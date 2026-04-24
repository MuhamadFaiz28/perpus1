<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="fw-bold"><i class="bi bi-cash-stack text-success me-2"></i> Riwayat Transaksi Perpustakaan</h4>
            <p class="text-muted small">Pantau semua aktivitas peminjaman dan denda yang masuk.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="" method="get" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-filter"></i> Filter</button>
                    <a href="<?= base_url('peminjaman/laporan') ?>" class="btn btn-light border px-4">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($transaksi) : $no = 1; foreach ($transaksi as $t) : ?>
                    <tr>
                        <td class="ps-4"><?= $no++ ?></td>
                        <td class="fw-bold"><?= $t['nama'] ?></td>
                        <td><?= $t['judul'] ?></td>
                        <td><?= date('d/m/Y', strtotime($t['tanggal_pinjam'])) ?></td>
                        <td><?= $t['tanggal_dikembalikan'] ? date('d/m/Y', strtotime($t['tanggal_dikembalikan'])) : '<span class="text-muted small">Belum Kembali</span>' ?></td>
                        <td class="text-danger fw-bold">
                            <?= $t['denda'] > 0 ? 'Rp ' . number_format($t['denda'], 0, ',', '.') : '-' ?>
                        </td>
                        <td class="text-center">
                            <span class="badge rounded-pill <?= $t['status'] == 'kembali' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= ucfirst($t['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; else : ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Belum ada data transaksi pada periode ini.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>