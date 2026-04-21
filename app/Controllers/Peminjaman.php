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
}