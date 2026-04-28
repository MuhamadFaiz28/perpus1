<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php 
    // Logika Cadangan: Hitung total denda langsung dari list jika variabel $total_denda kosong
    if (!isset($total_denda) || $total_denda == 0) {
        $total_denda = 0;
        if (!empty($denda_list)) {
            foreach ($denda_list as $dl) {
                $total_denda += (int)$dl['denda'];
            }
        }
    }
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Data Denda Anggota</h3>
            <p class="text-muted small">Total pendapatan denda: <span class="fw-bold text-success fs-5">Rp <?= number_format($total_denda, 0, ',', '.') ?></span></p>
        </div>
        <a href="<?= base_url('dashboard') ?>" class="btn btn-light rounded-pill px-4 border shadow-sm">Kembali</a>
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
                            // Pastikan data jatuh_tempo dan tgl_kembali ada agar hitungan hari tidak 0
                            $jt = $d['tanggal_kembali'] ?? $d['jatuh_tempo'] ?? date('Y-m-d');
                            $tk = $d['tanggal_pengembalian'] ?? date('Y-m-d');
                            
                            $selisih = strtotime($tk) - strtotime($jt);
                            $hari = floor($selisih / (60 * 60 * 24));
                            $terlambat = ($hari > 0) ? $hari : 0;
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                        <span class="fw-bold text-dark"><?= esc($d['nama'] ?? $d['nama_peminjam'] ?? 'Anggota') ?></span>
                                    </div>
                                </td>
                                <td><span class="text-secondary"><?= esc($d['judul']) ?></span></td>
                                <td>
                                    <?php if ($terlambat > 0) : ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">
                                            <?= $terlambat ?> Hari
                                        </span>
                                    <?php else : ?>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Tepat Waktu</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="fw-bold text-primary">Rp <?= number_format($d['denda'] ?? 0, 0, ',', '.') ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-info-circle fs-2 mb-2 d-block"></i>
                                Belum ada data denda tersimpan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>