<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">💰 Manajemen Denda</h4>
            
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Peminjam</th>
                            <th>Buku</th>
                            <th>Total Denda</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($semua_denda as $d) : ?>
                        <tr>
                            <td><strong><?= esc($d['nama']) ?></strong></td>
                            <td><?= esc($d['judul']) ?></td>
                            <td class="text-danger fw-bold">Rp <?= number_format($d['jumlah_denda'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ($d['status'] == 'belum_bayar') : ?>
                                    <span class="badge bg-warning text-dark">Belum Bayar</span>
                                <?php else : ?>
                                    <span class="badge bg-success">Lunas</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($d['status'] == 'belum_bayar') : ?>
                                    <a href="<?= base_url('denda/bayar/' . $d['id_denda']) ?>" 
                                       class="btn btn-sm btn-success rounded-pill px-3"
                                       onclick="return confirm('Konfirmasi pelunasan denda?')">
                                        Bayar Lunas
                                    </a>
                                <?php else : ?>
                                    <button class="btn btn-sm btn-light rounded-pill px-3" disabled>Selesai</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>