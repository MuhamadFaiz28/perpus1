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
    public function detail($id = null)
    {
        $buku = $this->bukuModel->find($id);

        if (!$buku) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Buku dengan ID $id tidak ditemukan!");
        }

        $data = [
            'title' => 'Detail Buku - ' . $buku['judul'],
            'buku'  => $buku,
        ];

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
        $namaCover = 'default.jpg';

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
            'tersedia'     => $this->request->getPost('jumlah'),
            'deskripsi'    => $this->request->getPost('deskripsi'),
            'cover'        => $namaCover
        ]);

        return redirect()->to('/buku')->with('success', 'Buku berhasil ditambahkan');
    }

    // 5. Form Edit Buku
    public function edit($id)
    {
        $buku = $this->bukuModel->find($id);

        if (empty($buku)) {
            return redirect()->to('/buku')->with('error', 'Data tidak ditemukan');
        }

        $data = [
            'buku'      => $buku,
            'kategori'  => (new KategoriModel())->findAll(),
            'penulis'   => (new PenulisModel())->findAll(),
            'penerbit'  => (new PenerbitModel())->findAll(),
            'rak'       => (new RakModel())->findAll(),
        ];

        return view('buku/edit', $data);
    }

    // 6. Update Buku
    public function update($id)
    {
        $fileCover = $this->request->getFile('cover');
        $bukuLama = $this->bukuModel->find($id);
        $namaCover = $bukuLama['cover'];

        if ($fileCover && $fileCover->isValid() && !$fileCover->hasMoved()) {
            $namaCover = $fileCover->getRandomName();
            $fileCover->move('uploads/buku/', $namaCover);
            
            // Hapus cover lama jika bukan default
            if ($bukuLama['cover'] != 'default.jpg' && !empty($bukuLama['cover'])) {
                $path = 'uploads/buku/' . $bukuLama['cover'];
                if (file_exists($path) && is_file($path)) {
                    unlink($path);
                }
            }
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
    // 📚 LOGIKA PEMINJAMAN (DINAMIS)
    // ==========================

    // Menampilkan form pilihan tanggal sebelum pinjam
    public function konfirmasi_pinjam($id)
    {
        $data['buku'] = $this->bukuModel->find($id);
        if (!$data['buku']) return redirect()->back()->with('error', 'Buku tidak ditemukan');
        
        return view('buku/konfirmasi_pinjam', $data);
    }

    // Proses simpan dengan tanggal pilihan user
    public function proses_pinjam($id_buku)
    {
        $buku = $this->bukuModel->find($id_buku);
        $tgl_kembali = $this->request->getPost('jatuh_tempo');
        
        if ($buku['tersedia'] <= 0) {
            return redirect()->to('/buku')->with('error', 'Stok buku habis!');
        }

        if (empty($tgl_kembali)) {
            return redirect()->back()->with('error', 'Silakan pilih tanggal pengembalian.');
        }

        $this->peminjamanModel->save([
            'id_buku'         => $id_buku,
            'id_anggota'      => session()->get('id_users'),
            'id_petugas'      => session()->get('id_users'), 
            'tanggal_pinjam'  => date('Y-m-d'),
            'tanggal_kembali' => $tgl_kembali, // Tanggal dinamis dari input user
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

    // Hitung denda jika terlambat
    $tgl_kembali_seharusnya = new \DateTime($pinjam['tanggal_kembali']);
    $tgl_sekarang = new \DateTime(date('Y-m-d'));
    $denda = 0;

    if ($tgl_sekarang > $tgl_kembali_seharusnya) {
        $selisih = $tgl_sekarang->diff($tgl_kembali_seharusnya);
        $denda = $selisih->days * 1000; // Denda 1000 per hari
    }

    // PERBAIKAN DI SINI:
    // Pastikan nama kolom di kiri (database) sesuai dengan yang ada di gambar DB kamu
    $this->peminjamanModel->update($id_peminjaman, [
        'tanggal_kembali' => date('Y-m-d'), // Ganti 'tanggal_dikembalikan' jadi 'tanggal_kembali'
        'status'          => 'kembali',
        'denda'           => $denda
    ]);

    // Kembalikan stok buku
    $buku = $this->bukuModel->find($pinjam['id_buku']);
    $this->bukuModel->update($pinjam['id_buku'], ['tersedia' => $buku['tersedia'] + 1]);

    return redirect()->back()->with('success', 'Buku berhasil dikembalikan. Denda: Rp ' . number_format($denda));
}
    public function peminjaman()
{
    $role = session()->get('role');
    $id_user = session()->get('id_users');

    $builder = $this->peminjamanModel
        ->select('peminjaman.*, buku.judul, users.nama as nama_peminjam') // Tambahkan nama peminjam
        ->join('buku', 'buku.id_buku = peminjaman.id_buku')
        ->join('users', 'users.id = peminjaman.id_anggota'); // Join ke tabel user

    // LOGIKA FILTER:
    // Jika bukan Admin dan bukan Petugas, maka hanya tampilkan miliknya sendiri
    if ($role !== 'admin' && $role !== 'petugas') {
        $builder->where('id_anggota', $id_user);
    }

    $data['pinjam'] = $builder->findAll();

    return view('buku/peminjaman', $data);
}

public function delete($id)
{
    // 1. Proteksi akses admin
    if (session()->get('role') != 'admin') {
        return redirect()->to('/')->with('error', 'Akses ditolak!');
    }

    $bukuModel = new \App\Models\BukuModel();
    
    // 2. Cari data buku untuk mendapatkan nama file cover
    $buku = $bukuModel->find($id);

    if ($buku) {
        // 3. Hapus file cover jika ada
        if (!empty($buku['cover']) && file_exists('uploads/buku/' . $buku['cover'])) {
            unlink('uploads/buku/' . $buku['cover']);
        }

        // 4. Hapus data dari database
        $bukuModel->delete($id);

        return redirect()->back()->with('success', 'Buku berhasil dihapus!');
    }

    return redirect()->back()->with('error', 'Buku tidak ditemukan!');
}
}