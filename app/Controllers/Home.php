<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        // 1. Statistik
        $total_buku = $db->table('buku')->countAllResults();

        // Pastikan 'dipinjam' huruf kecil sesuai gambar database kamu sebelumnya
        $sedang_dipinjam = $db->table('peminjaman')
                              ->where('status', 'dipinjam')
                              ->countAllResults();

        // Hitung terlambat (status dipinjam DAN tanggal kembali < hari ini)
        $total_terlambat = $db->table('peminjaman')
                                ->where('status', 'dipinjam')
                                ->where('tanggal_kembali <', $today)
                                ->countAllResults();

        $tersedia = $total_buku - $sedang_dipinjam;

        // 2. Ambil List Buku untuk Katalog
        $buku = $db->table('buku')->limit(10)->get()->getResultArray();

        // 3. Ambil Sirkulasi Terbaru (Hanya jika Admin yang login)
        $pinjam_terbaru = [];
        if (session()->get('role') == 'admin') {
            try {
                $pinjam_terbaru = $db->table('peminjaman')
                    ->select('peminjaman.*, buku.judul, users.username as nama_peminjam')
                    ->join('buku', 'buku.id_buku = peminjaman.id_buku', 'left')
                    ->join('users', 'users.id_users = peminjaman.id_users', 'left')
                    ->orderBy('peminjaman.id_peminjaman', 'DESC')
                    ->limit(5)
                    ->get()
                    ->getResultArray();
            } catch (\Exception $e) {
                $pinjam_terbaru = []; 
            }
        }

        $data = [
            'total_buku'      => $total_buku,
            'tersedia'        => $tersedia,
            'sedang_dipinjam' => $sedang_dipinjam,
            'total_terlambat' => $total_terlambat,
            'buku'            => $buku,
            'pinjam_terbaru'  => $pinjam_terbaru,
            'today'           => $today // Kita kirim variabel hari ini ke view
        ];

        // Memanggil view dashboard di dalam folder layouts
        return view('layouts/dashboard', $data);
    }
}