<?php

namespace App\Controllers;

class Rak extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $keyword = $this->request->getGet('keyword');

        if ($keyword) {
            $query = $db->query("
                SELECT rak.*, COUNT(buku.id_buku) as total_buku
                FROM rak
                LEFT JOIN buku ON buku.id_rak = rak.id_rak
                WHERE rak.nama_rak LIKE '%$keyword%'
                GROUP BY rak.id_rak
            ");
        } else {
            $query = $db->query("
                SELECT rak.*, COUNT(buku.id_buku) as total_buku
                FROM rak
                LEFT JOIN buku ON buku.id_rak = rak.id_rak
                GROUP BY rak.id_rak
            ");
        }

        $data['rak'] = $query->getResultArray();

        return view('rak/index', $data);
    }

    // ✅ TAMBAHAN (INI YANG KURANG)
    public function create()
    {
        return view('rak/create');
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $db->table('rak')->insert([
            'nama_rak' => $this->request->getPost('nama_rak'),
            'lokasi'   => $this->request->getPost('lokasi'),
        ]);

        return redirect()->to('/rak');
    }

    public function edit($id)
{
    $db = \Config\Database::connect();

    $data['rak'] = $db->table('rak')->getWhere(['id_rak' => $id])->getRowArray();

    return view('rak/edit', $data);
}

    public function update($id)
    {
        $db = \Config\Database::connect();

        $db->table('rak')->update([
            'nama_rak' => $this->request->getPost('nama_rak'),
            'lokasi'   => $this->request->getPost('lokasi'),
        ], ['id_rak' => $id]);

        return redirect()->to('/rak');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        $db->table('rak')->delete(['id_rak' => $id]);

        return redirect()->to('/rak');
    }
}