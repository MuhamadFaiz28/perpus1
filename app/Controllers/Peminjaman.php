<?php

namespace App\Controllers;

use App\Models\PeminjamanModel;
use App\Models\BukuModel;

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
        $data['peminjaman'] = $this->peminjamanModel->select('peminjaman.*, buku.judul, users.nama')
                                      ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                                      ->join('users', 'users.id = peminjaman.id_anggota')
                                      ->orderBy('id_peminjaman', 'DESC')
                                      ->findAll();

        return view('peminjaman/index', $data);
    }

    public function saya() {
        $id_user = session()->get('id') ?? session()->get('id_users'); 
        if (!$id_user) return redirect()->to('/login');

        $data['pinjaman'] = $this->peminjamanModel->select('peminjaman.*, buku.judul, buku.cover')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('id_anggota', $id_user)
            ->orderBy('id_peminjaman', 'DESC')
            ->findAll();

        return view('peminjaman/riwayat_saya', $data);
    }

    // DIPERBAIKI: Status awal menjadi 'menunggu' dan tidak potong stok di sini
    public function pinjam($id_buku) {
        $buku = $this->bukuModel->find($id_buku);
        $stok_sekarang = $buku['stok'] ?? $buku['tersedia'];

        if ($stok_sekarang <= 0) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        $this->peminjamanModel->save([
            'id_buku'         => $id_buku,
            'id_anggota'      => session()->get('id') ?? session()->get('id_users'),
            'tanggal_pinjam'  => null, // Diisi saat konfirmasi
            'tanggal_kembali' => null, // Diisi saat konfirmasi
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

        // Update status dan baru potong stok di sini
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

        if ($peminjaman) {
            $tgl_kembali_seharusnya = strtotime($peminjaman['tanggal_kembali']);
            $tgl_hari_ini = strtotime(date('Y-m-d'));
            
            $denda = 0;
            if ($tgl_hari_ini > $tgl_kembali_seharusnya) {
                $selisih_detik = $tgl_hari_ini - $tgl_kembali_seharusnya;
                $selisih_hari = floor($selisih_detik / (60 * 60 * 24));
                $tarif_denda = 1000; // Set tarif denda per hari di sini
                $denda = $selisih_hari * $tarif_denda;
            }

            // Update status dan denda
            $db->table('peminjaman')->where('id_peminjaman', $id)->update([
                'status' => 'kembali',
                'tanggal_pengembalian' => date('Y-m-d'),
                'denda' => $denda
            ]);

            // Kembalikan stok buku (Tambah 1 ke kolom tersedia)
            $db->query("UPDATE buku SET tersedia = tersedia + 1 WHERE id_buku = ?", [$peminjaman['id_buku']]);

            return redirect()->back()->with('success', 'Buku berhasil dikembalikan. Denda: Rp ' . number_format($denda, 0, ',', '.'));
        }

        return redirect()->back()->with('error', 'Data tidak ditemukan.');
}

    public function laporan()
    {
        $tgl_mulai = $this->request->getGet('tgl_mulai');
        $tgl_selesai = $this->request->getGet('tgl_selesai');

        $builder = $this->peminjamanModel->select('peminjaman.*, buku.judul, users.nama')
                    ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                    ->join('users', 'users.id = peminjaman.id_anggota');

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
            ->join('users', 'users.id = peminjaman.id_anggota')
            ->where('denda >', 0)
            ->orderBy('id_peminjaman', 'DESC')
            ->get()
            ->getResultArray();

        $data['total_denda'] = $db->table('peminjaman')->selectSum('denda')->get()->getRow()->denda;

        return view('peminjaman/denda', $data);
    }
}