<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\PeminjamanModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $today = date('Y-m-d');

        // 1. TOTAL STOK (Menghitung jumlah fisik semua buku)
        $total_stok_query = $db->table('buku')->selectSum('stok', 'total')->get()->getRowArray();
        $total_fisik = (int)($total_stok_query['total'] ?? 0);

        // 2. DIPINJAM (Menghitung berapa banyak buku yang belum kembali)
        $sedang_dipinjam = $db->table('peminjaman')
                              ->where('status', 'dipinjam')
                              ->countAllResults();

        // 3. TERSEDIA (Mengambil langsung dari kolom 'tersedia' di database agar sinkron)
        $tersedia_query = $db->table('buku')->selectSum('tersedia', 'total')->get()->getRowArray();
        $buku_tersedia = (int)($tersedia_query['total'] ?? 0);

        // 4. TERLAMBAT
        $total_terlambat = $db->table('peminjaman')
                                ->where('status', 'dipinjam')
                                ->where('tanggal_kembali <', $today)
                                ->countAllResults();

        // Menyusun data utama
        $data = [
            'total_buku'      => $total_fisik,
            'buku_tersedia'   => $buku_tersedia, 
            'sedang_dipinjam' => $sedang_dipinjam,
            'total_terlambat' => $total_terlambat,
            'buku'            => $db->table('buku')->orderBy('id_buku', 'DESC')->get()->getResultArray(),
            'today'           => $today
        ];

        // 5. Data Grafik (Dimasukkan ke dalam array $data sebelum return)
        $data['bulan'] = json_encode([date('M')]); 
        $data['total'] = json_encode([0]);

        // Kirim semua data ke view dashboard
        return view('layouts/dashboard', $data);
    } // Akhir fungsi index

    public function tambah_stok($id)
    {
        if (!in_array(session()->get('role'), ['admin', 'petugas'])) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $bukuModel = new BukuModel();
        $buku = $bukuModel->find($id);

        if ($buku) {
            $stok_sekarang = (int)($buku['stok'] ?? 0);
            $tersedia_sekarang = (int)($buku['tersedia'] ?? 0);
            
            // Saat stok tambah, kolom tersedia juga harus tambah 1 agar sinkron
            $bukuModel->update($id, [
                'stok' => $stok_sekarang + 1,
                'tersedia' => $tersedia_sekarang + 1
            ]);
            return redirect()->to('/dashboard')->with('success', 'Stok berhasil ditambah.');
        }

        return redirect()->back()->with('error', 'Buku tidak ditemukan.');
    }

    public function delete($id)
    {
        if (session()->get('role') != 'admin') {
            return redirect()->back()->with('error', 'Hanya Admin yang berwenang.');
        }

        $bukuModel = new BukuModel();
        $buku = $bukuModel->find($id);

        if ($buku) {
            if (!empty($buku['cover']) && file_exists('uploads/buku/' . $buku['cover'])) {
                unlink('uploads/buku/' . $buku['cover']);
            }
            $bukuModel->delete($id);
            return redirect()->back()->with('success', 'Buku dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal hapus.');
    }
}