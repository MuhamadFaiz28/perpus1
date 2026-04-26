<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Tambah Peminjaman' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; border: none; }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0">Tambah Peminjaman</h2>
                    <a href="<?= base_url('peminjaman') ?>" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="<?= base_url('peminjaman/simpan') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label class="form-label">Pilih Anggota</label>
                                <select name="id_anggota" class="form-select" required>
                                    <option value="">-- Pilih Nama Anggota --</option>
                                    <?php foreach ($users as $u) : ?>
                                        <option value="<?= $u['id'] ?>"><?= $u['nama'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pilih Buku</label>
                                <select name="id_buku" class="form-select" required>
                                    <option value="">-- Pilih Judul Buku --</option>
                                    <?php foreach ($buku as $b) : ?>
                                        <?php $stok = $b['stok'] ?? $b['tersedia']; ?>
                                        <option value="<?= $b['id_buku'] ?>" <?= $stok <= 0 ? 'disabled' : '' ?>>
                                            <?= $b['judul'] ?> (Stok: <?= $stok ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary rounded-pill py-2">
                                    <i class="bi bi-save"></i> Simpan Peminjaman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>