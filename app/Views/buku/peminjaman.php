<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <h2 class="fw-bold mb-4">Riwayat Peminjaman Saya</h2>

    <div class="card shadow-sm border-0" style="border-radius: 15px;">
        <div class="table-responsive p-3">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Buku</th>
                        <th>Tanggal Pinjam</th>
                        <th>Batas/Tanggal Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pinjaman)): ?>
                      <?php foreach ($pinjaman as $p): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= esc($p['judul']) ?></div>
                            </td>
                            
                            <td><?= (!empty($p['tanggal_pinjam'])) ? date('d/m/Y', strtotime($p['tanggal_pinjam'])) : '-' ?></td>
                            
                            <td>
                                <?php if ($p['status'] == 'kembali'): ?>
                                    <?= (!empty($p['tanggal_kembali'])) ? date('d/m/Y', strtotime($p['tanggal_kembali'])) : '-' ?>
                                <?php else: ?>
                                    <span class="text-danger">
                                        Jatuh Tempo: <?= (!empty($p['jatuh_tempo']) && $p['jatuh_tempo'] != '0000-00-00') ? date('d/m/Y', strtotime($p['jatuh_tempo'])) : 'Belum Atur' ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <?php if ($p['status'] == 'kembali'): ?>
                                    <span class="badge bg-success rounded-pill">Selesai</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark rounded-pill">Masih Dipinjam</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                Tidak ada riwayat peminjaman ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>