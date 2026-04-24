<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 text-center">
                    <h4 class="fw-bold text-dark mb-0">Tambah User Baru</h4>
                    <p class="text-muted small">Paos28App Management System</p>
                </div>
                <div class="card-body p-4">
                    <form action="<?= base_url('user/simpan') ?>" method="post">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">NAMA LENGKAP</label>
                            <input type="text" name="nama" class="form-control rounded-pill px-3" placeholder="Masukkan nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">USERNAME</label>
                            <input type="text" name="username" class="form-control rounded-pill px-3" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">PASSWORD</label>
                            <input type="password" name="password" class="form-control rounded-pill px-3" placeholder="Min. 6 karakter" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">ROLE</label>
                            <select name="role" class="form-select rounded-pill px-3">
                                <option value="Anggota">Anggota</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">Simpan User</button>
                            <a href="<?= base_url('user') ?>" class="btn btn-light rounded-pill py-2 text-muted fw-bold">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>