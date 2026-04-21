<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
public function index()
{
    // 🔒 proteksi role
    if (!in_array(session()->get('role'), ['admin','petugas'])) {
        return redirect()->to('/')->with('error', 'Akses ditolak!');
    }

    $db = \Config\Database::connect();

    $data['total_buku'] = $db->table('buku')->countAllResults();
    $data['total_rak'] = $db->table('rak')->countAllResults();
    $data['total_peminjaman'] = $db->table('peminjaman')->countAllResults();
    $data['total_anggota'] = $db->table('users')->where('role','anggota')->countAllResults();

    $data['dipinjam'] = $db->table('peminjaman')->where('status','dipinjam')->countAllResults();
    $data['kembali'] = $db->table('peminjaman')->where('status','kembali')->countAllResults();
    $data['terlambat'] = $db->table('peminjaman')->where('status','terlambat')->countAllResults();

    // 🔥 DATA GRAFIK (HARUS DI ATAS RETURN)
    $query = $db->query("
        SELECT 
            MONTH(tanggal_pinjam) as bulan,
            COUNT(*) as total
        FROM peminjaman
        GROUP BY MONTH(tanggal_pinjam)
    ");

    $result = $query->getResultArray();

    $bulan = [];
    $total = [];

    foreach ($result as $r) {
        $bulan[] = $r['bulan'];
        $total[] = $r['total'];
    }

    // 🔥 ANTISIPASI KOSONG
    if (empty($bulan)) {
        $bulan = [0];
        $total = [0];
    }

    $data['bulan'] = json_encode($bulan);
$data['total'] = json_encode($total);

return view('dashboard/index', $data);
        // 🔥 DATA GRAFIK PEMINJAMAN PER BULAN
$query = $db->query("
    SELECT 
        MONTH(tanggal_pinjam) as bulan,
        COUNT(*) as total
    FROM peminjaman
    GROUP BY MONTH(tanggal_pinjam)
");

$result = $query->getResultArray();

$bulan = [];
$total = [];

foreach ($result as $r) {
    $bulan[] = $r['bulan'];
    $total[] = $r['total'];
}

$data['bulan'] = json_encode($bulan);
$data['total'] = json_encode($total);
    }
}