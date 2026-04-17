<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
.dashboard-card {
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.dashboard-title {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 10px;
}

.dashboard-text {
    font-size: 14px;
    color: #555;
}
</style>

<div class="container mt-2">
    <div class="dashboard-card">
        <div class="dashboard-title">
            Dashboard
        </div>

        <div class="dashboard-text">
            Ini adalah Halaman Dashboard <br>
            Selamat datang di <b>Maldin17</b>App!
        </div>
    </div>
</div>

<?= $this->endSection() ?>