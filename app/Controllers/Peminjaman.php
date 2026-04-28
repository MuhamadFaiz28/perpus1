<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\BukuModel;
use App\Models\UsersModel;

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $bukuModel;
    protected $userModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->bukuModel = new BukuModel();
        $this->userModel = new UsersModel();
    }

    /**
     * Menampilkan daftar semua peminjaman (Admin)
     */
    public function index()
    {
        $peminjaman = $this->peminjamanModel->select('peminjaman.*, buku.judul, users.nama')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->join('users', 'users.id = peminjaman.id_anggota')
            ->findAll();

        // Logika denda real-time
        foreach ($peminjaman as &$p) {
            if ($p['status'] == 'dipinjam' || $p['status'] == 'Dipinjam') {
                $p['denda'] = $this->_hitungDendaOtomatis($p['jatuh_tempo']);
            }
        }

        $data['peminjaman'] = $peminjaman;
        return view('peminjaman/index', $data);
    }

    /**
     * Form tambah peminjaman (Admin)
     */
    public function tambah()
    {
        $data = [
            'title' => 'Tambah Peminjaman',
            'buku'  => $this->bukuModel->findAll(),
            'users' => $this->userModel->where('role', 'anggota')->findAll()
        ];

        return view('peminjaman/tambah', $data);
    }

    /**
     * Proses simpan peminjaman dari Admin
     */
    public function simpan()
    {
        $id_buku = $this->request->getPost('id_buku');
        $buku    = $this->bukuModel->find($id_buku);

        if (!$buku || !$this->_cekStok($buku)) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        $this->_prosesPinjam($id_buku, $this->request->getPost('id_anggota'), $buku);

        return redirect()->to('/peminjaman')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Menampilkan riwayat milik user sendiri
     */
    public function saya()
    {
        return $this->riwayat();
    }

    /**
     * Riwayat pinjaman milik user yang sedang login
     */
   public function riwayat()
    {
        // Mengambil ID dari session (mencoba semua kemungkinan nama variabel)
        $id_session = session()->get('id') ?? session()->get('id_user') ?? session()->get('user_id');
        $role = session()->get('role');

        $builder = $this->peminjamanModel->select('peminjaman.*, buku.judul, buku.cover, users.nama')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->join('users', 'users.id = peminjaman.id_anggota');

        if ($role !== 'admin') {
            // Pastikan ID session ditemukan
            if ($id_session) {
                $builder->where('peminjaman.id_anggota', $id_session);
            }
            
            // Menampilkan status yang umum digunakan agar data muncul kembali
            // Kita kecualikan 'Selesai_Hidden' agar tombol bersih tetap bekerja
            $builder->whereIn('peminjaman.status', ['dipinjam', 'pinjam', 'Kembali', 'Selesai', 'menunggu']);
        }

        $pinjaman = $builder->orderBy('id_peminjaman', 'DESC')->findAll();

        // Hitung denda untuk tampilan real-time
        foreach ($pinjaman as &$p) {
            if (isset($p['status']) && strtolower($p['status']) == 'dipinjam') {
                $p['denda'] = $this->_hitungDendaOtomatis($p['jatuh_tempo']);
            }
        }

        $data['pinjaman'] = $pinjaman;
        return view('peminjaman/riwayat_saya', $data);
    }

    public function riwayat_saya()
    {
        $db = \Config\Database::connect();
        $id_session = session()->get('id') ?? session()->get('id_user');

        $data = [
            'title' => 'Riwayat Peminjaman Saya',
            'pinjaman' => $db->table('peminjaman')
                            ->select('peminjaman.*, buku.judul')
                            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                            ->where('id_anggota', $id_session)
                            ->where('status !=', 'Selesai_Hidden') // Agar data yang sudah dibersihkan tidak muncul
                            ->orderBy('id_peminjaman', 'DESC')
                            ->get()
                            ->getResultArray()
        ];

        return view('peminjaman/riwayat_saya', $data);
    }

    public function pinjam($id_buku)
    {
        $buku = $this->bukuModel->find($id_buku);

        if (!$buku || !$this->_cekStok($buku)) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        $id_user = session()->get('id') ?? session()->get('id_user');
        $this->_prosesPinjam($id_buku, $id_user, $buku);

        return redirect()->to('/peminjaman/riwayat')->with('success', 'Buku berhasil dipinjam!');
    }

    /**
     * Konfirmasi peminjaman (jika ada sistem booking)
     */
    public function konfirmasi($id_peminjaman)
    {
        $pinjam = $this->peminjamanModel->find($id_peminjaman);
        if (!$pinjam) return redirect()->back()->with('error', 'Data tidak ditemukan');

        $buku = $this->bukuModel->find($pinjam['id_buku']);
        if (!$this->_cekStok($buku)) {
            return redirect()->back()->with('error', 'Gagal konfirmasi! Stok buku habis.');
        }

        $this->peminjamanModel->update($id_peminjaman, [
            'status'         => 'dipinjam',
            'tanggal_pinjam' => date('Y-m-d'),
            'jatuh_tempo'    => date('Y-m-d', strtotime('+7 days'))
        ]);

        $this->_updateStok($buku, -1);

        return redirect()->back()->with('success', 'Peminjaman berhasil dikonfirmasi!');
    }

    /**
     * Proses pengembalian buku dan hitung denda
     */
    public function kembalikan($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        if (!$peminjaman) return redirect()->back()->with('error', 'Data tidak ditemukan');

        $tgl_kembali = date('Y-m-d');
        $denda = $this->_hitungDendaOtomatis($peminjaman['jatuh_tempo']);

        $this->peminjamanModel->update($id, [
            'status'               => 'Kembali',
            'tanggal_dikembalikan' => $tgl_kembali,
            'denda'                => $denda
        ]);

        $buku = $this->bukuModel->find($peminjaman['id_buku']);
        $this->_updateStok($buku, 1);

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
    }

    /**
     * Laporan Transaksi
     */
    public function laporan()
    {
        $tgl_mulai   = $this->request->getGet('tgl_mulai');
        $tgl_selesai = $this->request->getGet('tgl_selesai');

        $builder = $this->peminjamanModel->select('peminjaman.*, buku.judul, users.nama')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->join('users', 'users.id = peminjaman.id_anggota');

        if ($tgl_mulai && $tgl_selesai) {
            $builder->where("tanggal_pinjam BETWEEN '$tgl_mulai' AND '$tgl_selesai'");
        }

        $data = [
            'transaksi'   => $builder->orderBy('tanggal_pinjam', 'DESC')->findAll(),
            'tgl_mulai'   => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai
        ];

        return view('peminjaman/laporan', $data);
    }

    /**
     * Daftar Denda
     */
        public function denda()
    {
        $data['denda_list'] = $this->peminjamanModel->select('peminjaman.*, buku.judul, users.nama as nama_peminjam')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->join('users', 'users.id = peminjaman.id_anggota')
            ->where('peminjaman.denda >', 0) // Hanya ambil yang punya denda
            ->orderBy('id_peminjaman', 'DESC')
            ->findAll();

        // Menghitung total pendapatan denda
        $total = $this->peminjamanModel->selectSum('denda')->get()->getRow()->denda;
        $data['total_pendapatan'] = $total ?? 0;

        return view('peminjaman/denda', $data);
    }

    /**
     * Mengubah status denda menjadi lunas (set ke 0)
     */
    public function lunas_denda($id_peminjaman)
    {
        if (session()->get('role') !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melunasi denda.');
        }

        $this->peminjamanModel->update($id_peminjaman, ['denda' => 0]);
        return redirect()->to('/peminjaman/denda')->with('success', 'Denda berhasil dilunasi!');
    }

    // --- PRIVATE HELPER METHODS ---

    private function _hitungDendaOtomatis($jatuh_tempo)
    {
        if (!$jatuh_tempo || $jatuh_tempo == '0000-00-00') return 0;
        
        $tgl_sekarang = strtotime(date('Y-m-d'));
        $tgl_jt = strtotime($jatuh_tempo);

        if ($tgl_sekarang > $tgl_jt) {
            $selisih = floor(($tgl_sekarang - $tgl_jt) / (60 * 60 * 24));
            return $selisih * 1000;
        }
        return 0;
    }

    private function _cekStok($buku)
    {
        $stok = $buku['stok'] ?? $buku['tersedia'] ?? 0;
        return $stok > 0;
    }

    private function _updateStok($buku, $tambah_kurang)
    {
        $kolom_stok = isset($buku['stok']) ? 'stok' : 'tersedia';
        $stok_baru  = ($buku[$kolom_stok]) + $tambah_kurang;
        
        return $this->bukuModel->update($buku['id_buku'], [$kolom_stok => $stok_baru]);
    }

    private function _prosesPinjam($id_buku, $id_anggota, $buku)
    {
        $this->peminjamanModel->save([
            'id_buku'        => $id_buku,
            'id_anggota'     => $id_anggota,
            'tanggal_pinjam' => date('Y-m-d'),
            'jatuh_tempo'    => date('Y-m-d', strtotime('+7 days')),
            'status'         => 'dipinjam',
            'denda'          => 0
        ]);

        $this->_updateStok($buku, -1);
    }

    public function hapusSemua()
    {
        // Ambil ID user yang sedang login
        $id_session = session()->get('id') ?? session()->get('id_user');

        // Kita tidak menghapus data (truncate), tapi hanya menyembunyikannya
        // dari halaman Riwayat Saya. Status 'Kembali' diubah jadi 'Selesai_Hidden'
        $this->peminjamanModel->where('id_anggota', $id_session)
                            ->where('status', 'Kembali')
                            ->set(['status' => 'Selesai_Hidden']) 
                            ->update();

        return redirect()->to('/peminjaman/riwayat_saya')->with('success', 'Riwayat berhasil dibersihkan.');
    }
}