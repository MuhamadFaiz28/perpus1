<!DOCTYPE html>
<html>
<head>
    <title>Daftar Akun</title>
    </head>
<body>
    <div class="container mt-5">
        <h2>Form Pendaftaran</h2>
        <form action="<?= base_url('auth/save_daftar') ?>" method="post">
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
            <a href="<?= base_url('login') ?>">Sudah punya akun? Login</a>
        </form>
    </div>
</body>
</html>