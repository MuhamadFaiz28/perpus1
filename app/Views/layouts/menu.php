<style>
/* HILANGKAN MARGIN DEFAULT */
body {
    margin: 0;
    padding: 0;
}

/* BIKIN SIDEBAR FULL HEIGHT & NEMPEL */
.sidebar {
    width: 220px;
    height: 100vh; /* penuh ke bawah */
    position: fixed; /* nempel kiri */
    top: 0;
    left: 0;
    background: #d8d3a3;
    padding: 15px;
    font-family: Arial, sans-serif;
    overflow-y: auto;
}

/* KONTEN SUPAYA TIDAK KETUTUP SIDEBAR */
.content {
    margin-left: 220px;
    padding: 20px;
}

.sidebar a {
    display: block;
    padding: 8px 10px;
    margin: 4px 0;
    text-decoration: none;
    color: #000;
    border-radius: 5px;
}

.sidebar a:hover {
    background: #bfb97a;
}

.sidebar b {
    font-size: 18px;
}

.profile {
    margin-top: 15px;
    font-size: 14px;
}

.profile img {
    margin-top: 8px;
    border-radius: 5px;
}
</style>

<div class="sidebar">

    <a href="#">
        <b>App</b>
    </a>

    <a href="<?= base_url('/') ?>">Dashboard</a>
    <a href="<?= base_url('/buku') ?>">Buku</a>
    <a href="<?= base_url('/rak') ?>">Rak Buku</a>

    <!-- TAMBAHAN TRANSAKSI -->
    <a href="<?= base_url('/peminjaman') ?>">Transaksi Peminjaman</a>
    <a href="<?= base_url('/pengembalian') ?>">Transaksi Pengembalian</a>
    <!-- END -->

    <a href="<?= base_url('/users') ?>">Data Users</a>

    <?php $idu = session('id'); ?>
    <a href="<?= base_url('users/edit/' . $idu) ?>">Setting</a>

    <a href="<?= base_url('/logout') ?>">Log Out</a>

    <div class="profile">
        <p>Masuk sebagai:</p>
        <b><?= session('nama'); ?> (<?= session('role'); ?>)</b><br>

        <img src="<?= base_url('uploads/users/' . session()->get('foto')) ?>" height="80" />
    </div>

</div>