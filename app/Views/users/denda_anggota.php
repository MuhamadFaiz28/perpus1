<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Data Denda' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; border: none; }
        .table thead { background-color: #f1f4f9; }
        .text-rp { font-family: 'Courier New', Courier, monospace; }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Data Denda Anggota</h2>
                <p class="text-muted">Total pendapatan denda: <span class="text-success fw-bold">Rp <?= number_format($total_pendapatan ?? 0, 0, ',', '.') ?></span></p>
            </div>
            <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">Peminjam</th>
                                <th class="py-3">Buku</th>
                                <th class="py-3">Terlambat</th>
                                <th class="pe-4 py-3 text-end">Besar Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($denda)) : ?>
                                <?php foreach ($denda as $d) : 
                                    // LOGIKA HITUNG DENDA OTOMATIS
                                    $tgl_sekarang = new DateTime();
                                    $tgl_jatuh_tempo = new DateTime($d['jatuh_tempo']);
                                    
                                    $hari_terlambat = 0;
                                    $total_denda = $d['denda']; // Ambil denda dari database jika sudah ada

                                    if ($tgl_sekarang > $tgl_jatuh_tempo && $d['status'] != 'Kembali') {
                                        $diff = $tgl_sekarang->diff($tgl_jatuh_tempo);
                                        $hari_terlambat = $diff->days;
                                        // Contoh: Per hari Rp 1.000 (Sesuaikan tarif denda Anda di sini)
                                        $total_denda = $hari_terlambat * 1000; 
                                    } else {
                                        $hari_terlambat = $d['terlambat'] ?? 0;
                                    }
                                ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold"><?= $d['nama_peminjam'] ?></div>
                                            <small class="text-muted">ID: #<?= $d['id_peminjaman'] ?></small>
                                        </td>
                                        <td><?= $d['judul_buku'] ?></td>
                                        <td>
                                            <span class="badge <?= $hari_terlambat > 0 ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning-emphasis' ?> rounded-pill px-3">
                                                <?= $hari_terlambat ?> Hari
                                            </span>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <span class="text-danger fw-bold text-rp">
                                                Rp <?= number_format($total_denda, 0, ',', '.') ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="bi bi-cash-stack display-4 text-muted"></i>
                                        <p class="mt-3 text-muted">Belum ada data denda tersimpan.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>