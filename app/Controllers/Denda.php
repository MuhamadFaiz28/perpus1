<?php

namespace App\Controllers;

use App\Models\DendaModel;

class Denda extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Mengambil data denda digabung dengan info peminjaman dan user
        $data['semua_denda'] = $db->table('denda')
            ->select('denda.*, peminjaman.tanggal_pinjam, buku.judul, users.nama')
            ->join('peminjaman', 'peminjaman.id_peminjaman = denda.id_pengembalian')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->join('users', 'users.id = peminjaman.id_anggota')
            ->orderBy('status', 'ASC')
            ->get()->getResultArray();

        return view('admin/denda/index', $data);
    }

    public function bayar($id)
    {
        $model = new DendaModel();
        $model->update($id, ['status' => 'sudah_bayar']);

        return redirect()->back()->with('success', 'Pembayaran denda berhasil dikonfirmasi!');
    }
}