<?php

namespace App\Models;

use CodeIgniter\Model;

class PenerbitModel extends Model
{
    protected $table = 'penerbit'; // Sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'id_penerbit';
    protected $allowedFields = ['nama_penerbit'];
}