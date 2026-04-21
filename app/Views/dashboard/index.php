<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
.dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.card-box {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.card-box h2 {
    margin: 0;
    font-size: 28px;
    color: #333;
}

.card-box p {
    margin: 5px 0;
    color: #777;
}

.menu-box {
    margin-top: 20px;
    display: flex;
    gap: 10px;
}

.menu-box a {
    padding: 8px 12px;
    background: #d8d3a3;
    text-decoration: none;
    border-radius: 5px;
    color: #000;
}
<?php
$bulan = isset($bulan) ? $bulan : [];
$total = isset($total) ? $total : [];
?>
</style>
<h3>Dashboard Perpustakaan</h3>

<div class="dashboard">

    <div class="card-box">
        <h2><?= $total_buku ?></h2>
        <p>Total Buku</p>
    </div>

    <div class="card-box">
        <h2><?= $total_rak ?></h2>
        <p>Total Rak</p>
    </div>

    <div class="card-box">
        <h2><?= $total_anggota ?></h2>
        <p>Total Anggota</p>
    </div>

    <div class="card-box">
        <h2><?= $total_peminjaman ?></h2>
        <p>Total Peminjaman</p>
    </div>

    <div class="card-box">
        <h2><?= $dipinjam ?></h2>
        <p>Sedang Dipinjam</p>
    </div>

    <div class="card-box">
        <h2><?= $kembali ?></h2>
        <p>Sudah Kembali</p>
    </div>

    <div class="card-box">
        <h2><?= $terlambat ?></h2>
        <p>Terlambat</p>
    </div>

   <canvas id="chartPeminjaman"></canvas>

<script>
const bulan = <?= json_encode($bulan) ?>;
<canvas id="chartPeminjaman"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const bulan = <?= json_encode($bulan) ?>;
const total = <?= json_encode($total) ?>;

<canvas id="chartPeminjaman"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const bulan = <?= json_encode($bulan) ?>;
const total = <?= json_encode($total) ?>;

new Chart(document.getElementById('chartPeminjaman'), {
    type: 'bar',
    data: {
        labels: bulan,
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: total,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

</div>

<div class="menu-box">
    <a href="<?= base_url('buku') ?>">Data Buku</a>
    <a href="<?= base_url('rak') ?>">Data Rak</a>
    <a href="<?= base_url('peminjaman') ?>">Peminjaman</a>
</div>

<?= $this->endSection() ?>