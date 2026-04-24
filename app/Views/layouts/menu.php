<style>
    /* Styling Sidebar */
    .sidebar-nav {
        padding: 10px;
    }

    /* Profil Wrapper */
    .user-profile-box {
        padding: 20px 15px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 15px;
        margin-bottom: 20px;
        text-align: center;
    }

    .user-profile-box img {
        transition: all 0.3s ease;
        border: 3px solid #fff !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .user-profile-box:hover img {
        transform: scale(1.05);
    }

    .user-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        background-color: #e0f7fa; /* Warna biru muda lembut */
        color: #006064;
    }

    /* Menu Link Styling */
    .nav-link {
        color: #555;
        font-weight: 500;
        padding: 12px 20px !important;
        border-radius: 10px;
        margin-bottom: 5px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .nav-link i {
        font-size: 1.2rem;
        margin-right: 15px;
        transition: transform 0.3s ease;
    }

    .nav-link:hover {
        background-color: #f0f2f5;
        color: #0d6efd !important;
    }

    .nav-link:hover i {
        transform: translateX(3px);
    }

    /* Khusus Logout */
    .nav-link.text-danger:hover {
        background-color: #fff5f5;
        color: #dc3545 !important;
    }

    /* Menu Divider */
    .menu-header {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #aaa;
        margin: 15px 0 10px 20px;
    }
</style>

<ul class="nav flex-column sidebar-nav mt-3">
    <li class="nav-item">
        <div class="user-profile-box mx-2">
            <img src="<?= base_url('uploads/users/' . session()->get('foto')) ?>" 
                 height="85" width="85" 
                 class="rounded-circle" 
                 style="object-fit: cover;" />
            <div class="mt-3">
                <h6 class="mb-1 fw-bold text-dark"><?= session('nama'); ?></h6>
                <span class="user-badge small shadow-sm">
                    <i class="bi bi-shield-check me-1"></i> <?= session('role'); ?>
                </span>
            </div>
        </div>
    </li>

    <div class="menu-header">Main Menu</div>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/') ?>">
            <i class="bi bi-grid-1x2"></i> <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('buku') ?>">
            <i class="bi bi-journal-bookmark"></i> <span>Katalog Buku</span>
        </a>
    </li>

    <div class="menu-header">Administrator</div>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/users') ?>">
            <i class="bi bi-people"></i> <span>Manajemen Users</span>
        </a>
    </li>

    <?php $idu = session('id_user'); ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('users/edit/' . $idu) ?>">
            <i class="bi bi-gear"></i> <span>Pengaturan Akun</span>
        </a>
    </li>

    <hr class="mx-3 my-2 opacity-10">

    <li class="nav-item">
        <a href="<?= site_url('/logout') ?>" class="nav-link text-danger">
            <i class="bi bi-power"></i> <span>Keluar</span>
        </a>
    </li>
</ul>