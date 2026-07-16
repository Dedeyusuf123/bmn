<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = mysqli_prepare($conn, 'SELECT id, username, password, role FROM `user` WHERE username = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    $passwordValid = false;
    if ($user) {
        $dbPassword = (string) $user['password'];
        $passwordValid = hash_equals($dbPassword, $password) || password_verify($password, $dbPassword);
    }

    if ($user && $passwordValid) {
        $_SESSION['login'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = strtolower((string) ($user['role'] ?? 'admin'));

        header('Location: ' . home_url());
        exit;
    }

    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login BMN Bawaslu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background:#f5f6fa;
        }

        .login-box{
            width:430px;
            max-width:92%;
            margin:auto;
            margin-top:55px;
        }

        .brand-logo{
            max-width:100%;
            height:auto;
        }

        .akun-demo{
            font-size:13px;
            background:#fff5f5;
            border:1px solid #ffd6d6;
            border-radius:10px;
            padding:10px 12px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="card shadow border-0">
            <div class="card-header bg-danger text-white text-center">
                <img src="<?= e(asset_url('images/Logo.png')) ?>" width="280" class="brand-logo mb-2" alt="Logo">
                <h3 class="mb-0">SISTEM BMN BAWASLU</h3>
            </div>

            <div class="card-body">
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger py-2"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" name="login" class="btn btn-danger w-100">
                        Login
                    </button>
                </form>

                <!-- <div class="akun-demo mt-3">
                    <strong>Akun default:</strong><br>
                    Admin: <code>admin</code> / <code>admin</code><br>
                    Pegawai: <code>pegawai</code> / <code>pegawai</code><br>
                    Pimpinan: <code>pimpinan</code> / <code>pimpinan</code>
                </div> -->
            </div>
        </div>
    </div>
</body>
</html>
