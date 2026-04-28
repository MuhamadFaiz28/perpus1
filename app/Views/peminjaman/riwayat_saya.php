<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <h2 class="fw-bold mb-4">
        Riwayat Peminjaman <?= (session()->get('role') == 'admin') ? '' : 'Saya' ?>
    </h2>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Buku</th>
                            <?php if (session()->get('role') == 'admin'): ?>
                                <th>Peminjam</th>
                            <?php endif; ?>
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
                                            <?php if (!empty($p['cover']) && file_exists('uploads/buku/' . $p['cover'])): ?>
                                                <img src="<?= base_url('uploads/buku/' . $p['cover']) ?>" 
                                                     class="rounded me-3" style="width: 45px; height: 60px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded me-3 bg-secondary d-flex align-items-center justify-content-center" style="width: 45px; height: 60px;">
                                                    <i class="bi bi-book text-white"></i>
                                                </div>
                                            <?php endif; ?>
                                            <span class="fw-bold"><?= esc($p['judul']) ?></span>
                                        </div>
                                    </td>

                                    <?php if (session()->get('role') == 'admin'): ?>
                                        <td><span class="badge bg-info text-dark"><?= esc($p['nama_lengkap'] ?? 'User') ?></span></td>
                                    <?php endif; ?>

                                    <td><?= ($p['tanggal_pinjam'] && $p['tanggal_pinjam'] != '0000-00-00') ? date('d M Y', strtotime($p['tanggal_pinjam'])) : '-' ?></td>
                                    <td><?= ($p['tanggal_kembali'] && $p['tanggal_kembali'] != '0000-00-00') ? date('d M Y', strtotime($p['tanggal_kembali'])) : '-' ?></td>
                                    
                                    <td>
                                        <span class="badge bg-success rounded-pill">Selesai / Kembali</span>
                                    </td>
                                    
                                    <td class="pe-4 text-end">
                                        <button class="btn btn-sm btn-light disabled text-muted">Selesai</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="<?= (session()->get('role') == 'admin') ? '6' : '5' ?>" class="text-center py-5 text-muted">
                                    <i class="bi bi-info-circle fs-2 d-block mb-2"></i>
                                    Tidak ada riwayat peminjaman yang sudah selesai.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>