<?php

namespace App\Controllers;

use App\Models\UsersModel;

class Users extends BaseController
{
    protected $users;

    public function __construct()
    {
        $this->users = new UsersModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword');
        if ($keyword) {
            $this->users->like('nama', $keyword)->orLike('username', $keyword);
        }

        $data['users'] = $this->users->paginate(10, 'users');
        $data['pager'] = $this->users->pager;
        $data['keyword'] = $keyword;

        return view('users/index', $data);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama'     => 'required',
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[4]',
            'role'     => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $validation->getErrors()));
        }

        $foto = $this->request->getFile('foto');
        $namaFoto = 'default.png';

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/users', $namaFoto);
        }

        $this->users->save([
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
            'foto'     => $namaFoto
        ]);

        return redirect()->to('/users')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data['user'] = $this->users->find($id);
        if (!$data['user']) {
            return redirect()->to('/users')->with('error', 'User tidak ditemukan');
        }
        return view('users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->users->find($id);
        if (!$user) return redirect()->to('/users')->with('error', 'Data tidak ditemukan');

        $fotoBaru = $this->request->getFile('foto');
        $namaFoto = $user['foto'];

        if ($fotoBaru && $fotoBaru->isValid() && !$fotoBaru->hasMoved()) {
            if (!empty($user['foto']) && $user['foto'] != 'default.png' && file_exists(FCPATH . 'uploads/users/' . $user['foto'])) {
                unlink(FCPATH . 'uploads/users/' . $user['foto']);
            }
            $namaFoto = $fotoBaru->getRandomName();
            $fotoBaru->move(FCPATH . 'uploads/users', $namaFoto);
        }

        $data = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'role'     => $this->request->getPost('role'),
            'foto'     => $namaFoto
        ];

        if ($this->request->getPost('password') != "") {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->users->update($id, $data);
        return redirect()->to('/users')->with('success', 'Data user berhasil diupdate!');
    }

    public function delete($id)
    {
        $user = $this->users->find($id);
        if ($user && $user['foto'] && $user['foto'] != 'default.png' && file_exists(FCPATH . 'uploads/users/' . $user['foto'])) {
            unlink(FCPATH . 'uploads/users/' . $user['foto']);
        }

        $this->users->delete($id);
        return redirect()->to('/users')->with('success', 'User berhasil dihapus!');
    }

    public function detail($id)
    {
        $user = $this->users->find($id);
        if (!$user) return redirect()->to('/users')->with('error', 'User tidak ditemukan');

        return view('users/detail', ['user' => $user]);
    }

    public function profile()
    {
        $id = session()->get('id') ?? session()->get('id_users'); 

        if (!$id) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = $this->users->find($id);

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan.');
        }

        $db = \Config\Database::connect();
        $aktivitas = $db->table('peminjaman')
            ->select('peminjaman.*, buku.judul')
            ->join('buku', 'buku.id_buku = peminjaman.id_buku')
            ->where('id_anggota', $id)
            ->orderBy('id_peminjaman', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $data = [
            'title'     => 'Profil Saya',
            'user'      => $user,
            'aktivitas' => $aktivitas
        ];

        return view('users/profile', $data);
    }

    public function wa($id)
    {
        $user = $this->users->find($id);
        if (!$user) return redirect()->to('/users')->with('error', 'User tidak ditemukan');

        $nomor = $user['no_hp'] ?? '628123456789'; 
        $pesan = "Halo " . $user['nama'] . ", ini dari sistem perpustakaan Paos28App.";

        return redirect()->to("https://wa.me/$nomor?text=" . urlencode($pesan));
    }

    public function print()
    {
        $keyword = $this->request->getGet('keyword');
        $role    = $this->request->getGet('role');

        if ($keyword) {
            $this->users->like('nama', $keyword);
        }
        
        if ($role) {
            $this->users->where('role', $role);
        }

        $data = [
            'title' => 'Laporan Data Pengguna',
            'users' => $this->users->findAll()
        ];

        return view('users/print', $data);
    }

    // ... (kode method lainnya tetap sama)

    public function tambah()
    {
        // Pastikan folder di Views bernama 'users' (jamak)
        return view('users/tambah');
    }

    public function simpan()
    {
        // Gunakan $this->users karena sudah didefinisikan di __construct
        $data = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
            'foto'     => 'default.png', 
            'status'   => 'Aktif'
        ];

        $this->users->insert($data);
        return redirect()->to('/users')->with('success', 'User baru berhasil ditambahkan!');
    }
    public function dataDenda()
{
    $db = \Config\Database::connect();
    
    // Query untuk mengambil data denda dari tabel peminjaman
    $denda = $db->table('peminjaman')
        ->select('peminjaman.*, users.nama as nama_peminjam, buku.judul as judul_buku')
        ->join('users', 'users.id_users = peminjaman.id_anggota')
        ->join('buku', 'buku.id_buku = peminjaman.id_buku')
        ->where('peminjaman.denda >', 0)
        ->orderBy('peminjaman.id_peminjaman', 'DESC')
        ->get()
        ->getResultArray();

    // Hitung total denda
    $total = $db->table('peminjaman')->selectSum('denda')->get()->getRow()->denda ?? 0;

    $data = [
        'title'            => 'Laporan Denda - Paos28App',
        'denda'            => $denda,
        'total_pendapatan' => $total
    ];

    return view('users/denda_anggota', $data);
}

}