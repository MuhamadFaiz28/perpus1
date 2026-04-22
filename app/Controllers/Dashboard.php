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

        // 2. Definisi Variabel Statistik (Menghilangkan error Undefined Variable)
        $data['total_buku']      = $bukuModel->countAll();
        $data['sirkulasi_aktif'] = $pinjamModel->where('status', 'dipinjam')->countAllResults();
        
        // Logika Terlambat: Status dipinjam dan sudah melewati tanggal hari ini
        $data['total_terlambat'] = $pinjamModel->where('status', 'dipinjam')
                                               ->where('tanggal_kembali <', date('Y-m-d'))
                                               ->countAllResults();
        
        $data['total_anggota']   = $db->table('users')->where('role', 'anggota')->countAllResults();

        // 3. Data Aktivitas Terakhir untuk Tabel
        $data['pinjam_terbaru']  = $pinjamModel->select('peminjaman.*, buku.judul, users.nama as nama_peminjam')
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
            // Mengubah angka bulan menjadi nama bulan singkat (Jan, Feb, dst)
            $list_bulan[] = date("M", mktime(0, 0, 0, $r['bulan'], 10));
            $list_total[] = (int)$r['total'];
        }

        // Antisipasi jika data grafik kosong agar Chart.js tetap jalan
        if (empty($list_bulan)) {
            $list_bulan = [date('M')];
            $list_total = [0];
        }

        $data['bulan'] = json_encode($list_bulan);
        $data['total'] = json_encode($list_total);

        // 5. Mengirimkan satu array $data ke View
        // Pastikan path 'dashboard' sesuai dengan lokasi file view Anda
        return view('dashboard', $data);
    }
    public function delete($id)
{
    // 1. Proteksi akses admin
    if (session()->get('role') != 'admin') {
        return redirect()->to('/')->with('error', 'Akses ditolak!');
    }

    $bukuModel = new \App\Models\BukuModel();
    
    // 2. Cari data buku untuk mendapatkan nama file cover
    $buku = $bukuModel->find($id);

    if ($buku) {
        // 3. Hapus file cover jika ada
        if (!empty($buku['cover']) && file_exists('uploads/buku/' . $buku['cover'])) {
            unlink('uploads/buku/' . $buku['cover']);
        }

        // 4. Hapus data dari database
        $bukuModel->delete($id);

        return redirect()->back()->with('success', 'Buku berhasil dihapus!');
    }

    return redirect()->back()->with('error', 'Buku tidak ditemukan!');
}
}
<?= $this->endSection() ?>