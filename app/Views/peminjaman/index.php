<style>
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

.btn {
    padding: 4px 8px;
    background: #d8d3a3;
    text-decoration: none;
    border-radius: 4px;
    color: #000;
    font-size: 12px;
}

.btn:hover {
    background: #bfb97a;
}
</style>

<h3>Data Peminjaman</h3>

<a href="<?= base_url('peminjaman/create') ?>" class="btn">Tambah</a>

<br><br>

<table>
    <tr>
        <th>ID Anggota</th>
        <th>Buku</th>
        <th>Tgl Pinjam</th>
        <th>Tgl Kembali</th>
        <th>Status</th>
        <th>Aksi</th>
         <th>Denda</th>
    </tr>

    <?php foreach ($peminjaman as $p): ?>
   <tr>
    <td><?= $p['id_anggota'] ?></td>
    <td><?= $p['judul'] ?></td>
    <td><?= $p['tanggal_pinjam'] ?></td>
    <td><?= $p['tanggal_kembali'] ?></td>
    <td><?= $p['status'] ?></td>

    <td>
        <?php if ($p['denda'] > 0): ?>
            Rp <?= number_format($p['denda']) ?>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
</tr>
        <!-- ✅ AKSI -->
        <td>
            <?php if ($p['status'] != 'kembali'): ?>
                <a href="<?= base_url('peminjaman/kembali/' . $p['id_peminjaman']) ?>">
                    Kembalikan
                </a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
        <?php if ($p['status'] == 'dipinjam'): ?>
    <a href="<?= base_url('peminjaman/kembali/' . $p['id_peminjaman']) ?>" 
       onclick="return confirm('Kembalikan buku ini?')" 
       class="btn">
        Kembalikan
    </a>
<?php else: ?>
    <span style="color:green;">Selesai</span>
<?php endif; ?>
</table>