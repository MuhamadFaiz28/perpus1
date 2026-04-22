<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="row g-0">

                    <div class="col-md-4 bg-light text-center d-flex align-items-center justify-content-center" style="min-height: 300px; background: #f8f9fa;">
                        <?php if (!empty($buku['cover']) && file_exists('uploads/buku/' . $buku['cover'])): ?>
                            <img src="<?= base_url('uploads/buku/' . $buku['cover']) ?>" class="img-fluid w-100 h-100" style="object-fit: cover;">
                        <?php else: ?>
                            <div class="p-5">
                                <i class="bi bi-book text-primary" style="font-size: 8rem; opacity: 0.3;"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-8">
                        <div class="card-body p-4 p-lg-5">

                            <nav aria-label="breadcrumb" class="mb-3">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?= base_url('/') ?>" class="text-decoration-none">Home</a></li>
                                    <li class="breadcrumb-item active">Detail Buku</li>
                                </ol>
                            </nav>

                            <h1 class="fw-bold text-dark mb-2">
                                <?= esc($buku['judul'] ?? 'Tanpa Judul') ?>
                            </h1>

                            <p class="text-muted mb-4">
                                Penulis: 
                                <span class="fw-bold text-primary">
                                    <?= esc($buku['penulis'] ?? $buku['id_penulis'] ?? '-') ?>
                                </span>
                            </p>

                            <div class="row mb-4">
                                <div class="col-6 col-md-4">
                                    <small class="text-muted d-block">Kategori</small>
                                    <span class="fw-bold"><?= esc($buku['nama_kategori'] ?? 'Umum') ?></span>
                                </div>
                                <div class="col-6 col-md-4">
                                    <small class="text-muted d-block">Stok Tersedia</small>
                                    <span class="badge <?= ($buku['tersedia'] > 0) ? 'bg-success' : 'bg-danger' ?> rounded-pill">
                                        <?= esc($buku['tersedia'] ?? 0) ?> Buku
                                    </span>
                                </div>
                            </div>

                            <hr class="my-4 opacity-50">

                            <div class="d-flex gap-3 flex-wrap">

                                <?php if (($buku['tersedia'] ?? 0) > 0): ?>
                                    <a href="<?= base_url('buku/konfirmasi_pinjam/' . $buku['id_buku']) ?>" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow-sm">
                                        <i class="fas fa-hand-holding me-2"></i> Pinjam Sekarang
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-lg rounded-pill px-4 shadow-sm" disabled>
                                        <i class="fas fa-exclamation-circle me-2"></i> Stok Habis
                                    </button>

                                <?php endif; ?>

                                <a href="<?= base_url('/') ?>" 
                                   class="btn btn-light btn-lg rounded-pill px-4 border">
                                    Kembali
                                </a>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>