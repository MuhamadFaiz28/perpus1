<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    // SESUAIKAN: Jika di phpMyAdmin namanyanya 'kategori', ubah di sini
    protected $table            = 'kategori'; 
    protected $primaryKey       = 'id_kategori';
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_kategori'];
}