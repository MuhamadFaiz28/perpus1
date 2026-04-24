<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Paos28App</title>

    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">

    <style>
        body {
            /* Background gradient yang modern agar senada dengan dashboard */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            padding: 30px;
            background: #fff !important;
            border-bottom: none;
        }

        .login-icon {
            font-size: 3rem;
            color: #764ba2;
            margin-bottom: 10px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #eee;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(118, 75, 162, 0.25);
            border-color: #764ba2;
            background-color: #fff;
        }

        .btn-login {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            opacity: 0.9;
            box-shadow: 0 4px 15px rgba(118, 75, 162, 0.4);
        }

        .alert {
            border-radius: 12px;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>

    <div class="container login-container">
        <div class="card shadow-lg" style="width: 400px;">
            
            <div class="card-header text-center">
                <div class="login-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h4 class="fw-bold text-dark mb-0">Welcome Back</h4>
                <p class="text-muted small">Silakan masuk ke akun Anda</p>
            </div>

            <div class="card-body px-4 pb-4">

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger border-0 shadow-sm">
                        <i class="bi bi-exclamation-circle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('salahpw')): ?>
                    <div class="alert alert-danger border-0 shadow-sm">
                        <i class="bi bi-shield-lock-fill me-2"></i> <?= session()->getFlashdata('salahpw') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/proses-login') ?>" method="post">

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;">
                                <i class="bi bi-person text-muted"></i>
                            </span>
                            <input type="text" name="username" class="form-control border-start-0" 
                                   placeholder="Masukkan username" required style="border-radius: 0 10px 10px 0;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-secondary">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px;">
                                <i class="bi bi-lock text-muted"></i>
                            </span>
                            <input type="password" name="password" class="form-control border-start-0" 
                                   placeholder="Masukkan password" required style="border-radius: 0 10px 10px 0;">
                        </div>
                    </div>

                    <button class="btn btn-primary btn-login w-100 mb-3">
                        SIGN IN <i class="bi bi-arrow-right-short ms-1"></i>
                    </button>

                </form>

                <div class="text-center mt-2">
                    <span class="text-muted small">Belum punya akun?</span><br>
                    <a href="<?= base_url('users/create') ?>" class="text-decoration-none fw-bold small" style="color: #764ba2;">
                         Daftar Baru Sekarang
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>