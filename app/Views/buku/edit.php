<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
.form-card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 600px;
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
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.form-group textarea {
    resize: vertical;
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

<h3>Edit Buku</h3>

<form method="post" action="<?= base_url('buku/update/' . $buku['id_buku']) ?>" enctype="multipart/form-data">

    <div class="form-group">
        <label>Judul:</label>
        <input type="text" name="judul" value="<?= $buku['judul'] ?>">
    </div>

    <div class="form-group">
        <label>ISBN:</label>
        <input type="text" name="isbn" value="<?= $buku['isbn'] ?>">
    </div>

    <div class="form-group">
        <label>Kategori:</label>
        <select name="id_kategori">
            <?php foreach ($kategori as $k): ?>
                <option value="<?= $k['id_kategori'] ?>"
                    <?= $buku['id_kategori'] == $k['id_kategori'] ? 'selected' : '' ?>>
                    <?= $k['nama_kategori'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Penulis:</label>
        <select name="id_penulis">
            <?php foreach ($penulis as $p): ?>
                <option value="<?= $p['id_penulis'] ?>"
                    <?= $buku['id_penulis'] == $p['id_penulis'] ? 'selected' : '' ?>>
                    <?= $p['nama_penulis'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Penerbit:</label>
        <select name="id_penerbit">
            <?php foreach ($penerbit as $p): ?>
                <option value="<?= $p['id_penerbit'] ?>"
                    <?= $buku['id_penerbit'] == $p['id_penerbit'] ? 'selected' : '' ?>>
                    <?= $p['nama_penerbit'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Rak:</label>
        <select name="id_rak">
            <?php foreach ($rak as $r): ?>
                <option value="<?= $r['id_rak'] ?>"
                    <?= $buku['id_rak'] == $r['id_rak'] ? 'selected' : '' ?>>
                    <?= $r['nama_rak'] ?> - <?= $r['lokasi'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Tahun:</label>
        <input type="number" name="tahun_terbit" value="<?= $buku['tahun_terbit'] ?>">
    </div>

    <div class="form-group">
        <label>Jumlah:</label>
        <input type="number" name="jumlah" value="<?= $buku['jumlah'] ?>">
    </div>

    <div class="form-group">
        <label>Tersedia:</label>
        <input type="number" name="tersedia" value="<?= $buku['tersedia'] ?>">
    </div>

    <div class="form-group">
        <label>Deskripsi:</label>
        <textarea name="deskripsi"><?= $buku['deskripsi'] ?></textarea>
    </div>

    <div class="form-group">
        <label>Cover:</label>
        <input type="file" name="cover">
    </div>

    <div class="form-group">
        <label>Cover Saat Ini:</label>

        <?php if ($buku['cover']): ?>
            <?php $ext = pathinfo($buku['cover'], PATHINFO_EXTENSION); ?>

            <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                <img src="<?= base_url('uploads/buku/' . $buku['cover']) ?>" width="100">
            <?php else: ?>
                <a href="<?= base_url('uploads/buku/' . $buku['cover']) ?>" target="_blank">Lihat File</a>
            <?php endif; ?>
        <?php else: ?>
            -
        <?php endif; ?>
    </div>

    <button type="submit" class="btn">Update</button>
    <a href="<?= base_url('buku') ?>" class="btn btn-secondary">Kembali</a>

</form>

</div>

<?= $this->endSection() ?>