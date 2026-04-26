<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark">Data Denda Anggota</h3>
            <p class="text-muted small">Total pendapatan denda: <span class="fw-bold text-success">Rp <?= number_format($total_denda ?? 0, 0, ',', '.') ?></span></p>
        </div>
        <a href="<?= base_url('peminjaman') ?>" class="btn btn-light rounded-pill px-4 border shadow-sm">Kembali</a>
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
                            // Menggunakan jatuh_tempo sebagai pembanding (sesuai data image_9ceb2f)
                            $jatuh_tempo = strtotime($d['jatuh_tempo'] ?? date('Y-m-d'));
                            $tgl_kembali = strtotime($d['tanggal_pengembalian'] ?? date('Y-m-d'));
                            
                            // Hitung selisih hari
                            $selisih_detik = $tgl_kembali - $jatuh_tempo;
                            $hari = floor($selisih_detik / (60 * 60 * 24));
                            $terlambat = ($hari > 0) ? $hari : 0;
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="fw-bold text-dark"><?= esc($d['nama'] ?? $d['nama_peminjam'] ?? 'Anggota') ?></span>
                                </td>
                                <td><?= esc($d['judul']) ?></td>
                                <td>
                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                        <?= $terlambat ?> Hari
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="fw-bold text-primary">Rp <?= number_format($d['denda'] ?? 0, 0, ',', '.') ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-info-circle mb-2"></i><br>
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