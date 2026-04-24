<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    body { background-color: #f8fafc; }
    .hero-section {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        border-radius: 24px;
        position: relative;
        overflow: hidden;
    }
    .book-item { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .cover-wrapper {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        background: #e2e8f0;
        aspect-ratio: 3/4;
    }
    .cover-buku {
        width: 100%;
        height: 100%;
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
    .custom-table-card { border-radius: 24px; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    .badge-status { padding: 6px 12px; border-radius: 8px; font-weight: 600; }

    /* Timeline Styling */
    .timeline-dashboard { position: relative; }
    .timeline-dashboard::before {
        content: "";
        position: absolute;
        left: 7px;
        top: 10px;
        bottom: 20px;
        width: 2px;
        background: #e9ecef;
        z-index: 1;
    }
</style>

<div class="container py-5">
    
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show mb-4 rounded-4 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="hero-section p-5 mb-5 shadow-lg text-white">
        <div class="row align-items-center">
            <div class="col-md-8 text-center text-md-start">
                <span class="badge bg-white bg-opacity-25 mb-3 px-3 py-2 rounded-pill">Selamat Datang di Paos28App</span>
                <h1 class="display-5 fw-bold mb-2">Kelola Literasi dengan <br><span class="text-warning">Presisi & Efisiensi</span></h1>
                <p class="lead opacity-75">Sistem Administrasi Perpustakaan Terpadu.</p>
            </div>
            <div class="col-md-4 text-md-end d-flex flex-column gap-2 mt-4 mt-md-0">
                <?php if (session()->get('role') == 'admin'): ?>
                    <a href="<?= base_url('buku/create') ?>" class="btn btn-warning btn-lg rounded-pill fw-bold shadow-sm">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Buku Baru
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('peminjaman') ?>" class="btn btn-light btn-lg rounded-pill fw-bold shadow-sm text-primary">
                    <i class="fas fa-exchange-alt me-2"></i> Kelola Sirkulasi
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white position-relative overflow-hidden">
                    <h6 class="text-uppercase fw-bold opacity-75 small">Total Stok</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $total_buku; ?></h2>
                    <i class="fas fa-book position-absolute opacity-25" style="font-size: 3.5rem; right: -5px; bottom: -5px;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white position-relative overflow-hidden">
                    <h6 class="text-uppercase fw-bold opacity-75 small">Tersedia</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $buku_tersedia; ?></h2>
                    <i class="fas fa-check-circle position-absolute opacity-25" style="font-size: 3.5rem; right: -5px; bottom: -5px;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, #f89b29 0%, #ffcc33 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white position-relative overflow-hidden">
                    <h6 class="text-uppercase fw-bold opacity-75 small">Dipinjam</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $sedang_dipinjam; ?></h2>
                    <i class="fas fa-clock position-absolute opacity-25" style="font-size: 3.5rem; right: -5px; bottom: -5px;"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%); border-radius: 20px;">
                <div class="card-body p-4 text-white position-relative overflow-hidden">
                    <h6 class="text-uppercase fw-bold opacity-75 small">Terlambat</h6>
                    <h2 class="display-6 fw-bold mb-0"><?= $total_terlambat; ?></h2>
                    <i class="fas fa-exclamation-triangle position-absolute opacity-25" style="font-size: 3.5rem; right: -5px; bottom: -5px;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <h4 class="fw-bold m-0"><i class="fas fa-th-large me-2 text-primary"></i>Katalog Koleksi</h4>
                <a href="<?= base_url('buku') ?>" class="text-primary text-decoration-none fw-bold">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
            </div>

            <div class="row row-cols-2 row-cols-md-3 g-4 mb-5">
                <?php if (!empty($buku)): ?>
                    <?php foreach ($buku as $b) : 
                        $id_key = $b['id_buku'] ?? $b['id'] ?? null; 
                        $stok = (int)($b['stok'] ?? 0);
                        $cover_path = 'uploads/buku/' . $b['cover'];
                    ?>
                        <div class="col book-item">
                            <div class="cover-wrapper mb-3">
                                <div class="position-absolute top-0 start-0 m-2 d-flex flex-column gap-1" style="z-index: 10;">
                                    <a href="<?= base_url('dashboard/tambah_stok/' . $id_key) ?>" 
                                       class="btn btn-success btn-sm rounded-circle shadow border-white border-2 p-0 d-flex align-items-center justify-content-center" 
                                       style="width: 28px; height: 28px;"
                                       onclick="return confirm('Tambah 1 stok buku ini?')">
                                         <i class="fas fa-plus fa-xs"></i>
                                    </a>
                                </div>

                                <?php if ($stok <= 0): ?>
                                    <span class="badge bg-danger position-absolute top-0 end-0 m-2 shadow" style="z-index: 3;">Kosong</span>
                                <?php endif; ?>
                                
                                <div class="book-overlay">
                                     <a href="<?= base_url('buku/detail/' . $id_key) ?>" class="btn btn-light btn-sm rounded-pill fw-bold px-3 shadow">Detail</a>
                                </div>

                                <?php if (!empty($b['cover']) && file_exists($cover_path)): ?>
                                    <img src="<?= base_url($cover_path) ?>" class="cover-buku" alt="<?= esc($b['judul']) ?>">
                                <?php else: ?>
                                    <div class="cover-buku d-flex flex-column align-items-center justify-content-center bg-light text-muted">
                                        <i class="fas fa-book-open fa-2x opacity-25"></i>
                                        <small class="mt-2 opacity-50" style="font-size: 0.6rem;">Tanpa Cover</small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <h6 class="text-dark fw-bold text-truncate mb-1"><?= esc($b['judul']) ?></h6>
                            <p class="text-muted small mb-0">Stok: <span class="fw-bold <?= $stok > 0 ? 'text-primary' : 'text-danger' ?>"><?= $stok ?></span></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0"><i class="fas fa-history me-2 text-primary"></i>Aktivitas Terbaru</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <?php if (!empty($aktivitas)) : ?>
                        <div class="timeline-dashboard">
                            <?php foreach ($aktivitas as $ak) : ?>
                                <div class="d-flex mb-4 position-relative">
                                    <div class="me-3">
                                        <?php 
                                            $color = ($ak['status'] == 'kembali') ? 'success' : (($ak['status'] == 'dipinjam') ? 'primary' : 'warning');
                                        ?>
                                        <div class="bg-<?= $color ?> rounded-circle shadow-sm" style="width: 15px; height: 15px; margin-top: 5px; z-index: 2; position: relative;"></div>
                                    </div>

                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold" style="font-size: 0.9rem;">
                                            <?= esc($ak['nama_peminjam']) ?> 
                                            <span class="text-muted fw-normal"><?= $ak['status'] == 'kembali' ? 'mengembalikan' : 'meminjam' ?></span>
                                            "<?= esc($ak['judul']) ?>"
                                        </h6>
                                        
                                        <?php if ($ak['status'] == 'kembali' && $ak['denda'] > 0) : ?>
                                            <div class="mb-1"><span class="badge bg-danger bg-opacity-10 text-danger" style="font-size: 0.7rem;">Denda: Rp <?= number_format($ak['denda'], 0, ',', '.') ?></span></div>
                                        <?php endif; ?>

                                        <?php if ($ak['status'] == 'dipinjam') : ?>
                                            <a href="<?= base_url('peminjaman/kembalikan/' . $ak['id_peminjaman']) ?>" 
                                               class="btn btn-xs btn-outline-warning py-0 px-2 rounded-pill fw-bold mb-2" style="font-size: 0.7rem;"
                                               onclick="return confirm('Konfirmasi pengembalian buku?')">
                                               Kembalikan Sekarang
                                            </a>
                                        <?php endif; ?>

                                        <div class="text-muted small"><i class="far fa-clock me-1"></i> <?= date('d M Y, H:i', strtotime($ak['tanggal_pinjam'] ?? date('Y-m-d H:i'))) ?> WIB</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="text-center py-4 text-muted">
                            <small>Belum ada aktivitas sirkulasi.</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>