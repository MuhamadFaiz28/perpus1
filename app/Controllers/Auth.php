<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    // 1. Menampilkan Halaman Login
    public function login()
    {
        return view('auth/login');
    }

    // 2. Menampilkan Halaman Daftar
   public function daftar()
    {
        // Ini akan mencari file: app/Views/auth/daftar.php
        return view('auth/daftar'); 
    }

    // 3. Proses Simpan Pendaftaran
    public function save_register()
    {
        $usersModel = new UsersModel();

        $username = $this->request->getPost('username');
        
        // Cek apakah username sudah ada
        $cek = $usersModel->where('username', $username)->first();
        if ($cek) {
            session()->setFlashdata('error', 'Username sudah digunakan!');
            return redirect()->back()->withInput();
        }

        $data = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $username,
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'anggota',
            'foto'     => 'default.png'
        ];

        $usersModel->save($data);
        
        session()->setFlashdata('success', 'Pendaftaran berhasil! Silakan login.');
        return redirect()->to(base_url('login'));
    }

    // 4. Proses Verifikasi Login
    public function prosesLogin()
    {
        $session = session();
        $usersModel = new UsersModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $usersModel->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $session->set([
                    'id_users'  => $user['id'], 
                    'nama'      => $user['nama'],
                    'username'  => $user['username'],
                    'role'      => $user['role'],
                    'foto'      => $user['foto'],
                    'logged_in' => true
                ]);
                return redirect()->to('/dashboard');
            } else {
                session()->setFlashdata('error', 'Password salah');
                return redirect()->back()->withInput();
            }
        } else {
            session()->setFlashdata('error', 'Username tidak ditemukan');
            return redirect()->back()->withInput();
        }
    }

    // 5. Proses Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}