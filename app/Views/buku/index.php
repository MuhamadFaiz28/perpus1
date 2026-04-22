<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .book-wrapper { position: relative; }
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
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .cover-buku:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    /* Group tombol aksi agar rapi */
    .action-buttons {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .book-wrapper:hover .action-buttons { opacity: 1; }
    
    .judul-fallback {
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
        padding: 10px;
        text-align: center;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">📚 Koleksi Buku</h2>
        <div>
            <?php if (session()->get('role') == 'admin'): ?>
                <a href="<?= base_url('buku/create') ?>" class="btn btn-primary rounded-pill">
                    <i class="bi bi-plus-lg"></i> Tambah Buku
                </a>
            <?php endif; ?>
            <a href="<?= base_url('buku/peminjaman') ?>" class="btn btn-outline-primary rounded-pill">
                <i class="bi bi-journal-bookmark"></i> Pinjaman Saya
            </a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
        <?php if (!empty($buku)): ?>
            <?php foreach ($buku as $b) : ?>
                <div class="col">
                    <div class="book-wrapper">
                        
                        <?php if (session()->get('role') == 'admin'): ?>
                            <div class="action-buttons">
                                <a href="<?= base_url('buku/edit/' . $b['id_buku']) ?>" class="btn btn-light btn-sm rounded-circle shadow" title="Edit Buku">
                                    <i class="bi bi-pencil-square text-primary"></i>
                                </a>
                                <a href="<?= base_url('buku/delete/' . $b['id_buku']) ?>" 
                                   class="btn btn-danger btn-sm rounded-circle shadow" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')" 
                                   title="Hapus Buku">
                                    <i class="bi bi-trash text-white"></i>
                                </a>
                            </div>
                        <?php endif; ?>

                        <a href="<?= base_url('buku/detail/' . $b['id_buku']) ?>" class="text-decoration-none">
                            <div class="cover-buku">
                                <?php if (!empty($b['cover']) && file_exists('uploads/buku/' . $b['cover'])): ?>
                                    <img src="<?= base_url('uploads/buku/' . $b['cover']) ?>" class="w-100 h-100" style="object-fit: cover;">
                                <?php else: ?>
                                    <i class="bi bi-book fa-2x mb-2" style="font-size: 2rem;"></i>
                                    <div class="judul-fallback"><?= esc($b['judul']) ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mt-2">
                                <p class="text-dark fw-bold mb-0 text-truncate"><?= esc($b['judul']) ?></p>
                                <small class="text-muted">Tersedia: <?= $b['tersedia'] ?></small>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-emoji-frown display-1 text-muted"></i>
                <p class="text-muted mt-3">Data buku belum tersedia.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>