<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
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

.form-group input {
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

.btn-secondary {
    background: #ccc;
}
</style>

<div class="form-card">

<h3>Edit Rak Buku</h3>

<form method="post" action="<?= base_url('rak/update/' . $rak['id_rak']) ?>">

    <div class="form-group">
        <label>Nama Rak:</label>
        <input type="text" name="nama_rak" value="<?= $rak['nama_rak'] ?>">
    </div>

    <div class="form-group">
        <label>Lokasi:</label>
        <input type="text" name="lokasi" value="<?= $rak['lokasi'] ?>">
    </div>

    <button type="submit" class="btn">Update</button>
    <a href="<?= base_url('rak') ?>" class="btn btn-secondary">Kembali</a>

</form>

</div>

<?= $this->endSection() ?>