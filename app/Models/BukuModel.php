<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table      = 'buku';
    protected $primaryKey = 'id_buku';
    protected $returnType = 'array';

   protected $allowedFields = [
    'judul',
    'isbn',
    'id_kategori',
    'id_penulis',
    'id_penerbit',
    'id_rak',
    'tahun_terbit',
    'jumlah',
    'tersedia',
    'deskripsi',
    'file',
    'cover'
];
}