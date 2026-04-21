<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';

    protected $allowedFields = [
        'id_anggota',
        'id_petugas',
        'id_buku',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'denda'
    ];
    public function peminjaman()
{
    $db = \Config\Database::connect();

    $data['pinjam'] = $db->table('peminjaman')
        ->join('buku', 'buku.id_buku = peminjaman.id_buku')
        ->where('id_anggota', session()->get('id_users'))
        ->get()
        ->getResultArray();

    return view('buku/peminjaman', $data);
}
public function pinjam($id_buku)
{
    // 1. Cek stok buku
    $buku = $this->bukuModel->find($id_buku);
    if ($buku['tersedia'] <= 0) {
        return redirect()->back()->with('error', 'Stok buku habis!');
    }

    // 2. Catat peminjaman (Durasi 7 hari)
    $this->peminjamanModel->save([
        'id_buku'         => $id_buku,
        'id_anggota'      => session()->get('id_users'),
        'tanggal_pinjam'  => date('Y-m-d'),
        'tanggal_kembali' => date('Y-m-d', strtotime('+7 days')),
        'status'          => 'dipinjam',
        'denda'           => 0
    ]);

    // 3. Kurangi stok tersedia di tabel buku
    $this->bukuModel->update($id_buku, [
        'tersedia' => $buku['tersedia'] - 1
    ]);

    return redirect()->to('/buku/peminjaman')->with('success', 'Buku berhasil dipinjam!');
}

public function kembalikan($id_peminjaman)
{
    $pinjam = $this->peminjamanModel->find($id_peminjaman);
    $tgl_kembali_seharusnya = new \DateTime($pinjam['tanggal_kembali']);
    $tgl_sekarang = new \DateTime(date('Y-m-d'));
    
    $denda = 0;
    $tarif_denda = 1000; // Rp 1.000 per hari

    // 1. Hitung Denda Otomatis
    if ($tgl_sekarang > $tgl_kembali_seharusnya) {
        $selisih = $tgl_sekarang->diff($tgl_kembali_seharusnya);
        $hari_terlambat = $selisih->days;
        $denda = $hari_terlambat * $tarif_denda;
    }

    // 2. Update status peminjaman
    $this->peminjamanModel->update($id_peminjaman, [
        'tanggal_dikembalikan' => date('Y-m-d'),
        'status'               => 'kembali',
        'denda'                => $denda
    ]);

    // 3. Kembalikan stok buku
    $buku = $this->bukuModel->find($pinjam['id_buku']);
    $this->bukuModel->update($pinjam['id_buku'], [
        'tersedia' => $buku['tersedia'] + 1
    ]);

    $pesan = ($denda > 0) ? "Buku dikembalikan. Denda: Rp " . number_format($denda) : "Buku dikembalikan tepat waktu.";
    return redirect()->back()->with('success', $pesan);
}
}