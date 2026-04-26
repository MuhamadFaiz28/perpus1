<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <h2 class="fw-bold mb-4">Riwayat Peminjaman Saya</h2>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Buku</th>
                            <th>Tanggal Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($pinjaman)) : ?>
                            <?php foreach ($pinjaman as $p) : ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= base_url('uploads/buku/' . $p['cover']) ?>" 
                                                 class="rounded me-3" style="width: 45px; height: 60px; object-fit: cover;">
                                            <span class="fw-bold"><?= esc($p['judul']) ?></span>
                                        </div>
                                    </td>
                                    <td><?= date('d M Y', strtotime($p['tanggal_pinjam'])) ?></td>
                                    <td><?= date('d M Y', strtotime($p['tanggal_kembali'])) ?></td>
                                    <td>
                                        <span class="badge bg-success rounded-pill">Kembali</span>
                                    </td>
                                    <td class="pe-4 text-end text-muted small">
                                        Selesai
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    Tidak ada riwayat peminjaman yang sudah selesai.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if (!empty($pinjaman)) : ?>
        <div class="text-end">
            <a href="<?= base_url('peminjaman/hapusSemua') ?>" 
               class="btn btn-danger rounded-pill px-4 shadow-sm"
               onclick="return confirm('Hapus semua riwayat yang sudah selesai?')">
                <i class="bi bi-trash-fill"></i> Bersihkan Semua Riwayat
            </a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>