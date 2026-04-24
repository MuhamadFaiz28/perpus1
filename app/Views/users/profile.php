<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
    .profile-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .profile-header {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        height: 120px;
    }
    .profile-img-container {
        margin-top: -60px;
        margin-bottom: 15px;
    }
    .profile-img-container img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 5px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .info-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #94a3b8;
        font-weight: 700;
    }
    .info-value {
        font-weight: 600;
        color: #1e293b;
        font-size: 1.1rem;
    }
    .timeline-small {
    position: relative;
    padding-left: 5px;
    }
    .timeline-small::before {
        content: "";
        position: absolute;
        left: 10px;
        top: 5px;
        bottom: 5px;
        width: 2px;
        background: #f1f5f9;
    }
    .timeline-small div {
        position: relative;
        z-index: 1;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card profile-card">
                <div class="profile-header"></div>
                <div class="card-body text-center p-4">
                    <div class="profile-img-container">
                        <img src="<?= base_url('uploads/users/' . ($user['foto'] ?: 'default.png')) ?>" class="rounded-circle">
                    </div>
                    
                    <h3 class="fw-bold mb-0"><?= esc($user['nama']) ?></h3>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-4">
                        <i class="bi bi-shield-check me-1"></i> <?= strtoupper($user['role']) ?>
                    </span>

                    <hr class="opacity-10">

                    <div class="row text-start mt-4">
                        <div class="col-6 mb-4">
                            <p class="info-label mb-1">Username</p>
                            <p class="info-value"><?= esc($user['username']) ?></p>
                        </div>
                        <div class="col-6 mb-4">
                            <p class="info-label mb-1">Nomor HP</p>
                            <p class="info-value"><?= esc($user['no_hp'] ?? '-') ?></p>
                        </div>
                        <div class="col-12 mb-4">
                            <p class="info-label mb-1">Status Akun</p>
                            <p class="info-value text-success"><i class="bi bi-check-circle-fill me-1"></i> Aktif</p>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-2">
                        <a href="<?= base_url('users/edit/' . $user['id']) ?>" class="btn btn-primary rounded-pill py-2 fw-bold">
                            <i class="bi bi-pencil-square me-2"></i> Edit Profil
                        </a>
                        <a href="<?= base_url('/') ?>" class="btn btn-light rounded-pill py-2">Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>