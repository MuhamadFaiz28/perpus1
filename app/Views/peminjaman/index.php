<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    body { background-color: #f8fafc; }
    .page-header {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        border-radius: 20px;
        color: white;
        padding: 30px;
        margin-bottom: 30px;
    }
    .table-container {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .table thead {
        background-color: #f1f5f9;
    }
    .table thead th {
        border: none;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 15px;
    }
    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.3s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container py-5">
    
    <div class="page-header d-flex justify-content-between align-items-center shadow-sm">
        <div>
            <h2 class="fw-bold m-0"><i class="fas fa-exchange-alt me-2"></i> Data Peminjaman</h2>
            <p class="m-0 opacity-75">Kelola sirkulasi buku dan status pengembalian anggota.</p>
        </div>
        <a href="<?= base_url('peminjaman/tambah') ?>" class="btn btn-warning rounded-pill px-4 fw-bold shadow">
            <i class="fas fa-plus me-2"></i> Tambah Pinjaman
        </a>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table align-middle m-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID Anggota</th>
                        <th>Informasi Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th class="text-center">Denda</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($peminjaman)): ?>
                        <?php foreach ($peminjaman as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-2" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user-tag fa-sm"></i>
                                    </div>
                                    <span class="fw-bold text-dark"><?= esc($p['id_anggota']); ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark text-truncate" style="max-width: 200px;"><?= esc($p['judul']); ?></div>
                                <small class="text-muted">ID Buku: <?= $p['id_buku']; ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= date('d M Y', strtotime($p['tanggal_pinjam'])); ?></span></td>
                            <td>
                                <span class="badge bg-light text-dark border <?= (strtotime($p['tanggal_kembali']) < strtotime(date('Y-m-d')) && $p['status'] == 'dipinjam') ? 'border-danger text-danger' : '' ?>">
                                    <?= date('d M Y', strtotime($p['tanggal_kembali'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($p['status'] == 'dipinjam'): ?>
                                    <span class="badge-status bg-warning text-warning bg-opacity-10">
                                        <i class="fas fa-clock me-1"></i> Dipinjam
                                    </span>
                                <?php else: ?>
                                    <span class="badge-status bg-success text-success bg-opacity-10">
                                        <i class="fas fa-check-circle me-1"></i> Kembali
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold <?= ($p['denda'] ?? 0) > 0 ? 'text-danger' : 'text-muted' ?>">
                                    Rp <?= number_format($p['denda'] ?? 0, 0, ',', '.'); ?>
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <?php if ($p['status'] == 'dipinjam'): ?>
                                        <a href="<?= base_url('peminjaman/kembalikan/' . $p['id_peminjaman']) ?>" 
                                           class="btn btn-primary btn-sm rounded-pill px-3 fw-bold shadow-sm"
                                           onclick="return confirm('Konfirmasi pengembalian buku?')">
                                            <i class="fas fa-undo me-1"></i> Kembalikan
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold" disabled>
                                            <i class="fas fa-check me-1"></i> Selesai
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted italic">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i><br>
                                Tidak ada data peminjaman.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>