<style>
    /* Styling Sidebar */
    .sidebar-nav {
        padding: 20px 15px;
        background-color: #fcfcfd; /* Warna background sidebar yang lebih soft */
        min-height: 100vh;
    }

    /* Profil Wrapper dengan Efek Elevasi Modern */
    .user-profile-box {
        padding: 25px 15px;
        background: #ffffff;
        background: linear-gradient(145deg, #ffffff, #f1f5f9);
        border-radius: 24px;
        margin-bottom: 30px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0,0,0,0.03);
        border: 1px solid rgba(226, 232, 240, 0.8);
        position: relative;
        overflow: hidden;
    }

    .user-profile-box::before {
        content: "";
        position: absolute;
        top: 0; left: 0; width: 100%; height: 5px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    }

    .user-profile-box img {
        transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 4px solid #fff !important;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        object-fit: cover;
    }

    .user-profile-box:hover img {
        transform: scale(1.05) rotate(2deg);
    }

    .user-badge {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 10px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
        letter-spacing: 0.5px;
        border: 1px solid rgba(59, 130, 246, 0.15);
    }

    /* Menu Link Styling */
    .nav-link {
        color: #475569; /* Slate 600 */
        font-weight: 600;
        padding: 12px 16px !important;
        border-radius: 14px;
        margin-bottom: 4px;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
    }

    .nav-link i, .nav-link span {
        transition: all 0.25s ease;
    }

    .nav-link i {
        font-size: 1.2rem;
        margin-right: 12px;
        color: #94a3b8; /* Icon warna muted */
    }

    /* State Active & Hover */
    .nav-link:hover, .nav-link.active {
        background-color: #eff6ff;
        color: #2563eb !important;
    }

    .nav-link:hover i, .nav-link.active i {
        color: #2563eb;
        transform: translateX(3px);
    }

    .nav-link.active {
        background: linear-gradient(90deg, #eff6ff 0%, #ffffff 100%);
        border-left: 4px solid #2563eb;
        border-radius: 0 14px 14px 0;
        padding-left: 12px !important;
    }

    /* Backup Button Customization */
    .btn-backup {
        width: 100%;
        border-radius: 14px;
        font-weight: 700;
        padding: 12px;
        margin-top: 15px;
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        transition: all 0.3s ease;
        color: white;
    }

    .btn-backup:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px rgba(16, 185, 129, 0.3);
        color: white;
    }

    /* Logout Styling */
    .nav-link.text-danger {
        color: #f43f5e !important;
        margin-top: 20px;
    }

    .nav-link.text-danger:hover {
        background-color: #fff1f2;
        color: #e11d48 !important;
    }

    /* Menu Divider */
    .menu-header {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 700;
        color: #cbd5e1;
        margin: 25px 0 10px 15px;
        display: flex;
        align-items: center;
    }

    .menu-header::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #f1f5f9;
        margin-left: 10px;
    }
</style>

<ul class="nav flex-column sidebar-nav">
    <li class="nav-item">
        <div class="user-profile-box mx-1">
            <img src="<?= base_url('uploads/users/' . session()->get('foto')) ?>" 
                 height="85" width="85" 
                 class="rounded-circle mb-2" />
            <div class="mt-2">
                <h6 class="mb-1 fw-bold text-dark" style="letter-spacing: -0.3px;"><?= session('nama'); ?></h6>
                <span class="user-badge">
                    <i class="bi bi-shield-check-fill me-1"></i> <?= session('role'); ?>
                </span>
            </div>
        </div>
    </li>

    <div class="menu-header">Utama</div>

    <li class="nav-item">
        <a class="nav-link <?= url_is('/') ? 'active' : '' ?>" href="<?= base_url('/') ?>">
            <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= url_is('buku*') ? 'active' : '' ?>" href="<?= base_url('buku') ?>">
            <i class="bi bi-journal-bookmark-fill"></i> <span>Katalog Buku</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="<?= base_url('peminjaman') ?>" class="nav-link <?= (url_is('peminjaman') || url_is('peminjaman/konfirmasi*')) ? 'active' : '' ?>">
            <i class="bi bi-arrow-left-right"></i> <span>Data Peminjaman</span>
        </a>
    </li>

      <li class="nav-item">
        <a href="<?= base_url('peminjaman/riwayat_saya') ?>" class="nav-link <?= url_is('peminjaman/riwayat_saya') ? 'active' : '' ?>">
            <i class="bi bi-clock-history"></i> <span>Riwayat Peminjaman</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="<?= base_url('peminjaman/laporan') ?>" class="nav-link <?= url_is('peminjaman/laporan*') ? 'active' : '' ?>">
            <i class="bi bi-pie-chart-fill"></i> <span>Laporan Sirkulasi</span>
        </a>
    </li>

    <li class="nav-item">
        <a href="<?= base_url('peminjaman/denda') ?>" class="nav-link <?= url_is('peminjaman/denda*') ? 'active' : '' ?>">
            <i class="bi bi-currency-dollar"></i> <span>Data Denda</span>
        </a>
    </li>

    <div class="menu-header">Sistem</div>

    <li class="nav-item">
        <a class="nav-link <?= url_is('profile*') ? 'active' : '' ?>" href="<?= base_url('profile') ?>">
            <i class="bi bi-person-badge-fill"></i> <span>Profil Saya</span>
        </a>
    </li>
 <?php if (session()->get('role') == 'admin') : ?>
    <li class="nav-item">
        <a class="nav-link <?= url_is('users') ? 'active' : '' ?>" href="<?= base_url('users') ?>">
            <i class="bi bi-people-fill"></i> <span>Data Pengguna</span>
        </a>
    </li>

   
    <li class="nav-item px-3">
        <a href="<?= base_url('/backup') ?>" class="btn btn-backup">
            <i class="bi bi-cloud-arrow-down-fill me-2"></i> Backup Database
        </a>
    </li>
    <?php endif; ?>

    <hr class="mx-3 my-4 opacity-0">

    <li class="nav-item">
        <a href="<?= site_url('/logout') ?>" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i> <span>Keluar Sistem</span>
        </a>
    </li>
</ul>