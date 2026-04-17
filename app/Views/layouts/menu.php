   <a href="#">
        <b>Maldin17</b>App
    </a><br>

    <a href="<?= base_url('/') ?>">
        Dashboard
    </a><br>

     <a href="<?= base_url('/buku') ?>"> Buku </a><br>

    <a href="<?= base_url('/users') ?>">
            Data buku
        </a><br>

        <?php $idu = session('id'); ?>
    <a href="<?= base_url('users/edit/' . $idu) ?>">
        Setting
    </a><br>

        <li>
        <a href="<?= base_url('/logout') ?>">Log Out</a>
    </li>

    <br>
Masuk sebagai: <b><?= session('nama'); ?> (<?= session('role'); ?>)</b>
<br>
<img src="<?= base_url('uploads/users/' . session()->get('foto')) ?>" height="80" />

    <?php if (session()->get('role') == 'admin' || session()->get('role') == 'petugas') : ?>
    <?php endif; ?>