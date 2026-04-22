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

$routes->get('users/edit/(:num)', 'Users::edit/$1');
$routes->post('users/update/(:num)', 'Users::update/$1');

$routes->get('users', 'Users::index');
$routes->get('users/detail/(:num)', 'Users::detail/$1');
$routes->get('users/edit/(:num)', 'Users::edit/$1');
$routes->post('users/update/(:num)', 'Users::update/$1');
$routes->get('users/delete/(:num)', 'Users::delete/$1');

$routes->get('buku/edit/(:num)', 'Buku::edit/$1'); 
$routes->post('buku/update/(:num)', 'Buku::update/$1'); // Untuk proses simpan perubahan

// Tambahkan baris ini
$routes->get('users/edit', 'Users::profile');

// Route update yang sudah ada (pastikan ini ada untuk memproses simpan)
$routes->post('users/update/(:num)', 'Users::update/$1');

$routes->get('users/edit', 'Users::profile'); // Mengarah ke fungsi profile
$routes->post('users/update/(:num)', 'Users::update/$1'); // Untuk proses simpan

// Route untuk menampilkan halaman pilih tanggal (GET)
$routes->get('buku/konfirmasi_pinjam/(:num)', 'Buku::konfirmasi_pinjam/$1');

// Route untuk memproses penyimpanan data pinjam (POST)
$routes->post('buku/proses_pinjam/(:num)', 'Buku::proses_pinjam/$1');

$routes->get('buku/delete/(:num)', 'Buku::delete/$1');
$routes->get('denda', 'Denda::index');
$routes->get('denda/bayar/(:num)', 'Denda::bayar/$1');