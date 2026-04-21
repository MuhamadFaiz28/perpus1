<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Hasil Pencarian: "<?= esc($keyword) ?>"</h3>
        <a href="<?= base_url('buku') ?>" class="btn btn-outline-secondary rounded-pill">Kembali</a>
    </div>

    <div class="row">
        <?php if (!empty($buku)) : ?>
            <?php foreach ($buku as $b) : ?>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <img src="<?= base_url('assets/img/hero_bg.png') ?>" class="card-img-top rounded-top-4" style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body">
                            <h6 class="fw-bold text-dark"><?= esc($b['judulbuku']) ?></h6>
                            <p class="small text-muted">Penulis: <?= esc($b['penulis']) ?></p>
                            
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('buku/detail/' . $b['id_buku']) ?>" class="btn btn-primary btn-sm w-100 rounded-pill">Detail</a>
                                
                                <a href="<?= base_url('buku/tambah_favorit/' . $b['id_buku']) ?>" class="btn btn-outline-danger btn-sm rounded-circle">
                                    <i class="bi bi-heart"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Buku tidak ditemukan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>