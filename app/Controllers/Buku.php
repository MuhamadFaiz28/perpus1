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
        $data = [
            'kategori' => (new KategoriModel())->findAll(),
            'penulis'  => (new PenulisModel())->findAll(),
            'penerbit' => (new PenerbitModel())->findAll(),
            'rak'      => (new RakModel())->findAll(),
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

    // 7. Hapus Buku
    public function delete($id)
    {
        // Proteksi: Hanya Admin yang bisa hapus
        if (session()->get('role') != 'admin') {
            return redirect()->to('/buku')->with('error', 'Akses ditolak!');
        }

        $buku = $this->bukuModel->find($id);

        if ($buku) {
            // Hapus file cover jika ada dan bukan default
            if (!empty($buku['cover']) && $buku['cover'] != 'default.jpg') {
                $path = 'uploads/buku/' . $buku['cover'];
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            $this->bukuModel->delete($id);
            return redirect()->to('/buku')->with('success', 'Buku berhasil dihapus!');
        }

        return redirect()->to('/buku')->with('error', 'Buku tidak ditemukan!');
    }

    // --- LOGIKA PEMINJAMAN ---

    public function konfirmasi_pinjam($id)
    {
        $data['buku'] = $this->bukuModel->find($id);
        if (!$data['buku']) return redirect()->back()->with('error', 'Buku tidak ditemukan');
        
        return view('buku/konfirmasi_pinjam', $data);
    }

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
            'tanggal_kembali' => $tgl_kembali,
            'status'          => 'dipinjam',
            'denda'           => 0
        ]);

        $this->bukuModel->update($id_buku, ['tersedia' => $buku['tersedia'] - 1]);

        return redirect()->to('/buku/peminjaman')->with('success', 'Buku berhasil dipinjam!');
    }

    // Contoh potongan kode di Controller
public function kembalikan($id_peminjaman)
{
    $this->db->table('tb_peminjaman')
        ->where('id_peminjaman', $id_peminjaman)
        ->update([
            'status'      => 'Kembali',
            'tgl_kembali' => date('Y-m-d') // Mencatat kapan buku dipulangkan
        ]);

    session()->setFlashdata('success', 'Buku berhasil dikembalikan dan masuk ke riwayat.');
    return redirect()->to(base_url('buku/peminjaman')); // Arahkan ke halaman riwayat
}

    public function peminjaman()
{
    $id_user = session()->get('id_user');
    // Pastikan tidak memfilter 'status' => 'Kembali' saja
    $data['pinjaman'] = $this->db->table('tb_peminjaman')
        ->join('tb_buku', 'tb_peminjaman.id_buku = tb_buku.id_buku')
        ->where('tb_peminjaman.id_user', $id_user)
        ->orderBy('id_peminjaman', 'DESC')
        ->get()->getResultArray();

    return view('buku/peminjaman', $data);
}
public function riwayat()
{
    $id_user = session()->get('id_user');
    
    // Ambil data tanpa memfilter status 'Dipinjam' saja
    $data['pinjaman'] = $this->db->table('tb_peminjaman')
        ->join('tb_buku', 'tb_peminjaman.id_buku = tb_buku.id_buku')
        ->where('tb_peminjaman.id_user', $id_user) 
        ->orderBy('tb_peminjaman.id_peminjaman', 'DESC')
        ->get()->getResultArray();

    return view('buku/peminjaman', $data);
}
}