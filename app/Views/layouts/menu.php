<style>
    /* Styling Sidebar */
    .sidebar-nav {
        padding: 20px 15px;
    }

    /* Profil Wrapper dengan efek Glassmorphism */
    .user-profile-box {
        padding: 25px 15px;
        background: #ffffff;
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        border-radius: 24px;
        margin-bottom: 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }

    .user-profile-box::before {
        content: "";
        position: absolute;
        top: 0; left: 0; width: 100%; height: 4px;
        background: linear-gradient(90deg, #0d6efd, #6610f2);
    }

    .user-profile-box img {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 4px solid #fff !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        object-fit: cover;
    }

    .user-profile-box:hover img {
        transform: scale(1.08) translateY(-5px);
    }

    .user-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        background: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
        letter-spacing: 0.8px;
        border: 1px solid rgba(13, 110, 253, 0.1);
    }

    /* Menu Link Styling */
    .nav-link {
        color: #64748b; /* Slate color untuk kesan modern */
        font-weight: 600;
        padding: 14px 18px !important;
        border-radius: 16px;
        margin-bottom: 6px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        position: relative;
    }

    .nav-link i {
        font-size: 1.3rem;
        margin-right: 14px;
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        background-color: #f1f5f9;
        color: #0d6efd !important;
        transform: translateX(8px);
    }

    .nav-link:hover i {
        color: #0d6efd;
        transform: scale(1.1);
    }

    /* Backup Button Customization */
    .btn-backup {
        width: 100%;
        border-radius: 16px;
        font-weight: 700;
        padding: 12px;
        margin-top: 15px;
        background: linear-gradient(135deg, #198754, #146c43);
        border: none;
        box-shadow: 0 8px 20px rgba(25, 135, 84, 0.15);
        transition: all 0.3s ease;
    }

    .btn-backup:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(25, 135, 84, 0.25);
    }

    /* Logout Styling */
    .nav-link.text-danger {
        color: #ef4444 !important;
        margin-top: 10px;
    }

    .nav-link.text-danger:hover {
        background-color: #fef2f2;
        color: #dc2626 !important;
    }

    /* Menu Divider */
    .menu-header {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 800;
        color: #94a3b8;
        margin: 25px 0 12px 18px;
        display: flex;
        align-items: center;
    }

    .menu-header::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #e2e8f0;
        margin-left: 10px;
    }
</style>

<ul class="nav flex-column sidebar-nav mt-2">
    <li class="nav-item">
        <div class="user-profile-box mx-1">
            <img src="<?= base_url('uploads/users/' . session()->get('foto')) ?>" 
                 height="95" width="95" 
                 class="rounded-circle mb-2" />
            <div class="mt-2">
                <h6 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.5px;"><?= session('nama'); ?></h6>
                <span class="user-badge shadow-sm">
                    <i class="bi bi-shield-check-fill me-1"></i> <?= session('role'); ?>
                </span>
            </div>
        </div>
    </li>

    <div class="menu-header">Utama</div>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/') ?>">
            <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('buku') ?>">
            <i class="bi bi-journal-bookmark-fill"></i> <span>Katalog Buku</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="<?= base_url('peminjaman/laporan') ?>" class="nav-link">
            <i class="bi bi-pie-chart-fill"></i> <span>Laporan Sirkulasi</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="<?= base_url('peminjaman/denda') ?>" class="nav-link">
            <i class="bi bi-currency-dollar"></i> <span>Data Denda</span>
        </a>
    </li>

    <div class="menu-header">Sistem</div>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('profile') ?>">
            <i class="bi bi-person-badge-fill"></i> <span>Profil Saya</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('users') ?>">
            <i class="bi bi-people-fill"></i> <span>Data Pengguna</span>
        </a>
    </li>

    <?php $idu = session('id_user'); ?>
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('users/edit/' . $idu) ?>">
            <i class="bi bi-gear-wide-connected"></i> <span>Konfigurasi</span>
        </a>
    </li>

    <?php if (session()->get('role') == 'admin') : ?>
    <li class="nav-item px-3">
        <a href="<?= base_url('/backup') ?>" class="btn btn-success btn-backup">
            <i class="bi bi-cloud-arrow-down-fill me-2"></i> Backup Database
        </a>
    </li>
    <?php endif; ?>

    <hr class="mx-3 my-4 opacity-5">

    <li class="nav-item">
        <a href="<?= site_url('/logout') ?>" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i> <span>Keluar Sistem</span>
        </a>
    </li>
</ul>