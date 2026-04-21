<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    /* Styling tetap sama dengan milik Anda, hanya ditambahkan sedikit perbaikan box-sizing */
    .form-header {
        background: linear-gradient(135deg, #d8d3a3 0%, #bfb97a 100%);
        padding: 20px;
        border-radius: 10px 10px 0 0;
        color: #333;
        margin: -20px -20px 20px -20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .form-card {
        background: #fff;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        max-width: 750px;
        margin: 30px auto;
        border-top: 5px solid #d8d3a3;
    }

    .form-group { margin-bottom: 18px; }
    .form-group label { 
        display: block; 
        font-weight: 600; 
        margin-bottom: 8px; 
        color: #555;
        font-size: 0.9rem;
    }

    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #eee;
        border-radius: 8px;
        transition: all 0.3s;
        background: #f9f9f9;
    }

    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color: #d8d3a3;
        background: #fff;
        outline: none;
        box-shadow: 0 0 8px rgba(216, 211, 163, 0.4);
    }

    .image-preview-container {
        display: flex;
        align-items: center;
        gap: 20px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        border: 1px dashed #ccc;
        margin-top: 10px;
    }

    .current-cover {
        width: 100px;
        height: 140px;
        object-fit: cover;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .btn-container { display: flex; gap: 10px; margin-top: 25px; }
    .btn-update { flex: 2; background: #d8d3a3; color: #333; font-weight: bold; padding: 12px; border: none; border-radius: 8px; cursor: pointer; transition: 0.3s; }
    .btn-update:hover { background: #bfb97a; transform: translateY(-2px); }
    .btn-back { flex: 1; background: #6c757d; color: white; text-align: center; text-decoration: none; padding: 12px; border-radius: 8px; font-weight: bold; transition: 0.3s; }
</style>

<div class="form-card">
    <div class="form-header">
        <div style="font-size: 2.5rem;">📚</div>
        <div>
            <h3 style="margin: 0;">Edit Data Buku</h3>
            <small style="opacity: 0.8;">ID Buku: #<?= $buku['id_buku'] ?></small>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('buku/update/' . $buku['id_buku']) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Judul Lengkap Buku</label>
            <input type="text" name="judul" value="<?= old('judul', $buku['judul']) ?>" required>
        </div>

        <div class="row">
            <div class="col-md-6 form-group">
                <label>ISBN</label>
                <input type="text" name="isbn" value="<?= old('isbn', $buku['isbn']) ?>">
            </div>
            <div class="col-md-6 form-group">
                <label>Kategori</label>
                <select name="id_kategori" required>
                    <?php foreach ($kategori as $k) : ?>
                        <option value="<?= $k['id_kategori'] ?>" <?= ($k['id_kategori'] == $buku['id_kategori']) ? 'selected' : '' ?>>
                            <?= $k['nama_kategori'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

       <div class="form-group">
    <label>Penulis</label>
    <select name="id_penulis" required>
        <option value="">-- Pilih Penulis --</option>
        <?php foreach ($penulis as $p): ?> <option value="<?= $p['id_penulis'] ?>" <?= $buku['id_penulis'] == $p['id_penulis'] ? 'selected' : '' ?>>
                <?= $p['nama_penulis'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Penerbit</label>
                <select name="id_penerbit" required>
                    <?php foreach ($penerbit as $p): ?>
                        <option value="<?= $p['id_penerbit'] ?>" <?= $buku['id_penerbit'] == $p['id_penerbit'] ? 'selected' : '' ?>>
                            <?= $p['nama_penerbit'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 form-group">
                <label>Lokasi Rak</label>
                <select name="id_rak" required>
                    <?php foreach ($rak as $r): ?>
                        <option value="<?= $r['id_rak'] ?>" <?= $buku['id_rak'] == $r['id_rak'] ? 'selected' : '' ?>>
                            <?= $r['nama_rak'] ?> (<?= $r['lokasi'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 form-group">
                <label>Tahun Terbit</label>
                <input type="number" name="tahun_terbit" value="<?= old('tahun_terbit', $buku['tahun_terbit']) ?>">
            </div>
            <div class="col-md-4 form-group">
                <label>Total Stok</label>
                <input type="number" name="jumlah" value="<?= old('jumlah', $buku['jumlah']) ?>" required>
            </div>
            <div class="col-md-4 form-group">
                <label>Tersedia</label>
                <input type="number" name="tersedia" value="<?= old('tersedia', $buku['tersedia']) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi Buku</label>
            <textarea name="deskripsi" rows="4"><?= old('deskripsi', $buku['deskripsi']) ?></textarea>
        </div>

        <div class="form-group">
            <label>Cover Buku</label>
            <div class="image-preview-container">
                <div class="text-center">
                    <?php 
                        $path = 'uploads/buku/' . $buku['cover'];
                        $imgSrc = (!empty($buku['cover']) && file_exists($path)) ? base_url($path) : base_url('uploads/buku/default.jpg');
                    ?>
                    <img src="<?= $imgSrc ?>" class="current-cover" id="previewImage">
                </div>
                <div style="flex: 1;">
                    <input type="file" name="cover" accept="image/*" onchange="previewFile(this)">
                    <p style="font-size: 0.75rem; color: #999; margin-top: 10px;">
                        * Format: JPG, PNG, WEBP (Maks. 2MB).<br>
                        * Kosongkan jika tidak ingin mengubah cover.
                    </p>
                </div>
            </div>
        </div>

        <div class="btn-container">
            <button type="submit" class="btn-update">💾 Simpan Perubahan</button>
            <a href="<?= base_url('buku') ?>" class="btn-back">Batal</a>
        </div>
    </form>
</div>

<script>
    function previewFile(input) {
        const preview = document.getElementById('previewImage');
        const file = input.files[0];
        const reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

<?= $this->endSection() ?>