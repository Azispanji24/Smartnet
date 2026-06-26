<?php

require_once __DIR__ . '/koneksi.php';

ensure_default_admin();

if (is_logged_in()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } elseif (authenticate_user($username, $password)) {
        redirect('dashboard.php');
    } else {
        $error = 'Username atau password tidak sesuai.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - <?= e(APP_NAME); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="login-page">
    <section class="login-visual">
        <div class="brand text-white p-0">
            <span class="brand-icon bg-white text-primary"><i class="bi bi-router-fill"></i></span>
            <span>
                <strong><?= e(APP_NAME); ?></strong>
                <small class="text-white-50">Monitoring Jaringan Laboratorium</small>
            </span>
        </div>
        <div>
            <h1>Kontrol status jaringan lab secara real-time.</h1>
            <p>Login admin untuk memantau perangkat, mencatat gangguan, dan membaca riwayat monitoring dari IP Address yang tersimpan.</p>
        </div>
    </section>
    <main class="login-panel">
        <div class="login-card">
            <div class="mb-4">
                <h2 class="fw-bold mb-1">Admin Login</h2>
                <p class="text-secondary mb-0">Masuk untuk membuka dashboard SmartNet.</p>
            </div>
            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= e($error); ?></div>
            <?php endif; ?>
            <form method="post" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label" for="username">Username</label>
                    <input class="form-control" id="username" name="username" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" id="password" name="password" type="password" required>
                </div>
                <button class="btn btn-primary w-100" type="submit">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Login
                </button>
            </form>
            <p class="small text-secondary mt-4 mb-0"></p>
        </div>
    </main>
</div>
</body>
</html>

