<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">

    <!-- HEADER -->
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold text-dark">📚 Koleksi Buku Digital</h2>
        <p class="text-muted">Pilih dan baca buku di Dadan Library.</p>
    </div>

    <div class="col-md-6 text-md-end d-flex justify-content-md-end gap-2 flex-wrap">

        <!-- TOMBOL TAMBAH BUKU -->
        <?php if (session()->get('role') == 'admin'): ?>
            <a href="<?= base_url('buku/create') ?>" class="btn btn-success rounded-pill px-4">
                ➕ Tambah Buku
            </a>
        <?php endif; ?>

        <!-- HISTORI -->
        <a href="<?= base_url('buku/histori_download') ?>" class="btn btn-dark rounded-pill px-4">
            Panel Histori
        </a>

    </div>
</div>

    <!-- LIST BUKU -->
    <div class="row">
        <?php if (!empty($semua_buku)) : ?>
            <?php foreach ($semua_buku as $b) : ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 rounded-4">

                        <!-- COVER -->
                        <div class="bg-primary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-book" style="font-size: 4rem;"></i>
                        </div>

                        <!-- ISI -->
                        <div class="card-body text-center">
                            <h6 class="fw-bold mb-1 text-truncate">
                                <?= esc($b['judul'] ?? 'Tanpa Judul') ?>
                            </h6>

                            <p class="small text-muted mb-3">
                                Penulis: <?= esc($b['id_penulis'] ?? '-') ?>
                            </p>

                            <div class="d-grid gap-2">

                                <!-- PINJAM -->
                                <a href="<?= base_url('buku/pinjam/' . $b['id_buku']) ?>" 
                                class="btn btn-primary btn-sm rounded-pill">
                                📚 Pinjam Buku
                                </a>

                                <!-- DETAIL -->
                                <a href="<?= base_url('buku/detail/' . $b['id_buku']) ?>" 
                                class="btn btn-outline-primary btn-sm rounded-pill">
                                Detail
                                </a>

                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    Belum ada data buku.
                </div>
            </div>
        <?php endif; ?>
    </div>

    <hr class="my-5">

    <!-- HISTORI DOWNLOAD -->
    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📊 Histori Download</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>User</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($histori)) : ?>
                            <?php $no = 1; foreach ($histori as $h) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($h['judul']) ?></td>
                                    <td>User #<?= $h['id_user'] ?></td>
                                    <td><?= date('d M Y H:i', strtotime($h['waktu_download'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada histori
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>