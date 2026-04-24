<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border: none;
            border-radius: 20px;
            width: 400px;
        }
        .btn-register {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            font-weight: 600;
        }
        .form-control {
            border-radius: 10px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="card shadow-lg">
    <div class="card-body p-4">
        <div class="text-center mb-4">
            <div class="display-4 text-primary"><i class="bi bi-person-plus-fill"></i></div>
            <h4 class="fw-bold">Daftar Akun</h4>
            <p class="text-muted small">Lengkapi data untuk bergabung</p>
        </div>

        <form action="<?= base_url('users/store') ?>" method="post">
            <div class="mb-3">
                <label class="form-label small fw-bold">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                    <input type="text" name="nama" class="form-control" placeholder="Nama lengkap Anda" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-at"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Pilih username" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Buat password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-register w-100 p-2 mb-3">
                DAFTAR SEKARANG
            </button>
        </form>

        <div class="text-center">
            <span class="small text-muted">Sudah punya akun?</span><br>
            <a href="<?= base_url('login') ?>" class="small fw-bold text-decoration-none" style="color: #764ba2;">
                Masuk di Sini
            </a>
        </div>
    </div>
</div>

</body>
</html>