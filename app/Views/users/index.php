<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    /* Styling tambahan untuk memperhalus tampilan */
    .card-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0 !important;
    }
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        color: #6c757d;
        background-color: #f8f9fa;
        border-top: none;
    }
    .user-avatar {
        transition: transform 0.2s ease;
    }
    .user-avatar:hover {
        transform: scale(1.1);
    }
    .badge-role {
        padding: 6px 12px;
        font-weight: 600;
        border-radius: 30px;
    }
    .btn-action {
        border-radius: 8px;
        transition: all 0.2s;
    }
    .btn-action:hover {
        opacity: 0.85;
        transform: translateY(-1px);
    }
</style>

<div class="container py-4">

    <div class="row mb-4">
        <div class="col">
            <h4 class="fw-bold text-dark"><i class="bi bi-people-fill me-2 text-primary"></i>Data Users</h4>
            <p class="text-muted small">Manajemen data pengguna dan hak akses sistem.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="get" action="" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary">Cari Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="keyword" class="form-control border-start-0" 
                               placeholder="Masukkan nama..." value="<?= $_GET['keyword'] ?? '' ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary">Filter Role</label>
                    <select name="role" class="form-select border-1">
                        <option value="">Semua Role</option>
                        <option value="admin" <?= (($_GET['role'] ?? '') == 'admin') ? 'selected' : '' ?>>Admin</option>
                        <option value="petugas" <?= (($_GET['role'] ?? '') == 'petugas') ? 'selected' : '' ?>>Petugas</option>
                        <option value="anggota" <?= (($_GET['role'] ?? '') == 'anggota') ? 'selected' : '' ?>>Anggota</option>
                    </select>
                </div>

                <div class="col-md-5">
    <div class="d-flex gap-2">
        <button class="btn btn-primary px-4 btn-action">
            <i class="bi bi-filter"></i> Terapkan
        </button>
        <a href="<?= base_url('users') ?>" class="btn btn-light px-4 border btn-action">
            <i class="bi bi-arrow-counterclockwise"></i> Reset
        </a>
        <a href="<?= base_url('users/print?' . http_build_query($_GET)) ?>" 
           target="_blank" class="btn btn-success px-4 btn-action ms-auto">
            <i class="bi bi-printer"></i> Print
        </a>
        <a href="<?= base_url('users/tambah') ?>" class="btn btn-primary px-4 btn-action ms-2">
    <i class="bi bi-plus-circle"></i> Tambah User
</a>    
    </div>
</div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div><?= session()->getFlashdata('success') ?></div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Identitas User</th>
                        <th>Kontak</th>
                        <th>Role</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php 
                            if (isset($pager)): 
                                $no = 1 + (10 * ($pager->getCurrentPage() - 1));
                            else: 
                                $no = 1;
                            endif; 
                        ?>

                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-muted"><?= $no++ ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($u['foto']): ?>
                                            <img src="<?= base_url('uploads/users/' . $u['foto']) ?>"
                                                 width="48" height="48" class="rounded-circle shadow-sm user-avatar object-fit-cover me-3 border">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-soft-primary d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 48px; height: 48px; background-color: #e9ecef;">
                                                <i class="bi bi-person text-secondary fs-4"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-bold text-dark"><?= $u['nama'] ?></div>
                                            <small class="text-muted">@<?= $u['username'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small"><i class="bi bi-envelope me-1 text-primary"></i> <?= $u['email'] ?></div>
                                </td>
                                <td>
                                    <?php 
                                        $roleClass = 'bg-info text-dark';
                                        if($u['role'] == 'admin') $roleClass = 'bg-danger text-white';
                                        if($u['role'] == 'petugas') $roleClass = 'bg-warning text-dark';
                                    ?>
                                    <span class="badge badge-role <?= $roleClass ?> small">
                                        <?= strtoupper($u['role']) ?>
                                    </span>
                                </td>

                                <?php if (session()->get('role') == 'admin') : ?>
                                    <td class="text-center pe-4">
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm rounded-pill shadow-sm border" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="border-radius: 12px;">
                                                <li><a class="dropdown-item rounded-3 mb-1" href="<?= base_url('users/detail/' . $u['id']) ?>"><i class="bi bi-eye text-info me-2"></i> Detail</a></li>
                                                <li><a class="dropdown-item rounded-3 mb-1" href="<?= base_url('users/edit/' . $u['id']) ?>"><i class="bi bi-pencil text-warning me-2"></i> Edit</a></li>
                                                <li><a class="dropdown-item rounded-3 mb-1 text-success" href="<?= base_url('users/wa/' . $u['id']) ?>" target="_blank"><i class="bi bi-whatsapp me-2"></i> WhatsApp</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item rounded-3 text-danger" href="<?= base_url('users/delete/' . $u['id']) ?>" onclick="return confirm('Hapus user ini?')"><i class="bi bi-trash me-2"></i> Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                <span class="text-muted">Oops! Tidak ada data user yang ditemukan.</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 px-2">
        <div class="small text-muted">
            Menampilkan data <strong><?= count($users) ?></strong> pengguna.
        </div>
        <div>
            <?php if (isset($pager)): ?>
                <?= $pager->links() ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>