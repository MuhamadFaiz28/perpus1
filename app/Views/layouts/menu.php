<ul class="nav flex-column mt-3">
    <li class="nav-item">
        <div class="text-center mb-3">
            <img src="<?= base_url('uploads/users/' . session()->get('foto')) ?>" height="80" width="80" class="rounded-circle border border-primary" style="object-fit: cover;" />
            <br>
            <span class="bg-info text-dark mt-2">
                <?= session('nama'); ?> (<?= session('role'); ?>)
            </span>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/') ?>">
            <i class="bi bi-house"></i> <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/users') ?>">
            <i class="bi bi-people"></i> <span>Users</span>
        </a>
    </li>

    <?php $idu = session('id_user'); ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('users/edit/' . $idu) ?>">
            <i class="bi bi-person-gear"></i> <span>Pengaturan</span>
        </a>
    </li>
<li class="nav-item">
    <a class="nav-link" href="<?= base_url('buku') ?>">Katalog Buku</a>
</li>
    <li class="nav-item mt-2">
        <a href="<?= site_url('/logout') ?>" class="nav-link text-danger">
            <i class="bi bi-box-arrow-left"></i> Keluar
        </a>
    </li>
</ul>