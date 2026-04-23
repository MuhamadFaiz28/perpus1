<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\UsersModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // 1. Proteksi Role: Hanya Admin & Petugas
        if (!in_array(session()->get('role'), ['admin', 'petugas'])) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $db = \Config\Database::connect();
        $bukuModel = new BukuModel();
        $pinjamModel = new PeminjamanModel();

        // 2. Statistik Kartu (Sinkronisasi Stok)
        
        // Total Buku Fisik (Menjumlahkan seluruh stok dari tabel buku)
        $total_stok_query = $bukuModel->selectSum('stok')->first();
        $data['total_buku'] = (int)($total_stok_query['stok'] ?? 0);
        
        // Sedang Dipinjam
        $data['sedang_dipinjam'] = $pinjamModel->where('status', 'dipinjam')->countAllResults();
        
        // Tersedia (Logic: Total Stok Fisik saat ini di rak)
        // Jika Anda ingin ini otomatis berkurang saat dipinjam, pastikan saat proses "Pinjam" di controller lain, 
        // Anda juga melakukan $bukuModel->update() untuk mengurangi kolom stok.
        $data['buku_tersedia'] = $data['total_buku']; 
        
        // Terlambat
        $data['total_terlambat'] = $pinjamModel->where('status', 'dipinjam')
                                               ->where('tanggal_kembali <', date('Y-m-d'))
                                               ->countAllResults();
        
        $data['total_anggota'] = $db->table('users')->where('role', 'anggota')->countAllResults();

        // 3. Data Sirkulasi Terbaru
        $data['pinjam_terbaru'] = $pinjamModel->select('peminjaman.*, buku.judul, users.nama as nama_peminjam')
                                               ->join('buku', 'buku.id_buku = peminjaman.id_buku', 'left')
                                               ->join('users', 'users.id = peminjaman.id_anggota', 'left')
                                               ->orderBy('peminjaman.id_peminjaman', 'DESC')
                                               ->findAll(5);

        // 4. Data Grafik
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

        $data['bulan'] = json_encode(!empty($list_bulan) ? $list_bulan : [date('M')]);
        $data['total'] = json_encode(!empty($list_total) ? $list_total : [0]);
        
        // Mengambil semua buku untuk katalog "Jelajahi Buku"
        $data['buku']  = $bukuModel->findAll(); 

        return view('dashboard', $data);
    }

    // FITUR BARU: Tambah Stok Cepat
    public function tambah_stok($id)
    {
        if (!in_array(session()->get('role'), ['admin', 'petugas'])) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $bukuModel = new BukuModel();
        $buku = $bukuModel->find($id);

        if ($buku) {
            $stok_baru = (int)$buku['stok'] + 1;
            $bukuModel->update($id, ['stok' => $stok_baru]);
            
            return redirect()->to('/dashboard')->with('success', 'Stok buku "' . $buku['judul'] . '" berhasil ditambah!');
        }

        return redirect()->back()->with('error', 'Buku tidak ditemukan!');
    }

    public function delete($id)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->back()->with('error', 'Hanya Admin yang boleh menghapus buku!');
        }

        $bukuModel = new BukuModel();
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
}