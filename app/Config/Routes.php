<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'Home::index');

// FILTER
$authFilter = ['filter' => 'auth'];
$admin      = ['filter' => 'role:admin'];
$allRole    = ['filter' => 'role:admin,user'];

// LOGIN
$routes->get('/login', 'Auth::login');
$routes->post('/proses-login', 'Auth::prosesLogin');
$routes->get('/logout', 'Auth::logout');

// DASHBOARD
$routes->get('/dashboard', 'Home::index', $authFilter);

// ================= USERS =================
$routes->get('/users', 'Users::index', $allRole);
$routes->get('/users/create', 'Users::create', $admin);
$routes->post('/users/store', 'Users::store', $admin);

$routes->get('/users/detail/(:num)', 'Users::detail/$1', $allRole);
$routes->get('/users/edit/(:num)', 'Users::edit/$1', $allRole);
$routes->post('/users/update/(:num)', 'Users::update/$1', $allRole);
$routes->get('/users/delete/(:num)', 'Users::delete/$1', $admin);

$routes->get('/users/print', 'Users::print', $allRole);
$routes->get('/users/wa/(:num)', 'Users::wa/$1', $allRole);

// ================= PENGATURAN =================
$routes->get('/pengaturan', 'Pengaturan::index', $allRole);

// ================= BUKU =================
$routes->get('buku', 'Buku::index');
$routes->get('buku/create', 'Buku::create');
$routes->post('buku/store', 'Buku::store');

$routes->get('buku/detail/(:num)', 'Buku::detail/$1');
$routes->get('buku/(:num)', 'Buku::detail/$1');

$routes->get('buku/cari', 'Buku::cari');

$routes->get('buku/unduh/(:num)', 'Buku::unduh/$1');
$routes->get('buku/baca/(:num)', 'Buku::baca/$1');

$routes->get('buku/pinjam/(:num)', 'Buku::pinjam/$1');
$routes->get('buku/kembalikan/(:num)', 'Buku::kembalikan/$1');

$routes->get('buku/peminjaman', 'Buku::peminjaman');

$routes->get('buku/histori_download', 'Buku::histori_download');
$routes->get('admin/histori', 'Buku::histori_download');