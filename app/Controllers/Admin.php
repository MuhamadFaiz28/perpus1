<?php

namespace App\Controllers;

class Admin extends BaseController
{
    // ... method index atau dashboard lainnya ...

    public function data_peminjaman()
    {
        // Query untuk mengambil data peminjaman lengkap
        $data['peminjaman'] = $this->db->table('peminjaman')
            ->select('peminjaman.*, tb_buku.judul, tb_users.nama_lengkap')
            ->join('tb_buku', 'tb_buku.id_buku = peminjaman.id_buku')
            ->join('tb_users', 'tb_users.id_users = peminjaman.id_anggota')
            ->orderBy('peminjaman.id_peminjaman', 'DESC')
            ->get()->getResultArray();

        return view('admin/data_peminjaman', $data);
    }
}