<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Jago Maca App</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Segoe UI', sans-serif; }
        .card { border: none; border-radius: 20px; width: 400px; }
        .btn-daftar { background: linear-gradient(to right, #667eea, #764ba2); border: none; border-radius: 10px; padding: 12px; color: white; font-weight: 600; width: 100%; }
    </style>
</head>
<body>
    <div class="card shadow-lg">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="bi bi-person-plus-fill" style="font-size: 3rem; color: #764ba2;"></i>
                <h4 class="fw-bold text-dark">Daftar Anggota</h4>
                <p class="text-muted small">Silakan lengkapi data diri Anda</p>
            </div>

            <form action="<?= base_url('proses-register') ?>" method="post">
                <div class="mb-3">
                    <label class="small fw-bold text-secondary">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Nama lengkap Anda" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold text-secondary">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Buat username" required>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold text-secondary">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Buat password" required>
                </div>
                <button type="submit" class="btn-daftar mb-3">DAFTAR SEKARANG</button>
            </form>

            <div class="text-center">
                <a href="<?= base_url('login') ?>" class="text-decoration-none small fw-bold" style="color: #764ba2;">Sudah punya akun? Login</a>
            </div>
        </div>
    </div>
</body>
</html>