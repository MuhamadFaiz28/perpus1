<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    /* Styling Card Buku agar rapi */
    .book-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease;
        background: #fff;
        height: 100%; /* Menyamakan tinggi card */
        display: flex;
        flex-direction: column;
    }
    .book-card:hover {
        transform: translateY(-5px);
    }
    .book-wrapper { position: relative; border-radius: 15px; overflow: hidden; }
    
    .cover-buku {
        width: 100%;
        aspect-ratio: 3/4;
        object-fit: cover;
        background: linear-gradient(135deg, #2680eb, #1a5bb8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    /* Tombol Aksi (Edit/Hapus) - Muncul saat Hover */
    .action-overlay {
        position: absolute;
        top: 8px;
        right: 8px;
        display: flex;
        flex-direction: column;
        gap: 5px;
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 5;
    }
    .book-wrapper:hover .action-overlay { opacity: 1; }

    /* Search Container agar tidak tumpang tindih */
    .filter-section {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #eee;
        margin-bottom: 30px;
    }
</style>

<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">📚 Koleksi Buku</h2>
        <div class="d-flex gap-2">
            <?php if (session()->get('role') == 'admin'): ?>
                <a href="<?= base_url('buku/create') ?>" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-plus-lg"></i> Tambah Buku
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
        <?php if (!empty($buku)): foreach ($buku as $b) : ?>
            <div class="col">
                <div class="book-card shadow-sm">
                    <div class="book-wrapper">
                        <?php if (session()->get('role') == 'admin'): ?>
                            <div class="action-overlay">
                                <a href="<?= base_url('buku/edit/' . $b['id_buku']) ?>" class="btn btn-white btn-sm rounded-circle shadow-sm bg-white">
                                    <i class="bi bi-pencil-square text-primary"></i>
                                </a>
                                <a href="<?= base_url('buku/delete/' . $b['id_buku']) ?>" class="btn btn-white btn-sm rounded-circle shadow-sm bg-white" onclick="return confirm('Hapus buku?')">
                                    <i class="bi bi-trash text-danger"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                        <a href="<?= base_url('buku/detail/' . $b['id_buku']) ?>">
                            <div class="cover-buku">
                                <?php if (!empty($b['cover']) && file_exists('uploads/buku/' . $b['cover'])): ?>
                                    <img src="<?= base_url('uploads/buku/' . $b['cover']) ?>" class="w-100 h-100" style="object-fit: cover;">
                                <?php else: ?>
                                    <div class="text-center px-2">
                                        <i class="bi bi-book fs-1"></i><br>
                                        <small><?= esc($b['judul']) ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 flex-grow-1 d-flex flex-column justify-content-between">
                        <div>
                            <p class="fw-bold mb-1 text-truncate" title="<?= esc($b['judul']) ?>"><?= esc($b['judul']) ?></p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">Stok: <?= $b['tersedia'] ?></small>
                                <span class="badge rounded-pill <?= $b['tersedia'] > 0 ? 'bg-success' : 'bg-danger' ?>" style="font-size: 0.7rem;">
                                    <?= $b['tersedia'] > 0 ? 'Tersedia' : 'Habis' ?>
                                </span>
                            </div>
                        </div>

                        <?php if (session()->get('role') == 'anggota'): ?>
                            <div class="d-grid gap-1 mt-2">
                                <?php if ($b['tersedia'] > 0): ?>
                                <?php else: ?>
                                    <button class="btn btn-light btn-sm rounded-pill fw-bold text-muted" disabled>
                                        <i class="bi bi-x-circle me-1"></i> Stok Habis
                                    </button>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Buku tidak ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>