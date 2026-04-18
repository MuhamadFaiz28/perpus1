<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
.card {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.top-bar {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.top-bar form input {
    padding: 5px;
}

.top-bar a {
    padding: 6px 10px;
    background: #bfb97a;
    color: #000;
    text-decoration: none;
    border-radius: 5px;
    margin-left: 5px;
}

.top-bar a:hover {
    background: #a8a25f;
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

table th, table td {
    border: 1px solid #ccc;
    padding: 6px;
    text-align: center;
}

table th {
    background: #d8d3a3;
}

table tr:nth-child(even) {
    background: #f9f9f9;
}

table img {
    border-radius: 5px;
}

.aksi a {
    display: inline-block;
    margin: 2px;
    padding: 3px 6px;
    background: #d8d3a3;
    text-decoration: none;
    border-radius: 4px;
    font-size: 12px;
}

.aksi a:hover {
    background: #bfb97a;
}
</style>

<div class="card">

    <h3>Data Buku</h3>

    <div class="top-bar">
        <form method="get">
            <input type="text" name="keyword" placeholder="Cari judul">
            <button type="submit">Cari</button>
        </form>

        <div>
            <a href="<?= base_url('buku/create') ?>">Tambah</a>
            <a href="<?= base_url('buku/print') ?>" target="_blank">Print</a>

            <!-- ✅ TOMBOL KEMBALI -->
            <a href="<?= base_url('/') ?>">Kembali</a>
        </div>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>ISBN</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Penulis</th>
            <th>Penerbit</th>
            <th>Rak</th>
            <th>Tahun</th>
            <th>Jumlah</th>
            <th>Tersedia</th>
            <th>Cover</th>
            <th>Aksi</th>
        </tr>

        <?php foreach ($buku as $b): ?>
            <tr>
                <td><?= $b['id_buku'] ?></td>
                <td><?= $b['isbn'] ?></td>
                <td><?= $b['judul'] ?></td>
                <td><?= $b['nama_kategori'] ?></td>
                <td><?= $b['nama_penulis'] ?></td>
                <td><?= $b['nama_penerbit'] ?></td>
                <td><?= $b['nama_rak'] ?></td>
                <td><?= $b['tahun_terbit'] ?></td>
                <td><?= $b['jumlah'] ?></td>
                <td><?= $b['tersedia'] ?></td>
                <td>
                    <?php if ($b['cover']): ?>

                        <?php $ext = pathinfo($b['cover'], PATHINFO_EXTENSION); ?>

                        <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                            <img src="<?= base_url('uploads/buku/' . $b['cover']) ?>" width="60">
                        <?php else: ?>
                            <a href="<?= base_url('uploads/buku/' . $b['cover']) ?>" target="_blank">File</a>
                        <?php endif; ?>

                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td class="aksi">
                    <a href="<?= base_url('buku/detail/' . $b['id_buku']) ?>">Detail</a>
                    <a href="<?= base_url('buku/edit/' . $b['id_buku']) ?>">Edit</a>
                    <a href="<?= base_url('buku/delete/' . $b['id_buku']) ?>">Hapus</a>
                    <a href="<?= base_url('buku/wa/' . $b['id_buku']) ?>" target="_blank">WA</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</div>

<?= $this->endSection() ?>