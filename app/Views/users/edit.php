<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="bi bi-person-gear me-2 text-primary"></i>Edit Profile User
                    </h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php 
                        // Ambil ID dengan aman untuk action form
                        $id_user = $user['id_user'] ?? $user['id'] ?? ''; 
                    ?>

                    <form action="<?= base_url('users/update/' . $id_user) ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= old('nama', $user['nama'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email', $user['email'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" value="<?= old('username', $user['username'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                            <div class="form-text text-info small">
                                <i class="bi bi-info-circle me-1"></i> Biarkan kosong jika password tetap sama.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <select name="role" class="form-select" required>
                                <?php $role_sekarang = strtolower($user['role'] ?? ''); ?>
                                <option value="Admin" <?= ($role_sekarang == 'admin') ? 'selected' : '' ?>>Admin</option>
                                <option value="Petugas" <?= ($role_sekarang == 'petugas') ? 'selected' : '' ?>>Petugas</option>
                                 <option value="Anggota" <?= ($role_sekarang == 'anggota') ? 'selected' : '' ?>>Anggota</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Profil</label>
                            <input type="file" name="foto" class="form-control mb-2" accept="image/*">
                            
                            <div class="mt-3">
                                <p class="text-muted small mb-1">Foto saat ini:</p>
                                <?php 
                                    $namaFoto = $user['foto'] ?? '';
                                    if ($namaFoto != '' && file_exists('uploads/users/' . $namaFoto)) {
                                        $urlFoto = base_url('uploads/users/' . $namaFoto);
                                    } else {
                                        $urlFoto = base_url('uploads/users/default.png');
                                    }
                                ?>
                                <img src="<?= $urlFoto ?>" width="120" height="120" class="rounded shadow-sm border object-fit-cover">
                            </div>
                        </div>

                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('users') ?>" class="btn btn-light border px-4">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .object-fit-cover {
        object-fit: cover;
    }
    .card {
        border-radius: 12px;
    }
</style>

<?= $this->endSection() ?>