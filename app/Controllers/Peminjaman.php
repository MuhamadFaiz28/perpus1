<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\BukuModel;
use App\Models\UsersModel; // Tambahan untuk mengambil data anggota

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $bukuModel;

    public function __construct() {
        $this->peminjamanModel = new PeminjamanModel();
        $this->bukuModel = new BukuModel();
    }

    public function index()
{
    $id_user = session()->get('id') ?? session()->get('id_users');
    
    $builder = $this->peminjamanModel->select('peminjaman.*, buku.judul, users.nama')
                  ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                  ->join('users', 'users.id = peminjaman.id_anggota');

    // FITUR OTOMATIS: Hanya tampilkan yang BELUM kembali di halaman sirkulasi utama
    $builder->whereIn('peminjaman.status', ['dipinjam', 'menunggu']);

    if (session()->get('role') == 'anggota') {
        $builder->where('peminjaman.id_anggota', $id_user);
    }

    $data['peminjaman'] = $builder->orderBy('id_peminjaman', 'DESC')->findAll();

    return view('peminjaman/index', $data);
}
    // --- METHOD TAMBAHAN UNTUK FIX ERROR "METHOD NOT FOUND" ---
    
    public function tambah()
    {
        $userModel = new UsersModel();
        $data = [
            'title' => 'Tambah Peminjaman',
            'buku'  => $this->bukuModel->findAll(),
            'users' => $userModel->where('role', 'anggota')->findAll()
        ];

        return view('peminjaman/tambah', $data);
    }

    public function simpan()
    {
        $id_buku = $this->request->getPost('id_buku');
        $buku = $this->bukuModel->find($id_buku);
        $stok_sekarang = $buku['stok'] ?? $buku['tersedia'];

        if ($stok_sekarang <= 0) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        $this->peminjamanModel->save([
            'id_buku'         => $id_buku,
            'id_anggota'      => $this->request->getPost('id_anggota'),
            'tanggal_pinjam'  => date('Y-m-d'),
            'tanggal_kembali' => date('Y-m-d', strtotime('+7 days')),
            'status'          => 'dipinjam', 
            'denda'           => 0
        ]);

        // Update stok otomatis
        $kolom_stok = isset($buku['stok']) ? 'stok' : 'tersedia';
        $this->bukuModel->update($id_buku, [$kolom_stok => $stok_sekarang - 1]);

        return redirect()->to('/peminjaman')->with('success', 'Data berhasil ditambahkan!');
    }

    // --- AKHIR METHOD TAMBAHAN ---

    public function saya()
{
    $id_user = session()->get('id') ?? session()->get('id_users');
    
    // FILTER: Hanya mengambil yang sudah Kembali agar yang 'Dipinjam' tidak masuk riwayat
    $data['pinjaman'] = $this->peminjamanModel->select('peminjaman.*, buku.judul, buku.cover')
        ->join('buku', 'buku.id_buku = peminjaman.id_buku')
        ->where('id_anggota', $id_user)
        ->whereIn('status', ['Kembali', 'Selesai']) 
        ->orderBy('id_peminjaman', 'DESC')
        ->findAll();

    return view('peminjaman/riwayat_saya', $data);
}
    public function pinjam($id_buku) {
        $buku = $this->bukuModel->find($id_buku);
        $stok_sekarang = $buku['stok'] ?? $buku['tersedia'];

        if ($stok_sekarang <= 0) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        $this->peminjamanModel->save([
            'id_buku'         => $id_buku,
            'id_anggota'      => session()->get('id') ?? session()->get('id_users'),
            'tanggal_pinjam'  => null,
            'tanggal_kembali' => null,
            'status'          => 'menunggu', 
            'denda'           => 0
        ]);

        return redirect()->to('/peminjaman/saya')->with('success', 'Permintaan pinjam terkirim! Menunggu konfirmasi petugas.');
    }

    public function konfirmasi($id_peminjaman)
    {
        $pinjam = $this->peminjamanModel->find($id_peminjaman);
        if (!$pinjam) return redirect()->back()->with('error', 'Data tidak ditemukan');

        $buku = $this->bukuModel->find($pinjam['id_buku']);
        $kolom_stok = isset($buku['stok']) ? 'stok' : 'tersedia';

        if ($buku[$kolom_stok] <= 0) {
            return redirect()->back()->with('error', 'Gagal konfirmasi! Stok buku sedang kosong.');
        }

        $this->peminjamanModel->update($id_peminjaman, [
            'status' => 'dipinjam',
            'tanggal_pinjam' => date('Y-m-d'),
            'tanggal_kembali' => date('Y-m-d', strtotime('+7 days'))
        ]);

        $this->bukuModel->update($pinjam['id_buku'], [
            $kolom_stok => $buku[$kolom_stok] - 1
        ]);

        return redirect()->back()->with('success', 'Peminjaman berhasil dikonfirmasi!');
    }

    public function kembalikan($id)
    {
        $db = \Config\Database::connect();
        $peminjaman = $db->table('peminjaman')->where('id_peminjaman', $id)->get()->getRowArray();
        $tgl_kembali = date('Y-m-d');
        
        $jatuh_tempo = $peminjaman['jatuh_tempo'] ?? null;
        $selisih = $jatuh_tempo ? (strtotime($tgl_kembali) - strtotime($jatuh_tempo)) / (60 * 60 * 24) : 0;
        $terlambat = ($selisih > 0) ? $selisih : 0;
        $denda = $terlambat * 0; 

        $db->table('peminjaman')->where('id_peminjaman', $id)->update([
            'status'               => 'Kembali', 
            'tanggal_dikembalikan' => $tgl_kembali,
            'denda'                => $denda
        ]);

        return redirect()->back()->with('success', 'Buku berhasil dikembalikan!');
    }

    public function laporan()
    {
        $tgl_mulai = $this->request->getGet('tgl_mulai');
        $tgl_selesai = $this->request->getGet('tgl_selesai');

        $builder = $this->peminjamanModel->select('peminjaman.*, buku.judul, users.nama')
                    ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                    ->join('users', 'users.id = peminjaman.id_anggota'); // Perbaikan: id_users -> id

        if ($tgl_mulai && $tgl_selesai) {
            $builder->where('tanggal_pinjam >=', $tgl_mulai)
                    ->where('tanggal_pinjam <=', $tgl_selesai);
        }

        $data['transaksi'] = $builder->orderBy('tanggal_pinjam', 'DESC')->findAll();
        $data['tgl_mulai'] = $tgl_mulai;
        $data['tgl_selesai'] = $tgl_selesai;

        return view('peminjaman/laporan', $data);
    }

    public function denda()
    {
        $db = \Config\Database::connect();
        $data['denda_list'] = $db->table('peminjaman')
            ->select('peminjaman.*, buku.judul, users.nama as nama_peminjam')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->join('users', 'users.id = peminjaman.id_anggota') // Perbaikan: id_users -> id
            ->where('denda >', 0)
            ->orderBy('id_peminjaman', 'DESC')
            ->get()
            ->getResultArray();

        $data['total_denda'] = $db->table('peminjaman')->selectSum('denda')->get()->getRow()->denda;

        return view('peminjaman/denda', $data);
    }

    public function dataDenda()
    {
        $db = \Config\Database::connect();
        $id_user = session()->get('id') ?? session()->get('id_users');

        $denda = $db->table('peminjaman')
            ->select('peminjaman.*, users.nama as nama_peminjam, buku.judul as judul_buku')
            ->join('users', 'users.id = peminjaman.id_anggota') // Perbaikan: id_users -> id
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('peminjaman.denda >', 0)
            ->where('peminjaman.id_anggota', $id_user)
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->get()
            ->getResultArray();

        $total = $db->table('peminjaman')
                   ->where('id_anggota', $id_user)
                   ->selectSum('denda')->get()->getRow()->denda ?? 0;

        $data = [
            'title'            => 'Data Denda Anggota',
            'denda'            => $denda,
            'total_pendapatan' => $total
        ];

        return view('users/denda_anggota', $data);
    }

    public function lunas_denda($id)
    {
        $db = \Config\Database::connect();
        $db->table('peminjaman')
           ->where('id_peminjaman', $id)
           ->update(['denda' => 0]);

        return redirect()->to(base_url('peminjaman'))->with('success', 'Denda berhasil dibayar.');
    }
    public function hapusSemua()
{
    $id_user = session()->get('id') ?? session()->get('id_users');
    
    // Hanya menghapus riwayat milik user yang login dan statusnya sudah Kembali/Selesai
    $this->peminjamanModel->where('id_anggota', $id_user)
                          ->whereIn('status', ['Kembali', 'Selesai'])
                          ->delete();

    return redirect()->back()->with('success', 'Semua riwayat berhasil dibersihkan.');
}
}