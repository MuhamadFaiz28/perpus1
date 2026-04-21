<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
.form-card {
    max-width: 400px;
    background: #fff;
    padding: 15px;
    border-radius: 8px;
}

.form-group {
    margin-bottom: 10px;
}

.form-group input {
    width: 100%;
    padding: 6px;
}

.btn {
    padding: 6px 10px;
    background: #d8d3a3;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    color: #000;
    cursor: pointer;
}
</style>

<div class="form-card">

    <h3>Tambah Rak Buku</h3>

    <!-- 🔥 FIX DI SINI (novalidate) -->
    <form method="post" action="<?= base_url('rak/store') ?>" novalidate>

        <div class="form-group">
            <label>Nama Rak:</label>
            <input type="text" name="nama_rak">
        </div>

        <div class="form-group">
            <label>Lokasi:</label>
            <input type="text" name="lokasi">
        </div>

        <button type="submit" class="btn">Simpan</button>
        <a href="<?= base_url('rak') ?>" class="btn">Kembali</a>

    </form>

</div>

<?= $this->endSection() ?>  