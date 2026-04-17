<!DOCTYPE html>
<html>
<head>
    <title>Daftar User</title>
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 400px;">

        <h4 class="text-center mb-3">Daftar User</h4>

        <form method="post" action="<?= base_url('users/store') ?>">

            <div class="mb-2">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control">
            </div>

            <div class="mb-2">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="mb-2">
                <label>Username</label>
                <input type="text" name="username" class="form-control">
            </div>

            <div class="mb-2">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                    <option value="anggota">Anggota</option>
                </select>
            </div>

            <button class="btn btn-primary w-100">Daftar</button>

        </form>

        <div class="text-center mt-2">
            <a href="<?= base_url('/') ?>">Kembali ke Login</a>
        </div>

    </div>
</div>

</body>
</html>