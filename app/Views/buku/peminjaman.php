<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📚 Data Peminjaman</h3>
        <a href="<?= base_url('buku') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali ke Katalog
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark text-center">
                <tr>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th>Aksi / Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pinjam as $p) : ?>
                <tr>
                    <td><strong><?= $p['judul'] ?></strong></td>
                    <td class="text-center"><?= $p['tanggal_pinjam'] ?: '-' ?></td>
                    <td class="text-center"><?= $p['tanggal_kembali'] ?: '-' ?></td>
                    <td class="text-center">
                        <?php 
                            $badge = 'badge-secondary';
                            if ($p['status'] == 'pending') $badge = 'badge-warning text-dark';
                            if ($p['status'] == 'disetujui') $badge = 'badge-info';
                            if ($p['status'] == 'dipinjam') $badge = 'badge-primary';
                            if ($p['status'] == 'kembali') $badge = 'badge-success';
                        ?>
                        <span class="badge <?= $badge ?>"><?= ucfirst($p['status']) ?></span>
                    </td>
                    <td>Rp <?= number_format($p['denda'], 0, ',', '.') ?></td>

                    <td class="text-center">
                        <?php if (session()->get('role') == 'admin') : ?>
                            
                            <?php if ($p['status'] == 'pending') : ?>
                                <a href="<?= base_url('buku/setujui/' . $p['id_peminjaman']) ?>" class="btn btn-warning btn-sm shadow-sm">
                                    <i class="fas fa-check"></i> Setujui (Tahap 1)
                                </a>

                            <?php elseif ($p['status'] == 'disetujui') : ?>
                                <a href="<?= base_url('buku/serah_terima/' . $p['id_peminjaman']) ?>" class="btn btn-success btn-sm shadow-sm">
                                    <i class="fas fa-hand-holding"></i> Konfirmasi Serah Terima (Tahap 2)
                                </a>

                            <?php elseif ($p['status'] == 'dipinjam') : ?>
                                <a href="<?= base_url('buku/kembalikan/' . $p['id_peminjaman']) ?>" class="btn btn-primary btn-sm shadow-sm" onclick="return confirm('Konfirmasi pengembalian buku?')">
                                    <i class="fas fa-undo"></i> Kembalikan Buku
                                </a>

                            <?php else : ?>
                                <span class="text-muted small">Selesai</span>
                            <?php endif; ?>

                        <?php else : ?>
                            <small class="text-muted italic">Proses Petugas</small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>