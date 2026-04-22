<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="card shadow-sm mx-auto" style="max-width: 500px; border-radius: 15px;">
        <div class="card-body p-4">
            <h4 class="mb-4 text-center">📅 Atur Durasi Pinjam</h4>
            <div class="text-center mb-4">
                <img src="<?= base_url('uploads/buku/' . $buku['cover']) ?>" style="width: 120px; border-radius: 8px;">
                <h5 class="mt-2"><?= $buku['judul'] ?></h5>
            </div>

            <form action="<?= base_url('buku/proses_pinjam/' . $buku['id_buku']) ?>" method="post">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tanggal Pinjam</label>
                    <input type="text" class="form-control bg-light" value="<?= date('d-m-Y') ?>" readonly>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Tanggal Jatuh Tempo</label>
                    <input type="date" name="jatuh_tempo" class="form-control" 
                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" 
                           value="<?= date('Y-m-d', strtotime('+2 days')) ?>" required>
                    <small class="text-muted">*Tentukan kapan Anda akan mengembalikan buku ini.</small>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill">Konfirmasi Pinjam</button>
                    <a href="<?= base_url('buku/detail/' . $buku['id_buku']) ?>" class="btn btn-link text-muted">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>