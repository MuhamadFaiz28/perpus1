<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\UsersModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // 1. Proteksi Role: Memastikan hanya Admin/Petugas yang bisa akses
        if (!in_array(session()->get('role'), ['admin', 'petugas'])) {
            return redirect()->to('/')->with('error', 'Akses ditolak!');
        }

        $db = \Config\Database::connect();
        $bukuModel = new BukuModel();
        $pinjamModel = new PeminjamanModel();

        // 2. Definisi Variabel Statistik (Sesuai Gambar)
        
        // Kartu 1: TOTAL BUKU
        $data['total_buku'] = $bukuModel->countAll();
        
        // Kartu 2: TERSEDIA (Buku yang statusnya tidak sedang dipinjam)
        // Asumsi: Anda memiliki kolom 'status' di tabel buku
        $data['tersedia'] = $bukuModel->where('status', 'tersedia')->countAllResults();
        
        // Kartu 3: SEDANG DIPINJAM
        $data['sedang_dipinjam'] = $pinjamModel->where('status', 'dipinjam')->countAllResults();
        
        // Kartu 4: TERLAMBAT (Status dipinjam dan melewati tanggal kembali)
        $data['total_terlambat'] = $pinjamModel->where('status', 'dipinjam')
                                               ->where('tanggal_kembali <', date('Y-m-d'))
                                               ->countAllResults();
        
        // Tambahan statistik lainnya jika diperlukan
        $data['total_anggota'] = $db->table('users')->where('role', 'anggota')->countAllResults();

        // 3. Data Aktivitas Terakhir untuk Tabel
        $data['pinjam_terbaru'] = $pinjamModel->select('peminjaman.*, buku.judul, users.nama as nama_peminjam')
                                               ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                                               ->join('users', 'users.id = peminjaman.id_anggota')
                                               ->orderBy('peminjaman.id_peminjaman', 'DESC')
                                               ->findAll(5);

        // 4. Data Grafik Peminjaman Per Bulan
        $query = $db->query("
            SELECT 
                MONTH(tanggal_pinjam) as bulan,
                COUNT(*) as total
            FROM peminjaman
            WHERE YEAR(tanggal_pinjam) = YEAR(CURRENT_DATE)
            GROUP BY MONTH(tanggal_pinjam)
            ORDER BY bulan ASC
        ");

        $result = $query->getResultArray();
        $list_bulan = [];
        $list_total = [];

        foreach ($result as $r) {
            $list_bulan[] = date("M", mktime(0, 0, 0, $r['bulan'], 10));
            $list_total[] = (int)$r['total'];
        }

        if (empty($list_bulan)) {
            $list_bulan = [date('M')];
            $list_total = [0];
        }

        $data['bulan'] = json_encode($list_bulan);
        $data['total'] = json_encode($list_total);

        return view('dashboard', $data);
    }

    public function delete($id)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to('/')->with('error', 'Akses ditolak!');
        }

        $bukuModel = new \App\Models\BukuModel();
        $buku = $bukuModel->find($id);

        if ($buku) {
            if (!empty($buku['cover']) && file_exists('uploads/buku/' . $buku['cover'])) {
                unlink('uploads/buku/' . $buku['cover']);
            }
            $bukuModel->delete($id);
            return redirect()->back()->with('success', 'Buku berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Buku tidak ditemukan!');
    }
    public function index()
    {
        $bukuModel = new BukuModel();
        $pinjamModel = new PeminjamanModel();

        // 1. Total Buku: Menghitung semua stok buku yang terdaftar
        $total_buku = $bukuModel->selectSum('stok')->first();

        // 2. Sedang Dipinjam: Menghitung transaksi yang statusnya belum kembali
        $sedang_dipinjam = $pinjamModel->where('status', 'dipinjam')->countAllResults();

        // 3. Terlambat: Menghitung transaksi dipinjam yang melewati tanggal kembali
        $total_terlambat = $pinjamModel->where('status', 'dipinjam')
                                       ->where('tanggal_kembali <', date('Y-m-d'))
                                       ->countAllResults();

        // 4. Tersedia: Total stok dikurangi yang sedang dipinjam
        $tersedia = ($total_buku['stok'] ?? 0) - $sedang_dipinjam;

        $data = [
            'total_buku'      => $total_buku['stok'] ?? 0,
            'tersedia'        => $tersedia,
            'sedang_dipinjam' => $sedang_dipinjam,
            'total_terlambat' => $total_terlambat,
            'buku'            => $bukuModel->findAll(10), // Untuk list buku di bawah
            'pinjam_terbaru'  => $pinjamModel->getTerbaru(), // Method custom di model
        ];

        return view('dashboard', $data);
    }
}