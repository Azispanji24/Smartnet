<?php

require_once __DIR__ . '/koneksi.php';
require_login();

$pageTitle = 'Settings';
$pageSubtitle = 'Kelola akun admin dan informasi konfigurasi SmartNet.';
$success = '';
$error = '';
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($newPassword !== $confirmPassword) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $result = change_user_password((int) $user['id'], $currentPassword, $newPassword);

        if ($result === true) {
            $success = 'Password admin berhasil diperbarui.';
        } else {
            $error = $result;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle); ?> - <?= e(APP_NAME); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="app-shell">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="app-content">
        <?php include __DIR__ . '/includes/navbar.php'; ?>
        <main class="main-content">
            <div class="chart-grid">
                <section class="panel">
                    <div class="panel-title"><h2>Ubah Password</h2></div>
                    <?php if ($success !== ''): ?>
                        <div class="alert alert-success"><?= e($success); ?></div>
                    <?php endif; ?>
                    <?php if ($error !== ''): ?>
                        <div class="alert alert-danger"><?= e($error); ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label" for="current_password">Password Saat Ini</label>
                            <input class="form-control" id="current_password" name="current_password" type="password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="new_password">Password Baru</label>
                            <input class="form-control" id="new_password" name="new_password" type="password" minlength="6" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="confirm_password">Konfirmasi Password</label>
                            <input class="form-control" id="confirm_password" name="confirm_password" type="password" minlength="6" required>
                        </div>
                        <button class="btn btn-primary" type="submit"><i class="bi bi-shield-lock"></i> Simpan Password</button>
                    </form>
                </section>
                <section class="panel">
                    <div class="panel-title"><h2>Konfigurasi Sistem</h2></div>
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                            <tr><th>Nama Aplikasi</th><td><?= e(APP_NAME); ?></td></tr>
                            <tr><th>Database</th><td><?= e(DB_NAME); ?></td></tr>
                            <tr><th>Interval Monitoring</th><td><?= (int) (MONITORING_INTERVAL_MS / 1000); ?> detik</td></tr>
                            <tr><th>Status Valid</th><td><?= e(implode(', ', get_statuses())); ?></td></tr>
                            <tr><th>Role Login</th><td><?= e($user['role']); ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
<?php include __DIR__ . '/includes/footer.php'; ?>
