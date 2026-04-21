<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\PeminjamanModel;

class Buku extends BaseController
{
    protected $bukuModel;
    protected $peminjamanModel;

    public function __construct()
    {
        $this->bukuModel = new BukuModel();
        $this->peminjamanModel = new PeminjamanModel();
    }

    // 1. Tampilkan Semua Buku
    public function index()
    {
        $data['semua_buku'] = $this->bukuModel->findAll();
        return view('buku/index', $data);
    }

    // 2. Detail Buku
    public function detail($id)
    {
        $data['buku'] = $this->bukuModel->find($id);

        if (!$data['buku']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Buku tidak ditemukan!");
        }

        return view('buku/detail', $data);
    }

    // 3. Cari Buku
    public function cari()
    {
        $keyword = $this->request->getGet('keyword');

        if ($keyword) {
            $hasil = $this->bukuModel
                ->like('judul', $keyword)
                ->orLike('id_penulis', $keyword)
                ->findAll();
        } else {
            $hasil = [];
        }

        return view('buku/hasil_pencarian', [
            'buku' => $hasil,
            'keyword' => $keyword
        ]);
    }

    // 4. Download Buku
    public function download($id)
    {
        $db = \Config\Database::connect();

        $buku = $this->bukuModel->find($id);
        $id_user = session()->get('id_users');

        if ($buku) {

            $db->table('log_download')->insert([
                'id_buku'        => $id,
                'id_user'        => $id_user,
                'waktu_download' => date('Y-m-d H:i:s')
            ]);

            $path = FCPATH . 'assets/pdf/' . $buku['file_buku'];

            if (file_exists($path)) {
                return $this->response->download($path, null);
            }
        }

        return redirect()->back()->with('error', 'File tidak ditemukan!');
    }

    // 5. Histori Download
    public function histori_download()
    {
        $db = \Config\Database::connect();

        $data['histori'] = $db->table('log_download')
            ->select('log_download.*, buku.judul')
            ->join('buku', 'buku.id_buku = log_download.id_buku')
            ->orderBy('waktu_download', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/histori_download', $data);
    }

    // ==========================
    // 📚 PEMINJAMAN
    // ==========================

    public function pinjam($id_buku)
    {
        $this->peminjamanModel->save([
            'id_anggota' => session()->get('id_users'),
            'id_petugas' => session()->get('id_users'),
            'id_buku' => $id_buku,
            'tanggal_pinjam' => date('Y-m-d'),
            'tanggal_kembali' => date('Y-m-d', strtotime('+7 days')),
            'status' => 'dipinjam',
            'denda' => 0
        ]);

        return redirect()->back()->with('success', 'Buku berhasil dipinjam');
    }

    public function kembalikan($id)
    {
        $data = $this->peminjamanModel->find($id);

        $today = date('Y-m-d');
        $denda = 0;

        if ($today > $data['tanggal_kembali']) {
            $telat = (strtotime($today) - strtotime($data['tanggal_kembali'])) / 86400;
            $denda = $telat * 1000;
            $status = 'terlambat';
        } else {
            $status = 'kembali';
        }

        $this->peminjamanModel->update($id, [
            'status' => $status,
            'denda' => $denda
        ]);

        return redirect()->back()->with('success', 'Buku dikembalikan');
    }

    public function peminjaman()
    {
        $data['pinjam'] = $this->peminjamanModel
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('id_anggota', session()->get('id_users'))
            ->findAll();

        return view('buku/peminjaman', $data);
    }

    public function create()
    {
        return view('buku/create');
    }

    public function store()
    {
        $file = $this->request->getFile('file');

        $namaFile = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaFile = $file->getRandomName();
            $file->move('uploads', $namaFile);
        }

        $data = [
            'judul'        => $this->request->getPost('judul'),
            'isbn'         => $this->request->getPost('isbn'),
            'id_kategori'  => $this->request->getPost('id_kategori'),
            'id_penulis'   => $this->request->getPost('id_penulis'),
            'id_penerbit'  => $this->request->getPost('id_penerbit'),
            'id_rak'       => $this->request->getPost('id_rak'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'jumlah'       => $this->request->getPost('jumlah'),
            'tersedia'     => $this->request->getPost('tersedia'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'file'         => $namaFile
        ];

        $this->bukuModel->insert($data);

        return redirect()->to('/buku');
    }
}