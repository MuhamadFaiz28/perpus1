<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    body { background-color: #f8fafc; }
    .hero-section {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        border-radius: 24px;
        position: relative;
        overflow: hidden;
    }
    .book-item { transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .cover-wrapper {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        background: #e2e8f0;
        aspect-ratio: 3/4;
    }
    .cover-buku {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .book-item:hover .cover-buku { transform: scale(1.1); }
    .book-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        padding-bottom: 15px;
        z-index: 2;
    }
    .book-item:hover .book-overlay { opacity: 1; }
    .custom-table-card { border-radius: 24px; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    .badge-status { padding: 6px 12px; border-radius: 8px; font-weight: 600; }

    /* Timeline Styling */
    .timeline-dashboard { position: relative; }
    .timeline-dashboard::before {
        content: "";
        position: absolute;
        left: 7px;
        top: 10px;
        bottom: 20px;
        width: 2px;
        background: #e9ecef;
        z-index: 1;
    }
</style>

<div class="dashboard-wrapper">
    <div class="container-fluid px-4 pt-4">
        
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div><?= session()->getFlashdata('success'); ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-banner p-4 shadow-sm" style="background: white; border-radius: 20px;">
                    <div class="row align-items-center">
                        <div class="col-md-8 d-flex align-items-center">
                            <div class="icon-box bg-primary text-white shadow-sm mb-0 me-4 d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; border-radius: 15px;">
                                <i class="bi bi-person-badge fs-3"></i>
                            </div>
                            <div>
                                <h2 class="fw-extrabold mb-1 text-dark">Hello, <?= session()->get('nama'); ?>! </h2>
                                <p class="text-muted mb-0">Selamat datang di panel kendali <b>Jago Maca App</b>. Semangat membaca hari ini!</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="text-muted small">Akses Terakhir: <span class="fw-bold text-dark"><?= date('H:i'); ?> WIB</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card shadow-sm h-100 border-0" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="icon-box bg-primary bg-opacity-10 text-primary mb-3 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; border-radius: 12px;">
                            <i class="bi bi-book-half fs-4"></i>
                        </div>
                        <h6 class="text-muted fw-bold small text-uppercase">Total Koleksi</h6>
                        <h2 class="fw-bold text-dark mb-3"><?= number_format($total_buku); ?></h2>
                        <a href="<?= base_url('buku'); ?>" class="btn btn-sm btn-pill btn-outline-primary w-100 rounded-pill">
                            <?= (strtolower(session()->get('role')) == 'anggota') ? 'Cari Buku' : 'Kelola Buku'; ?>
                        </a>
                    </div>
                </div>
            </div>

            <?php if (strtolower(session()->get('role')) == 'anggota') : ?>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card shadow-sm h-100 border-0" style="border-radius: 20px;">
                        <div class="card-body p-4">
                            <div class="icon-box bg-info bg-opacity-10 text-info mb-3 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; border-radius: 12px;">
                                <i class="bi bi-clock-history fs-4"></i>
                            </div>
                            <h6 class="text-muted fw-bold small text-uppercase">Riwayat Pinjam</h6>
                            <h2 class="fw-bold text-dark mb-3"><?= $sirkulasi_aktif; ?></h2>
                            <a href="<?= base_url('peminjaman/riwayat_saya'); ?>" class="btn btn-sm btn-pill btn-outline-info w-100 rounded-pill">Lihat Riwayat</a>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card shadow-sm h-100 border-0" style="border-radius: 20px;">
                        <div class="card-body p-4">
                            <div class="icon-box bg-success bg-opacity-10 text-success mb-3 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; border-radius: 12px;">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <h6 class="text-muted fw-bold small text-uppercase">Anggota Aktif</h6>
                            <h2 class="fw-bold text-dark mb-3"><?= $total_anggota; ?></h2>
                            <a href="<?= base_url('anggota'); ?>" class="btn btn-sm btn-pill btn-outline-success w-100 rounded-pill">Cek Member</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-xl-3 col-md-6">
                <div class="card stat-card shadow-sm h-100 border-0" style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <div class="icon-box bg-danger bg-opacity-10 text-danger mb-3 d-flex justify-content-center align-items-center" style="width: 50px; height: 50px; border-radius: 12px;">
                            <i class="bi bi-cash-stack fs-4"></i>
                        </div>
                        <h6 class="text-muted fw-bold small text-uppercase">Status Denda</h6>
                        <h2 class="fw-bold text-dark mb-3">Rp <?= number_format($total_pendapatan ?? 0, 0, ',', '.'); ?></h2>
                        <a href="<?= base_url('peminjaman/denda'); ?>" class="btn btn-sm btn-pill btn-outline-danger w-100 rounded-pill">Cek Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card log-card shadow-sm border-0" style="border-radius: 20px;">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center">
                            <span class="p-2 bg-primary bg-opacity-10 rounded-3 me-3">
                                <i class="bi bi-lightning-charge text-primary"></i>
                            </span>
                            Aktivitas Terbaru
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Waktu</th>
                                        <th>Keterangan</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($logs)) : ?>
                                        <?php foreach ($logs as $l) : ?>
                                            <tr>
                                                <td class="ps-3 small text-secondary">
                                                    <i class="bi bi-calendar3 me-1"></i> 
                                                    <?= isset($l['created_at']) ? date('d M Y', strtotime($l['created_at'])) : '-'; ?>
                                                    <br>
                                                    <i class="bi bi-clock me-1"></i> 
                                                    <?= isset($l['created_at']) ? date('H:i', strtotime($l['created_at'])) : '--:--'; ?>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-dark"><?= $l['pesan']; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Verified</span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-5">
                                                <i class="bi bi-folder2-open fs-1 opacity-25 d-block mb-3"></i>
                                                <p>Belum ada rekaman aktivitas terbaru.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>