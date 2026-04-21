<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">📊 Data Users</h4>
    </div>

    <!-- FILTER -->
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body">
            <form method="get" action="" class="row g-2 align-items-center">

                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control"
                        placeholder="Cari nama..."
                        value="<?= $_GET['keyword'] ?? '' ?>">
                </div>

                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="admin" <?= (($_GET['role'] ?? '') == 'admin') ? 'selected' : '' ?>>Admin</option>
                        <option value="petugas" <?= (($_GET['role'] ?? '') == 'petugas') ? 'selected' : '' ?>>Petugas</option>
                        <option value="anggota" <?= (($_GET['role'] ?? '') == 'anggota') ? 'selected' : '' ?>>Anggota</option>
                    </select>
                </div>

                <div class="col-md-5 d-flex gap-2">
                    <button class="btn btn-primary">🔍 Cari</button>

                    <a href="<?= base_url('users') ?>" class="btn btn-secondary">
                        Reset
                    </a>

                    <a href="<?= base_url('users/print?' . http_build_query($_GET)) ?>" 
                       target="_blank" 
                       class="btn btn-success">
                        🖨 Print
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- ALERT -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Foto</th>
                        <?php if (session()->get('role') == 'admin') : ?>
                            <th class="text-center">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($users)): ?>

                        <?php if (isset($pager)): ?>
                            <?php $no = 1 + (10 * ($pager->getCurrentPage() - 1)); ?>
                        <?php else: ?>
                            <?php $no = 1; ?>
                        <?php endif; ?>

                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $u['nama'] ?></td>
                                <td><?= $u['email'] ?></td>
                                <td><?= $u['username'] ?></td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <?= ucfirst($u['role']) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($u['foto']): ?>
                                        <img src="<?= base_url('uploads/users/' . $u['foto']) ?>"
                                             width="50" height="50"
                                             class="rounded-circle object-fit-cover">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <?php if (session()->get('role') == 'admin') : ?>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('users/detail/' . $u['id']) ?>" 
                                               class="btn btn-info">Detail</a>

                                            <a href="<?= base_url('users/edit/' . $u['id']) ?>" 
                                               class="btn btn-warning">Edit</a>

                                            <a href="<?= base_url('users/wa/' . $u['id']) ?>" 
                                               target="_blank" 
                                               class="btn btn-success">WA</a>

                                            <a href="<?= base_url('users/delete/' . $u['id']) ?>"
                                               onclick="return confirm('Hapus user ini?')"
                                               class="btn btn-danger">Hapus</a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Tidak ada data user
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION -->
    <div class="mt-3">
        <?php if (isset($pager)): ?>
            <?= $pager->links() ?>
        <?php endif; ?>
    </div>

</div>

<?= $this->endSection() ?>