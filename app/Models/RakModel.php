<?php

namespace App\Models;

use CodeIgniter\Model;

class RakModel extends Model
{
    protected $table = 'rak'; // Sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'id_rak';
    protected $allowedFields = ['nama_rak', 'lokasi'];
}