<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    // Menampilkan halaman view/auth/login
    public function login()
    {
        return view('auth/login');
    }

    // Memproses data login
    public function prosesLogin()
    {
        $session = session();
        $usersModel = model('UsersModel');

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $users = $usersModel->getUsersByUsername($username);

        if ($users) {
            if (password_verify($password, $users['password'])) {
                
                // PERBAIKAN DI SINI:
                // Gunakan 'id_users' agar sinkron dengan pemanggilan di Controller Users.php
                // Pastikan '$users['id']' sesuai dengan nama kolom primary key di tabel database kamu
                $session->set([
                    'id_users'  => $users['id'], // Sebelumnya hanya 'id', ini yang bikin kepental
                    'nama'      => $users['nama'],
                    'username'  => $users['username'],
                    'role'      => $users['role'],
                    'foto'      => $users['foto'],
                    'logged_in' => true
                ]);

                return redirect()->to('/dashboard');

            } else {
                $session->setFlashdata('salahpw', 'Password salah');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Nama tidak ditemukan');
            return redirect()->to('/login');
        }
    }

    // Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}