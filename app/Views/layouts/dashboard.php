<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-1">Halo, 👋</h3>
            <small class="text-muted">
                Siap membaca di <span class="fw-semibold text-primary">Perpus</span> hari ini?
            </small>
        </div>

        <div class="px-3 py-2 rounded-3 shadow-sm bg-light border">
            <i class="bi bi-calendar3 me-1 text-primary"></i>
            <?= date('d M Y') ?>
            <span class="mx-2">|</span>
            <i class="bi bi-clock text-primary"></i>
            <span id="clock"></span>
        </div>
    </div>

    <div class="row g-4">

        <!-- LEFT -->
        <div class="col-lg-8">

            <!-- SEARCH CARD -->
            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">🔍 Cari Buku</h6>

                    <form action="<?= base_url('buku/cari') ?>" method="get">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="keyword" 
                                class="form-control border-start-0"
                                placeholder="Judul, penulis, genre..." required>

                            <button class="btn btn-primary px-4">
                                Cari
                            </button>
                        </div>
                    </form>

                    <!-- TOMBOL LIHAT BUKU -->
                    <div class="mt-3">
                        <a href="<?= base_url('buku') ?>" class="btn btn-outline-primary rounded-pill px-4">
                            📚 Lihat Semua Buku
                        </a>
                    </div>

                </div>
            </div>

            <!-- INFO CARD (KLIKABLE) -->
            <a href="<?= base_url('buku') ?>" style="text-decoration: none;">
                <div class="card mt-4 border-0 shadow rounded-4 bg-primary text-white hover-card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">📚 Lihat Daftar Buku</h5>
                            <small class="opacity-75">
                                Klik untuk melihat semua koleksi buku
                            </small>
                        </div>
                        <i class="bi bi-book display-5 opacity-50"></i>
                    </div>
                </div>
            </a>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-4">
            
            <!-- QUOTE -->
            <div class="mt-4 p-3 rounded-4 bg-light border-start border-4 border-primary">
                <small class="fst-italic text-dark">
                    nothing is imposible
                </small>
                <div class="text-end mt-1">
                    <small class="text-muted">- Muhamad Faiz</small>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- STYLE -->
<style>
.card {
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-3px);
}

.hover-card {
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-5px) scale(1.01);
    box-shadow: 0 12px 25px rgba(0,0,0,0.2);
}
</style>

<!-- CLOCK -->
<script>
function updateClock() {
    const now = new Date();
    document.getElementById('clock').textContent =
        now.toLocaleTimeString('id-ID', { hour12: false });
}
setInterval(updateClock, 1000);
updateClock();
</script>

<?= $this->endSection() ?>