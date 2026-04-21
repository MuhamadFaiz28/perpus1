<?php

namespace App\Controllers;

use App\Models\BukuModel;
use App\Models\PeminjamanModel;
use App\Models\KategoriModel;
use App\Models\PenulisModel;
use App\Models\PenerbitModel;
use App\Models\RakModel;

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
        $data['buku'] = $this->bukuModel->findAll();
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

    // 3. Form Tambah Buku
    public function create()
    {
        $kategoriModel = new KategoriModel();
        $penulisModel = new PenulisModel();
        $penerbitModel = new PenerbitModel();
        $rakModel = new RakModel();

        $data = [
            'kategori' => $kategoriModel->findAll(),
            'penulis'  => $penulisModel->findAll(),
            'penerbit' => $penerbitModel->findAll(),
            'rak'      => $rakModel->findAll(),
        ];

        return view('buku/create', $data);
    }

    // 4. Simpan Buku Baru
    public function store()
    {
        $fileCover = $this->request->getFile('cover');
        $namaCover = 'default.jpg'; // Nama default jika tidak upload

        if ($fileCover && $fileCover->isValid() && !$fileCover->hasMoved()) {
            $namaCover = $fileCover->getRandomName();
            $fileCover->move('uploads/buku/', $namaCover);
        }

        $this->bukuModel->insert([
            'judul'        => $this->request->getPost('judul'),
            'isbn'         => $this->request->getPost('isbn'),
            'id_kategori'  => $this->request->getPost('id_kategori'),
            'id_penulis'   => $this->request->getPost('id_penulis'),
            'id_penerbit'  => $this->request->getPost('id_penerbit'),
            'id_rak'       => $this->request->getPost('id_rak'),
            'tahun_terbit' => $this->request->getPost('tahun_terbit'),
            'jumlah'       => $this->request->getPost('jumlah'),
            'tersedia'     => $this->request->getPost('jumlah'), // Awalnya stok tersedia = jumlah total
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'cover'        => $namaCover
        ]);

        return redirect()->to('/buku')->with('success', 'Buku berhasil ditambahkan');
    }

    // 5. Form Edit Buku
   public function edit($id)
{
    // Ambil data buku yang akan diedit
    $buku = $this->bukuModel->find($id);

    if (empty($buku)) {
        return redirect()->to('/buku')->with('error', 'Data tidak ditemukan');
    }

    // Load model pendukung
    $kategoriModel = new \App\Models\KategoriModel();
    $penulisModel  = new \App\Models\PenulisModel();
    $penerbitModel = new \App\Models\PenerbitModel();
    $rakModel      = new \App\Models\RakModel();

    $data = [
        'buku'       => $buku,
        'kategori'   => $kategoriModel->findAll(),
        'penulis'     => $penulisModel->findAll(), // Kita beri nama 'penulis'
        'penerbit'   => $penerbitModel->findAll(),
        'rak'        => $rakModel->findAll(),
    ];

    return view('buku/edit', $data);
}

    // 6. Update Buku
    public function update($id)
    {
        $fileCover = $this->request->getFile('cover');

        if ($fileCover && $fileCover->isValid() && !$fileCover->hasMoved()) {
            $namaCover = $fileCover->getRandomName();
            $fileCover->move('uploads/buku/', $namaCover);
            
            
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
            'cover'        => $namaCover
        ];

        $this->bukuModel->update($id, $data);
        return redirect()->to('/buku')->with('success', 'Data buku berhasil diperbarui!');
    }

    // ==========================
    // 📚 LOGIKA PEMINJAMAN
    // ==========================

    public function pinjam($id_buku)
    {
        $buku = $this->bukuModel->find($id_buku);
        
        if ($buku['tersedia'] <= 0) {
            return redirect()->back()->with('error', 'Stok buku habis!');
        }

        $this->peminjamanModel->save([
            'id_buku'         => $id_buku,
            'id_anggota'      => session()->get('id_users'),
            'id_petugas'      => session()->get('id_users'), // Sementara disamakan
            'tanggal_pinjam'  => date('Y-m-d'),
            'tanggal_kembali' => date('Y-m-d', strtotime('+7 days')),
            'status'          => 'dipinjam',
            'denda'           => 0
        ]);

        $this->bukuModel->update($id_buku, ['tersedia' => $buku['tersedia'] - 1]);

        return redirect()->to('/buku/peminjaman')->with('success', 'Buku berhasil dipinjam!');
    }

    public function kembalikan($id_peminjaman)
    {
        $pinjam = $this->peminjamanModel->find($id_peminjaman);
        if (!$pinjam) return redirect()->back();

        $tgl_kembali_seharusnya = new \DateTime($pinjam['tanggal_kembali']);
        $tgl_sekarang = new \DateTime(date('Y-m-d'));
        $denda = 0;

        if ($tgl_sekarang > $tgl_kembali_seharusnya) {
            $selisih = $tgl_sekarang->diff($tgl_kembali_seharusnya);
            $denda = $selisih->days * 1000;
        }

        $this->peminjamanModel->update($id_peminjaman, [
            'tanggal_dikembalikan' => date('Y-m-d'),
            'status'               => 'kembali',
            'denda'                => $denda
        ]);

        $buku = $this->bukuModel->find($pinjam['id_buku']);
        $this->bukuModel->update($pinjam['id_buku'], ['tersedia' => $buku['tersedia'] + 1]);

        return redirect()->back()->with('success', 'Buku dikembalikan. Denda: Rp ' . number_format($denda));
    }

    public function peminjaman()
    {
        $data['pinjam'] = $this->peminjamanModel
            ->select('peminjaman.*, buku.judul')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('id_anggota', session()->get('id_users'))
            ->findAll();

        return view('buku/peminjaman', $data);
    }
}