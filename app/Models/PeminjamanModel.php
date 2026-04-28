<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    protected $returnType = 'array';

    protected $allowedFields = [
        'id_anggota', 'id_petugas', 'id_buku', 
        'tanggal_pinjam', 'tanggal_kembali', 
        'tanggal_dikembalikan', 'status',
        'jatuh_tempo', 'denda',
    ];
}