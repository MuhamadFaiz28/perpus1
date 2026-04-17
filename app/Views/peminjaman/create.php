<style>
    <?php if (session()->getFlashdata('error')): ?>
    <div style="color:red;">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>
.form-card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 500px;
}

.form-group {
    margin-bottom: 12px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 4px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.btn {
    padding: 6px 12px;
    background: #d8d3a3;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    color: #000;
    cursor: pointer;
}

.btn:hover {
    background: #bfb97a;
}
</style>

<div class="form-card">

<h3>Tambah Peminjaman</h3>

<form method="post" action="<?= base_url('peminjaman/store') ?>">

    <div class="form-group">
        <label>ID Anggota:</label>
        <input type="number" name="id_anggota" required>
    </div>

    <div class="form-group">
        <label>Buku:</label>
        <select name="id_buku">
            <?php foreach ($buku as $b): ?>
                <option value="<?= $b['id_buku'] ?>">
                    <?= $b['judul'] ?> (Stok: <?= $b['tersedia'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Tanggal Kembali:</label>
        <input type="date" name="tanggal_kembali" required>
    </div>

    <button type="submit" class="btn">Simpan</button>
    <a href="<?= base_url('peminjaman') ?>" class="btn">Kembali</a>

</form>

</div>