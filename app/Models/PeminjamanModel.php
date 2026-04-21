<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';

    protected $allowedFields = [
    'id_buku', 'id_anggota', 'id_petugas', 
    'tanggal_pinjam', 'tanggal_kembali', 
    'tanggal_dikembalikan', 'status', 'denda'
];
}