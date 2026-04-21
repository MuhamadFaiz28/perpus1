<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="row g-0">

                    <!-- ICON -->
                    <div class="col-md-4 bg-light text-center p-4 d-flex align-items-center justify-content-center">
                        <i class="bi bi-file-earmark-pdf text-primary" style="font-size: 8rem; opacity: 0.5;"></i>
                    </div>

                    <!-- DETAIL -->
                    <div class="col-md-8">
                        <div class="card-body p-4 p-lg-5">

                            <nav>
                                <a href="<?= base_url('/') ?>">Home</a> / Detail Buku
                            </nav>

                            <h1 class="fw-bold text-dark">
                                <?= esc($buku['judul'] ?? 'Tanpa Judul') ?>
                            </h1>

                            <p class="text-muted mb-4">
                                Ditulis oleh 
                                <span class="fw-bold text-primary">
                                    <?= esc($buku['id_penulis'] ?? '-') ?>
                                </span>
                            </p>

                            <div class="mb-4">
                                <h6 class="fw-bold">Tentang Buku:</h6>
                                <p class="text-secondary small">
                                    Buku ini tersedia dalam format PDF dan siap diunduh untuk belajar mandiri.
                                </p>
                            </div>

                            <hr class="my-4 opacity-50">

                            <div class="d-flex gap-2 flex-wrap">

                                <!-- DOWNLOAD -->
                                <?php if (!empty($buku['file'])): ?>
                                    <a href="<?= base_url('uploads/ebooks/' . $buku['file']) ?>" 
                                       class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm" download>
                                        <i class="bi bi-download me-2"></i> Download
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-lg rounded-pill px-4" disabled>
                                        File tidak tersedia
                                    </button>
                                <?php endif; ?>

                                <!-- PINJAM -->
                                <a href="<?= base_url('buku/pinjam/' . $buku['id_buku']) ?>" 
                                   class="btn btn-success btn-lg rounded-pill px-4">
                                    📚 Pinjam
                                </a>

                                <!-- KEMBALI -->
                                <a href="<?= base_url('/') ?>" 
                                   class="btn btn-outline-secondary btn-lg rounded-pill px-4">
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