<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    /* Custom Background & Smooth Scroll */
    body { background-color: #f8fafc; }

    /* Hero Gradient Section */
    .hero-section {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        border-radius: 24px;
        position: relative;
        overflow: hidden;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    /* Modern Book Card */
    .book-item { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .cover-wrapper {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .cover-buku {
        width: 100%;
        aspect-ratio: 3/4;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .book-item:hover .cover-buku { transform: scale(1.1); }

    .book-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 15px;
    }

    .book-item:hover .book-overlay { opacity: 1; }

    /* Quick Action Cards */
    .action-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        transition: all 0.3s ease;
        text-decoration: none !important;
    }

    .action-card:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.1);
    }

    /* Glass Table Design */
    .custom-table-card {
        border-radius: 24px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .table thead th {
        background-color: #f1f5f9;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border: none;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
    }
</style>

<div class="container py-5">

    <div class="hero-section p-5 mb-5 shadow-lg text-white">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge bg-white bg-opacity-25 mb-3 px-3 py-2 rounded-pill">Selamat Datang di Jago Maca</span>
                <h1 class="display-5 fw-bold mb-2 text-white">Temukan Dunia Melalui <br><span class="text-warning">Koleksi Digital Terbaik</span></h1>
                <p class="lead opacity-75">Sistem Perpustakaan Pintar untuk pengelolaan yang lebih mudah dan cepat.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="d-flex flex-column gap-2">
                    <a href="<?= base_url('buku/peminjaman') ?>" class="btn btn-light btn-lg rounded-pill fw-bold shadow-sm">
                        <i class="fas fa-history me-2 text-primary"></i> Histori Pinjam
                    </a>
                    <?php if (strtolower(session()->get('role')) == 'admin'): ?>
                        <a href="<?= base_url('buku/create') ?>" class="btn btn-warning btn-lg rounded-pill fw-bold shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Koleksi
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white position-relative overflow-hidden">
                    <div class="position-relative" style="z-index: 2;">
                        <h6 class="text-uppercase fw-bold opacity-75 small mb-1">Total Buku</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $total_buku ?? '0'; ?></h2>
                        <p class="small mb-0 opacity-75 mt-2">Unit terdaftar</p>
                    </div>
                    <i class="fas fa-box position-absolute opacity-25" style="font-size: 5rem; right: -10px; bottom: -10px;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white position-relative overflow-hidden">
                    <div class="position-relative" style="z-index: 2;">
                        <h6 class="text-uppercase fw-bold opacity-75 small mb-1">Tersedia</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $tersedia ?? '0'; ?></h2>
                        <p class="small mb-0 opacity-75 mt-2">Siap dipinjam</p>
                    </div>
                    <i class="fas fa-check-circle position-absolute opacity-25" style="font-size: 5rem; right: -10px; bottom: -10px;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, #f9d423 0%, #ff4e50 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white position-relative overflow-hidden">
                    <div class="position-relative" style="z-index: 2;">
                        <h6 class="text-uppercase fw-bold opacity-75 small mb-1">Dipinjam</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $sedang_dipinjam ?? '0'; ?></h2>
                        <p class="small mb-0 opacity-75 mt-2">Menunggu kembali</p>
                    </div>
                    <i class="fas fa-clock position-absolute opacity-25" style="font-size: 5rem; right: -10px; bottom: -10px;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, #f85032 0%, #e73827 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white position-relative overflow-hidden">
                    <div class="position-relative" style="z-index: 2;">
                        <h6 class="text-uppercase fw-bold opacity-75 small mb-1">Terlambat</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $total_terlambat ?? '0'; ?></h2>
                        <p class="small mb-0 opacity-75 mt-2">Lewat jatuh tempo</p>
                    </div>
                    <i class="fas fa-exclamation-triangle position-absolute opacity-25" style="font-size: 5rem; right: -10px; bottom: -10px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 px-2">
        <h4 class="fw-bold m-0"><i class="fas fa-th-large me-2 text-primary"></i>Jelajahi Buku</h4>
        <a href="<?= base_url('buku') ?>" class="text-primary text-decoration-none fw-bold">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
    </div>

    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4 mb-5">
        <?php if (!empty($buku)): ?>
            <?php foreach ($buku as $b) : ?>
                <?php 
                    $id_key = $b['id_buku'] ?? $b['id'] ?? null; 
                    $is_available = ($b['stok'] ?? 1) > 0; 
                ?>
                <div class="col book-item">
                    <div class="cover-wrapper mb-3">
                        <?php if (!$is_available): ?>
                            <span class="badge bg-danger position-absolute top-0 end-0 m-3 shadow" style="z-index: 3;">Habis</span>
                        <?php endif; ?>
                        
                        <div class="book-overlay">
                             <a href="<?= base_url('buku/detail/' . $id_key) ?>" class="btn btn-light btn-sm rounded-pill fw-bold px-3">Detail</a>
                        </div>

                        <?php if (!empty($b['cover']) && file_exists('uploads/buku/' . $b['cover'])): ?>
                            <img src="<?= base_url('uploads/buku/' . $b['cover']) ?>" class="cover-buku">
                        <?php else: ?>
                            <div class="cover-buku d-flex flex-column align-items-center justify-content-center bg-secondary text-white p-2">
                                <i class="fas fa-book fa-3x mb-2"></i>
                                <span class="small fw-bold text-center"><?= esc($b['judul']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h6 class="text-dark fw-bold text-truncate mb-1"><?= esc($b['judul']) ?></h6>
                    <p class="text-muted small mb-3"><?= ($is_available) ? 'Tersedia' : 'Stok Kosong' ?></p>
                    
                    <?php if ($is_available) : ?>
                        <a href="<?= base_url('buku/konfirmasi_pinjam/' . $id_key) ?>" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold">Pinjam</a>
                    <?php else : ?>
                        <button class="btn btn-sm btn-light border rounded-pill w-100 disabled text-muted">Habis</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12">
            <h4 class="fw-bold mb-4 px-2"><i class="fas fa-bolt me-2 text-warning"></i>Akses Cepat</h4>
            <div class="row g-3">
                <?php if (strtolower(session()->get('role')) == 'admin') : ?>
                <div class="col-md-3">
                    <a href="<?= base_url('buku/create'); ?>" class="action-card p-4 d-block text-center h-100">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="fas fa-plus fs-3 text-primary"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Tambah Buku</h6>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?= base_url('users/create'); ?>" class="action-card p-4 d-block text-center h-100">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="fas fa-users-cog fs-3 text-success"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Kelola Anggota</h6>
                    </a>
                </div>
                <?php endif; ?>
                <div class="col-md-3">
                    <a href="<?= base_url('buku'); ?>" class="action-card p-4 d-block text-center h-100">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="fas fa-search fs-3 text-info"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Cari Katalog</h6>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="<?= base_url('users/edit/' . session()->get('id_users')); ?>" class="action-card p-4 d-block text-center h-100">
                        <div class="bg-secondary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="fas fa-user-circle fs-3 text-secondary"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Akun Saya</h6>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if (strtolower(session()->get('role')) == 'admin') : ?>
    <div class="row">
        <div class="col-12">
            <div class="card custom-table-card shadow-sm border-0">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0 text-dark">Sirkulasi Terbaru</h5>
                    <a href="<?= base_url('buku/peminjaman'); ?>" class="btn btn-primary btn-sm rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="table-responsive p-4 pt-0">
                    <table class="table align-middle border-0">
                        <thead>
                            <tr>
                                <th class="ps-0">Nama Peminjam</th>
                                <th>Judul Buku</th>
                                <th>Batas Kembali</th>
                                <th>Denda</th>
                                <th class="text-end pe-0">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($pinjam_terbaru)): ?>
                                <?php foreach($pinjam_terbaru as $p): ?>
                                <tr>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                            <span class="fw-bold text-dark"><?= $p['nama_peminjam'] ?? 'User'; ?></span>
                                        </div>
                                    </td>
                                    <td><span class="text-muted"><?= $p['judul'] ?? '-'; ?></span></td>
                                    <td><span class="fw-medium"><?= date('d M Y', strtotime($p['tanggal_kembali'])); ?></span></td>
                                    <td>
                                        <?php 
                                            $denda = 0;
                                            $tgl_kembali = strtotime($p['tanggal_kembali']);
                                            $tgl_skrg = strtotime(date('Y-m-d'));
                                            if ($p['status'] == 'dipinjam' && $tgl_skrg > $tgl_kembali) {
                                                $selisih = ($tgl_skrg - $tgl_kembali) / (60 * 60 * 24);
                                                $denda = $selisih * 1000;
                                            }
                                        ?>
                                        <span class="<?= $denda > 0 ? 'text-danger fw-bold' : 'text-muted'; ?>">
                                            Rp <?= number_format($denda, 0, ',', '.'); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-0">
                                        <span class="badge-status <?= $p['status'] == 'dipinjam' ? 'bg-warning text-warning' : 'bg-success text-success'; ?> bg-opacity-10">
                                            <?= ucfirst($p['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-4">Tidak ada data sirkulasi.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>