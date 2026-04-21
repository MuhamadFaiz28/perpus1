<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3>📚 Data Peminjaman</h3>

<table class="table table-bordered">
<tr>
    <th>Judul</th>
    <th>Tgl Pinjam</th>
    <th>Jatuh Tempo</th>
    <th>Status</th>
    <th>Denda</th>
    <th>Aksi</th>
</tr>

<?php foreach ($pinjam as $p): ?>
<tr>
    <td><?= $p['judul'] ?></td>
    <td><?= $p['tanggal_pinjam'] ?></td>
    <td><?= $p['tanggal_kembali'] ?></td>
    <td><?= $p['status'] ?></td>
    <td>Rp <?= number_format($p['denda']) ?></td>
    <td>
        <?php if ($p['status'] == 'dipinjam'): ?>
            <a href="<?= base_url('buku/kembalikan/' . $p['id_peminjaman']) ?>" class="btn btn-success btn-sm">
                Kembalikan
            </a>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

</table>

<?= $this->endSection() ?>