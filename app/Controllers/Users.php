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
        // Fitur pencarian sederhana agar Index lebih fungsional
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
        $namaFoto = 'default.png'; // Default foto jika tidak upload

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

        return redirect()->to('/login')->with('success', 'User berhasil ditambahkan!');
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

    public function print()
    {
        $keyword = $this->request->getGet('keyword');
        $role    = $this->request->getGet('role');
        $builder = $this->users;

        if ($keyword) $builder->like('nama', $keyword);
        if ($role) $builder->where('role', $role);

        $data['users'] = $builder->findAll();
        return view('users/print', $data);
    }

    public function profile()
    {
        $id = session()->get('id_users'); 
        if (!$id) return redirect()->to('/login')->with('error', 'Silakan login ulang.');

        $user = $this->users->find($id);
        return view('users/edit', ['user' => $user]);
    }
    // Pastikan method ini ada di dalam class Users
public function detail($id)
{
    $user = $this->users->find($id);

    if (!$user) {
        return redirect()->to('/users')->with('error', 'User tidak ditemukan');
    }

    return view('users/detail', ['user' => $user]);
}
public function wa($id)
{
    $user = $this->users->find($id);

    if (!$user) {
        return redirect()->to('/users')->with('error', 'User tidak ditemukan');
    }

    // Mengambil nomor HP, jika kosong gunakan nomor default
    // Pastikan di database ada kolom 'no_hp'
    $nomor = $user['no_hp'] ?? '628123456789'; 
    $pesan = "Halo " . $user['nama'] . ", ini dari sistem perpustakaan Paos28App.";

    return redirect()->to("https://wa.me/$nomor?text=" . urlencode($pesan));
}
}