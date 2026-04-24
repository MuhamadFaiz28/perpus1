<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        // 1. Ambil Statistik Dashboard
        $total_stok_query = $db->table('buku')->selectSum('stok', 'total')->get()->getRowArray();
        $total_fisik = (int)($total_stok_query['total'] ?? 0);

        $tersedia_query = $db->table('buku')->selectSum('tersedia', 'total')->get()->getRowArray();
        $buku_tersedia = (int)($tersedia_query['total'] ?? 0);

        $sedang_dipinjam = $db->table('peminjaman')->where('status', 'dipinjam')->countAllResults();
        
        $total_terlambat = $db->table('peminjaman')
                                ->where('status', 'dipinjam')
                                ->where('tanggal_kembali <', $today)
                                ->countAllResults();

        // 2. Tambahan: Ambil Aktivitas Terakhir (Log Peminjaman)
        // Kita join ke tabel buku dan users agar tampil nama & judulnya
        $aktivitas = $db->table('peminjaman')
                        ->select('peminjaman.*, buku.judul, users.nama as nama_peminjam')
                        ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                        ->join('users', 'users.id = peminjaman.id_anggota')
                        ->orderBy('id_peminjaman', 'DESC')
                        ->limit(5) // Ambil 5 aktivitas terbaru saja
                        ->get()
                        ->getResultArray();

        // 3. Bungkus semua ke dalam array $data
        $data = [
            'total_buku'      => $total_fisik,
            'buku_tersedia'   => $buku_tersedia, 
            'sedang_dipinjam' => $sedang_dipinjam,
            'total_terlambat' => $total_terlambat,
            'buku'            => $db->table('buku')->orderBy('id_buku', 'DESC')->get()->getResultArray(),
            'aktivitas'       => $aktivitas, // Data aktivitas masuk ke sini
            'today'           => $today 
        ];

        // 4. Kirim ke View Dashboard
        return view('layouts/dashboard', $data);
    } 
}