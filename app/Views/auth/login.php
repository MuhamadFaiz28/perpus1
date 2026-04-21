<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Dadan Library</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">

    <style>
        body {
            /* Background Gradient Gelap agar efek transparan terlihat */
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Efek Kaca (Glassmorphism) */
        .glass-card {
            background: rgba(255, 255, 255, 0.1); /* Transparansi putih tipis */
            backdrop-filter: blur(15px); /* Efek blur di belakang kartu */
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            text-align: center;
            color: white;
        }

        .login-icon {
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 10px;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.9) !important; /* Input tetap putih agar mudah dibaca */
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            margin-bottom: 15px;
            color: #1e293b !important;
        }

        .btn-login {
            background: #004a99; /* Biru gelap sesuai gambar */
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px;
            width: 100%;
            font-weight: bold;
            font-size: 1.1rem;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #003366;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .forgot-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.9rem;
            display: block;
            margin-top: 15px;
        }

        .forgot-link:hover {
            color: white;
        }
    </style>
</head>
<body>

    <div class="glass-card">
        <div class="login-icon">
            <i class="bi bi-person-circle"></i>
        </div>
        <h2 class="fw-normal mb-4">User Login</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 py-2 small bg-danger text-white">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/proses-login') ?>" method="post">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            
            <a href="#" class="forgot-link mb-3">Forgot Password?</a>

            <button type="submit" class="btn btn-login shadow">Login</button>
        </form>

        <div class="mt-4">
            <p class="small mb-0">Belum punya akun? 
                <a href="<?= base_url('users/create') ?>" class="text-white fw-bold text-decoration-none">Daftar</a>
            </p>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>