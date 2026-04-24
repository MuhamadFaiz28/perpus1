<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Data Denda Anggota</h3>
            <p class="text-muted small">Total pendapatan denda: <span class="fw-bold text-success">Rp <?= number_format($total_denda ?? 0, 0, ',', '.') ?></span></p>
        </div>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-light rounded-pill px-4">Kembali</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 border-0">Peminjam</th>
                        <th class="border-0">Buku</th>
                        <th class="border-0">Terlambat</th>
                        <th class="border-0 text-end pe-4">Besar Denda</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($denda_list)) : ?>
                        <?php foreach ($denda_list as $d) : 
                            $tgl_kembali = strtotime($d['tanggal_kembali']);
                            $tgl_asli = strtotime($d['tanggal_pengembalian'] ?? date('Y-m-d'));
                            $selisih = floor(($tgl_asli - $tgl_kembali) / (60 * 60 * 24));
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark"><?= esc($d['nama_peminjam']) ?></span>
                                </td>
                                <td><?= esc($d['judul']) ?></td>
                                <td><span class="badge bg-danger bg-opacity-10 text-danger"><?= $selisih ?> Hari</span></td>
                                <td class="text-end pe-4">
                                    <span class="fw-bold text-primary">Rp <?= number_format($d['denda'], 0, ',', '.') ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Belum ada data denda tersimpan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>