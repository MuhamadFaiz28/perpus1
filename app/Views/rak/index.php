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

    <h3>Data Rak Buku</h3>

    <div class="top-bar">
        <form method="get">
            <input type="text" name="keyword" placeholder="Cari rak">
            <button type="submit">Cari</button>
        </form>

        <div>
            <a href="<?= base_url('rak/create') ?>">Tambah</a>
            <a href="<?= base_url('rak/print') ?>" target="_blank">Print</a>
        </div>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama Rak</th>
            <th>Lokasi</th>
            <th>Jumlah Buku</th>
            <th>Aksi</th>
        </tr>

        <?php if (!empty($rak)): ?>
            <?php foreach ($rak as $r): ?>
                <tr>
                    <td><?= $r['id_rak'] ?></td>
                    <td><?= $r['nama_rak'] ?></td>
                    <td><?= $r['lokasi'] ?></td>
                    <td><?= $r['total_buku'] ?? 0 ?></td>
                    <td class="aksi">
                        <a href="<?= base_url('rak/detail/' . $r['id_rak']) ?>">Detail</a>
                        <a href="<?= base_url('rak/edit/' . $r['id_rak']) ?>">Edit</a>
                        <a href="<?= base_url('rak/delete/' . $r['id_rak']) ?>"
                           onclick="return confirm('Hapus rak ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Data rak belum ada</td>
            </tr>
        <?php endif; ?>
    </table>

</div>