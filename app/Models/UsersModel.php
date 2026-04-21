<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id'; // ✅ FIX (hapus huruf x)
    protected $allowedFields = ['nama', 'username', 'password', 'role', 'foto'];

    public function getUsersByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}