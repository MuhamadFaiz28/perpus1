<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        $id_user = session()->get('id') ?? session()->get('id_user');
        $role = session()->get('role');

        // 1. Statistik Dasar
        $total_buku = $db->table('buku')->countAllResults();
        
        // Menghitung Anggota Aktif (hanya yang role-nya anggota)
        $total_anggota = $db->table('users')->where('role', 'anggota')->countAllResults();

        // Menghitung Sirkulasi Aktif (Buku yang sedang dipinjam)
        $sirkulasi_aktif = $db->table('peminjaman')->where('status', 'dipinjam')->countAllResults();

        // Menghitung Total Pendapatan Denda (untuk card denda)
        $total_denda = $db->table('peminjaman')->selectSum('denda')->get()->getRowArray();
        $total_pendapatan = (int)($total_denda['denda'] ?? 0);

        // 2. Data untuk Tabel Log Aktivitas
        $builder_logs = $db->table('peminjaman')
                           ->select('peminjaman.*, buku.judul, users.nama as nama_peminjam')
                           ->join('buku', 'buku.id_buku = peminjaman.id_buku')
                           ->join('users', 'users.id = peminjaman.id_anggota');
        
        // Jika yang login Anggota, hanya tampilkan log miliknya sendiri
        if ($role == 'anggota') {
            $builder_logs->where('id_anggota', $id_user);
        }

        $logs = $builder_logs->orderBy('id_peminjaman', 'DESC')
                             ->limit(5)
                             ->get()
                             ->getResultArray();

        // Menyesuaikan isi pesan log (agar variabel $l['pesan'] tidak kosong)
        foreach ($logs as &$l) {
            $l['pesan'] = "Peminjaman buku " . $l['judul'] . " oleh " . $l['nama_peminjam'];
            $l['status_verifikasi'] = ($l['status'] == 'menunggu') ? 'pending' : 'verified';
        }

        // 3. Siapkan Data untuk dikirim ke View
        $data = [
            'total_buku'       => $total_buku,
            'total_anggota'    => $total_anggota,
            'sirkulasi_aktif'  => $sirkulasi_aktif,
            'total_pendapatan' => $total_pendapatan,
            'logs'             => $logs,
            'users' => $db->table('users')->get()->getResultArray(),
            'today'            => $today
        ];

        // 4. Pastikan path view sesuai (tadi Anda menggunakan layouts/dashboard atau dashboard?)
        return view('layouts/dashboard', $data);
    }
}