<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function daftar()
    {
        return view('auth/daftar'); 
    }

    public function save_daftar()
    {
        $usersModel = new UsersModel();

        // Validasi sederhana agar username tidak bentrok
        $username = $this->request->getPost('username');
        $cek = $usersModel->where('username', $username)->first();
        
        if ($cek) {
            session()->setFlashdata('error', 'Username sudah digunakan!');
            return redirect()->back();
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
        return redirect()->to('/login');
    }

    public function prosesLogin()
    {
        $session = session();
        $usersModel = model('UsersModel');

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $users = $usersModel->where('username', $username)->first();

        if ($users) {
            if (password_verify($password, $users['password'])) {
                $session->set([
                    'id_users'  => $users['id'], 
                    'nama'      => $users['nama'],
                    'username'  => $users['username'],
                    'role'      => $users['role'],
                    'foto'      => $users['foto'],
                    'logged_in' => true
                ]);
                return redirect()->to('/dashboard');
            } else {
                session()->setFlashdata('error', 'Password salah');
                return redirect()->to('/login');
            }
        } else {
            session()->setFlashdata('error', 'Username tidak ditemukan');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}