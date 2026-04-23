<?php

namespace App\Controllers;

class Home extends BaseController
{
    // SEMUA LOGIKA HARUS DI DALAM SINI
    public function index()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        // 1. Ambil Statistik
        $total_stok_query = $db->table('buku')->selectSum('stok', 'total')->get()->getRowArray();
        $total_fisik = (int)($total_stok_query['total'] ?? 0);

        // Mengambil kolom 'tersedia' langsung dari database sesuai keinginan Anda
        $tersedia_query = $db->table('buku')->selectSum('tersedia', 'total')->get()->getRowArray();
        $buku_tersedia = (int)($tersedia_query['total'] ?? 0);

        $sedang_dipinjam = $db->table('peminjaman')->where('status', 'dipinjam')->countAllResults();
        
        $total_terlambat = $db->table('peminjaman')
                                ->where('status', 'dipinjam')
                                ->where('tanggal_kembali <', $today)
                                ->countAllResults();

        // 2. Bungkus ke dalam array $data (DI DALAM FUNGSI)
        $data = [
            'total_buku'      => $total_fisik,
            'buku_tersedia'   => $buku_tersedia, 
            'sedang_dipinjam' => $sedang_dipinjam,
            'total_terlambat' => $total_terlambat,
            'buku'            => $db->table('buku')->orderBy('id_buku', 'DESC')->get()->getResultArray(),
            'today'           => $today 
        ];

        // 3. Kirim ke View
        return view('layouts/dashboard', $data);
    } 
    // AKHIR FUNGSI
}