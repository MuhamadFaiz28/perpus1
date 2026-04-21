<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    .cover-buku {
        width: 100%;
        aspect-ratio: 3/4;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #2680eb, #1a5bb8);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
        text-decoration: none;
    }

    .cover-buku:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        filter: brightness(1.1);
    }

    .judul-fallback {
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
        padding: 10px;
        text-align: center;
    }
</style>

<div class="container py-5">

    <div class="row mb-5 align-items-center bg-white p-4 rounded-4 shadow-sm border">
        <div class="col-md-7">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-primary text-white rounded-3 p-2 me-3 shadow-sm">
                    <i class="fas fa-book-open fa-lg"></i>
                </div>
                <h2 class="fw-bold text-dark m-0">Koleksi Buku Digital</h2>
            </div>
            <p class="text-muted mb-0 ps-1">Pilih dan baca koleksi terbaik di <strong>Jago Maca</strong>.</p>
        </div>

        <div class="col-md-5 mt-4 mt-md-0 text-md-end">
            <div class="d-flex justify-content-md-end gap-2 flex-wrap">
                <a href="<?= base_url('buku/histori_download') ?>" class="btn btn-outline-dark rounded-pill px-4 fw-medium">
                    <i class="fas fa-history me-1"></i> Histori
                </a>
                <?php if (session()->get('role') == 'admin'): ?>
                    <a href="<?= base_url('buku/create') ?>" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fas fa-plus me-1"></i> Tambah
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
        <?php if (isset($buku) && !empty($buku)): ?>
            <?php foreach ($buku as $b) : ?>
                <?php 
                    // Mengatasi error 'id' atau 'id_buku'
                    $id_key = $b['id_buku'] ?? $b['id'] ?? null; 
                ?>
                <div class="col">
                    <a href="<?= base_url('buku/detail/' . $id_key) ?>" class="text-decoration-none">
                        <div class="cover-buku">
                            <?php if (!empty($b['cover']) && file_exists('uploads/' . $b['cover'])): ?>
                                <img src="<?= base_url('uploads/' . $b['cover']) ?>" class="w-100 h-100" style="object-fit: cover;">
                            <?php else: ?>
                                <i class="fas fa-book fa-2x mb-2"></i>
                                <div class="judul-fallback"><?= esc($b['judul']) ?></div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted"></p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?> 