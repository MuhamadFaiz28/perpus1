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

    /* Modern Book Card (Jelajahi Buku) */
    .book-item { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .cover-wrapper {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        background: #e2e8f0;
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
        z-index: 2;
    }

    .book-item:hover .book-overlay { opacity: 1; }

    /* Glass Table & Cards */
    .custom-table-card {
        border-radius: 24px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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
                    <a href="<?= base_url('peminjaman/saya') ?>" class="btn btn-light btn-lg rounded-pill fw-bold shadow-sm">
                        <i class="fas fa-history me-2 text-primary"></i> Pinjaman Saya
                    </a>
                    <?php if (in_array(session()->get('role'), ['admin', 'petugas'])): ?>
                        <a href="<?= base_url('buku/create') ?>" class="btn btn-warning btn-lg rounded-pill fw-bold shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Buku
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
                    <h6 class="text-uppercase fw-bold opacity-75 small mb-1">Total Buku</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $total_buku ?? 0; ?></h2>
                    <i class="fas fa-book position-absolute opacity-25" style="font-size: 5rem; right: -10px; bottom: -10px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white position-relative overflow-hidden">
                    <h6 class="text-uppercase fw-bold opacity-75 small mb-1">Tersedia</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $buku_tersedia ?? 0; ?></h2>
                    <i class="fas fa-check-circle position-absolute opacity-25" style="font-size: 5rem; right: -10px; bottom: -10px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, #f89b29 0%, #ffcc33 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white position-relative overflow-hidden">
                    <h6 class="text-uppercase fw-bold opacity-75 small mb-1">Dipinjam</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $sedang_dipinjam ?? 0; ?></h2>
                    <i class="fas fa-clock position-absolute opacity-25" style="font-size: 5rem; right: -10px; bottom: -10px;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white position-relative overflow-hidden">
                    <h6 class="text-uppercase fw-bold opacity-75 small mb-1">Terlambat</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $total_terlambat ?? 0; ?></h2>
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
        $stok = (int)($b['stok'] ?? 0);
    ?>
    <div class="col book-item">
        <div class="cover-wrapper mb-3" style="position: relative;">
            
            <?php if (in_array(session()->get('role'), ['admin', 'petugas'])): ?>
                <div class="position-absolute top-0 start-0 m-2" style="z-index: 10;">
                    <a href="<?= base_url('buku/tambah_stok/' . $id_key) ?>" 
                       class="btn btn-success btn-sm rounded-circle shadow" 
                       title="Tambah 1 Stok">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($stok <= 0): ?>
                <span class="badge bg-danger position-absolute top-0 end-0 m-2 shadow" style="z-index: 3;">Habis</span>
            <?php endif; ?>
            
            <div class="book-overlay">
                 <a href="<?= base_url('buku/detail/' . $id_key) ?>" class="btn btn-light btn-sm rounded-pill fw-bold px-3">Detail</a>
            </div>

            <?php if (!empty($b['cover']) && file_exists('uploads/buku/' . $b['cover'])): ?>
                <img src="<?= base_url('uploads/buku/' . $b['cover']) ?>" class="cover-buku">
            <?php else: ?>
                <div class="cover-buku d-flex align-items-center justify-content-center bg-light text-muted">
                    <i class="fas fa-book fa-2x opacity-25"></i>
                </div>
            <?php endif; ?>
        </div>
        
        <h6 class="text-dark fw-bold text-truncate mb-1" title="<?= esc($b['judul']) ?>"><?= esc($b['judul']) ?></h6>
        <p class="text-muted small mb-2">
            Tersedia: <span class="badge <?= $stok > 0 ? 'bg-info' : 'bg-secondary' ?>"><?= $stok ?></span>
        </p>
        
        <?php if ($stok > 0) : ?>
            <a href="<?= base_url('peminjaman/tambah/' . $id_key) ?>" class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold">Pinjam</a>
        <?php else : ?>
            <button class="btn btn-sm btn-light border rounded-pill w-100 disabled text-muted">Kosong</button>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada koleksi buku.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php if (in_array(session()->get('role'), ['admin', 'petugas'])) : ?>
    <div class="row">
        <div class="col-12">
            <div class="card custom-table-card shadow-sm border-0">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0 text-dark">Sirkulasi Terbaru</h5>
                    <a href="<?= base_url('peminjaman'); ?>" class="btn btn-primary btn-sm rounded-pill px-3">Lihat Semua</a>
                </div>
                <div class="table-responsive p-4 pt-0">
                    <table class="table align-middle border-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Peminjam</th>
                                <th>Buku</th>
                                <th>Batas Kembali</th>
                                <th>Denda</th>
                                <th class="text-end pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($pinjam_terbaru)): ?>
                                <?php foreach($pinjam_terbaru as $p): ?>
                                <tr>
                                    <td class="ps-3">
                                        <span class="fw-bold text-dark"><?= esc($p['nama_peminjam'] ?? 'User'); ?></span>
                                    </td>
                                    <td><span class="text-muted"><?= esc($p['judul'] ?? '-'); ?></span></td>
                                    <td><span class="fw-medium"><?= date('d/m/Y', strtotime($p['tanggal_kembali'])); ?></span></td>
                                    <td>
                                        <?php 
                                            $tgl_kembali = strtotime($p['tanggal_kembali']);
                                            $tgl_skrg = strtotime(date('Y-m-d'));
                                            $denda_akhir = 0;
                                            if ($p['status'] == 'dipinjam' && $tgl_skrg > $tgl_kembali) {
                                                $hari = ($tgl_skrg - $tgl_kembali) / (60 * 60 * 24);
                                                $denda_akhir = $hari * 1000;
                                            }
                                        ?>
                                        <span class="<?= $denda_akhir > 0 ? 'text-danger fw-bold' : 'text-muted'; ?>">
                                            Rp <?= number_format($denda_akhir, 0, ',', '.'); ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <span class="badge-status <?= $p['status'] == 'dipinjam' ? 'bg-warning text-warning' : 'bg-success text-success'; ?> bg-opacity-10">
                                            <?= ucfirst($p['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada data sirkulasi.</td></tr>
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