<?php

namespace App\Models;

use CodeIgniter\Model;

class BukuModel extends Model
{
    protected $table      = 'buku';
    protected $primaryKey = 'id_buku';
    protected $returnType = 'array';

    // Pastikan 'stok' atau 'jumlah' konsisten. 
    // Berdasarkan gambar dashboard Anda sebelumnya, kita akan gunakan 'stok'.
    protected $allowedFields = [
        'judul',
        'isbn',
        'id_kategori',
        'id_penulis',
        'id_penerbit',
        'id_rak',
        'tahun_terbit',
        'stok', // Menambahkan/mengubah dari 'jumlah' menjadi 'stok' agar sinkron dengan Controller
        'tersedia',
        'deskripsi',
        'file',
        'cover'
    ];

    /**
     * Fungsi bantuan untuk mengambil total seluruh stok buku di perpustakaan
     */
    public function getTotalStok()
    {
        return $this->selectSum('stok')->get()->getRow()->stok ?? 0;
    }

    /**
     * Fungsi untuk mengambil buku yang stoknya hampir habis (misal sisa 2)
     */
    public function getStokKritis()
    {
        return $this->where('stok <=', 2)->where('stok >', 0)->findAll();
    }
}