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

    public function saya() {
        // Ambil ID sesuai session login Anda (pastikan namanya benar: 'id' atau 'id_users')
        $id_user = session()->get('id') ?? session()->get('id_users'); 
        
        if (!$id_user) return redirect()->to('/login');

        $data['pinjaman'] = $this->peminjamanModel->select('peminjaman.*, buku.judul, buku.cover')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('id_anggota', $id_user)
            ->orderBy('id_peminjaman', 'DESC')
            ->findAll();

        return view('peminjaman/riwayat_saya', $data);
    }

    public function pinjam($id_buku) {
        $buku = $this->bukuModel->find($id_buku);
        
        // Sesuaikan dengan kolom di database Anda (stok atau tersedia)
        $stok_sekarang = $buku['stok'] ?? $buku['tersedia'];

        if ($stok_sekarang <= 0) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        $this->peminjamanModel->save([
            'id_buku'         => $id_buku,
            'id_anggota'      => session()->get('id') ?? session()->get('id_users'),
            'tanggal_pinjam'  => date('Y-m-d'),
            'tanggal_kembali' => date('Y-m-d', strtotime('+7 days')),
            'status'          => 'dipinjam',
            'denda'           => 0
        ]);

        // Kurangi stok (Gunakan kolom yang sesuai: 'stok' atau 'tersedia')
        $kolom_stok = isset($buku['stok']) ? 'stok' : 'tersedia';
        $this->bukuModel->update($id_buku, [$kolom_stok => $stok_sekarang - 1]);

        return redirect()->to('/dashboard')->with('success', 'Buku berhasil dipinjam!');
    }

    public function kembalikan($id_peminjaman) {
        $pinjam = $this->peminjamanModel->find($id_peminjaman);
        if (!$pinjam) return redirect()->back()->with('error', 'Data tidak ditemukan');

        $tgl_kembali_seharusnya = new \DateTime($pinjam['tanggal_kembali']);
        $tgl_sekarang = new \DateTime(date('Y-m-d'));
        
        $denda = 0;
        if ($tgl_sekarang > $tgl_kembali_seharusnya) {
            $hari_terlambat = $tgl_sekarang->diff($tgl_kembali_seharusnya)->days;
            $denda = $hari_terlambat * 1000; 
        }

        $this->peminjamanModel->update($id_peminjaman, [
            'tanggal_dikembalikan' => date('Y-m-d'),
            'status'               => 'kembali',
            'denda'                => $denda
        ]);

        // Kembalikan stok buku
        $buku = $this->bukuModel->find($pinjam['id_buku']);
        $kolom_stok = isset($buku['stok']) ? 'stok' : 'tersedia';
        $this->bukuModel->update($pinjam['id_buku'], [$kolom_stok => ($buku[$kolom_stok] + 1)]);

        return redirect()->back()->with('success', 'Buku dikembalikan. Denda: Rp ' . number_format($denda));
    }
}