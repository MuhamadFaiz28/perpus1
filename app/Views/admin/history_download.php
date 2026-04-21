<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-dark py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 text-white fw-bold">
                <i class="bi bi-person-badge"></i> Panel Admin: Histori Download
            </h5>
            <a href="<?= base_url('buku') ?>" class="btn btn-light btn-sm rounded-pill">Kembali</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Judul Buku</th>
                            <th>ID User</th>
                            <th>Waktu Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($histori)) : ?>
                            <?php $no = 1; foreach ($histori as $h) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-bold text-primary"><?= esc($h['judulbuku']); ?></td>
                                <td><span class="badge bg-info text-dark">User #<?= $h['id_user']; ?></span></td>
                                <td class="small text-muted"><?= $h['waktu_download']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada aktivitas download.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>