<?php

namespace App\Controllers;

use App\Models\DendaModel;

class Denda extends BaseController
{
    ppublic function index()
{
    $db = \Config\Database::connect();
    
    // Query untuk mengambil data yang hanya memiliki denda
    $data['denda_anggota'] = $db->table('peminjaman')
        ->select('peminjaman.*, users.nama, buku.judul')
        ->join('users', 'users.id_users = peminjaman.id_anggota')
        ->join('buku', 'buku.id_buku = peminjaman.id_buku')
        ->where('denda >', 0) // HANYA ambil yang ada dendanya
        ->get()
        ->getResultArray();

    // Hitung total pendapatan denda untuk ditampilkan di header
    $queryTotal = $db->table('peminjaman')->selectSum('denda')->get()->getRow();
    $data['total_pendapatan'] = $queryTotal->denda ?? 0;

    return view('denda/index', $data);
}

    public function bayar($id)
    {
        $model = new DendaModel();
        $model->update($id, ['status' => 'sudah_bayar']);

        return redirect()->back()->with('success', 'Pembayaran denda berhasil dikonfirmasi!');
    }
}