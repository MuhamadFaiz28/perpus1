<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table          = 'buku';
    protected $primaryKey     = 'id_buku';
    protected $returnType     = 'array';
    protected $useAutoIncrement = true;

    // Field yang diizinkan untuk diisi/diupdate (CRUD)
    protected $allowedFields = [
        'judul',
        'isbn',
        'id_kategori',
        'id_penulis',
        'id_penerbit',
        'id_rak',
        'tahun_terbit',
        'stok',        // Wajib ada agar query selectSum di Controller jalan
        'tersedia',    // Bisa digunakan sebagai cadangan stok real-time
        'deskripsi',
        'file',
        'cover'
    ];

    /**
     * Mengambil total seluruh stok fisik buku
     * Menggunakan query builder CI4 agar lebih bersih
     */
    public function getTotalStok()
    {
        $result = $this->selectSum('stok', 'total')->first();
        return (int)($result['total'] ?? 0);
    }

    /**
     * Mengambil daftar buku yang stoknya menipis (Stok di bawah 3)
     */
    public function getStokKritis()
    {
        return $this->where('stok <', 3)
                    ->where('stok >', 0)
                    ->findAll();
    }

    /**
     * Mengambil buku yang benar-benar habis
     */
    public function getStokKosong()
    {
        return $this->where('stok', 0)->findAll();
    }
}