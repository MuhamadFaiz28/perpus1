<?php

namespace App\Controllers;

class Peminjaman extends BaseController
{
    public function index()
{
    $db = \Config\Database::connect();

    $query = $db->query("
        SELECT p.*, b.judul
        FROM peminjaman p
        LEFT JOIN buku b ON b.id_buku = p.id_buku
    ");

    $data['peminjaman'] = $query->getResultArray();

    // 🔥 LOGIKA DENDA
    foreach ($data['peminjaman'] as &$p) {

        if ($p['status'] == 'dipinjam') {

            $hari_ini = date('Y-m-d');

            if ($hari_ini > $p['tanggal_kembali']) {

                $telat = (strtotime($hari_ini) - strtotime($p['tanggal_kembali'])) / (60 * 60 * 24);

                $denda = $telat * 1000;

                $p['status'] = 'terlambat';
                $p['denda'] = $denda;

                $db->table('peminjaman')->update([
                    'status' => 'terlambat',
                    'denda'  => $denda
                ], ['id_peminjaman' => $p['id_peminjaman']]);
            }
        }
    }

    return view('peminjaman/index', $data);
}

    public function create()
    {
        $db = \Config\Database::connect();

        $data['buku'] = $db->table('buku')->get()->getResultArray();

        return view('peminjaman/create', $data);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $id_buku = $this->request->getPost('id_buku');

        $buku = $db->table('buku')
            ->getWhere(['id_buku' => $id_buku])
            ->getRowArray();

        if ($buku['tersedia'] <= 0) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        $db->table('peminjaman')->insert([
            'id_anggota'      => $this->request->getPost('id_anggota'),
            'id_petugas'      => session('id'),
            'id_buku'         => $id_buku,
            'tanggal_pinjam'  => date('Y-m-d'),
            'tanggal_kembali' => $this->request->getPost('tanggal_kembali'),
            'status'          => 'dipinjam'
        ]);

        $db->table('buku')->update([
            'tersedia' => $buku['tersedia'] - 1
        ], ['id_buku' => $id_buku]);

        return redirect()->to('/peminjaman');
    }

    // ✅ SATU SAJA (FIX)
    public function kembali($id)
    {
        $db = \Config\Database::connect();

        $p = $db->table('peminjaman')
            ->getWhere(['id_peminjaman' => $id])
            ->getRowArray();

        // ❌ kalau sudah kembali
        if ($p['status'] == 'kembali') {
            return redirect()->to('/peminjaman');
        }

        // ✅ tambah stok
        $db->table('buku')
            ->set('tersedia', 'tersedia+1', false)
            ->where('id_buku', $p['id_buku'])
            ->update();

        // ✅ update status
        $db->table('peminjaman')->update([
            'status' => 'kembali',
            'tanggal_kembali' => date('Y-m-d')
        ], ['id_peminjaman' => $id]);

        return redirect()->to('/peminjaman');
    }
    public function pinjam($id_buku)
{
    $db = \Config\Database::connect();

    $buku = $db->table('buku')
        ->getWhere(['id_buku' => $id_buku])
        ->getRowArray();

    if ($buku['tersedia'] <= 0) {
        return redirect()->back()->with('error', 'Stok habis!');
    }

    $db->table('peminjaman')->insert([
        'id_anggota'      => 1, // bisa diganti session nanti
        'id_petugas'      => session('id'),
        'id_buku'         => $id_buku,
        'tanggal_pinjam'  => date('Y-m-d'),
        'tanggal_kembali' => date('Y-m-d', strtotime('+7 days')),
        'status'          => 'dipinjam'
    ]);

    $db->table('buku')->set('tersedia', 'tersedia-1', false)
        ->where('id_buku', $id_buku)
        ->update();

    return redirect()->to('/peminjaman');
}
}