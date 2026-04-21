<!-- app/Views/users/create.php -->
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">➕ Tambah User</h5>
        </div>

        <div class="card-body">

            <!-- ERROR -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- FORM -->
            <form action="<?= base_url('users/store') ?>" method="post" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <!-- 🔥 PERBAIKAN ROLE -->
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                        <option value="anggota">Anggota</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="foto" class="form-control" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak upload foto</small>
                </div>

                <!-- BUTTON -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        💾 Simpan
                    </button>

                    <a href="<?= base_url('users') ?>" class="btn btn-secondary">
                        ⬅ Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>