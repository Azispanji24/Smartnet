<?php

require_once __DIR__ . '/koneksi.php';
require_login();

$pageTitle = 'Logs';
$pageSubtitle = 'Riwayat perubahan status yang tersimpan hanya saat status berubah.';
$logs = get_logs();
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
            <section class="table-panel">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Waktu</th><th>Device</th><th>IP Address</th><th>Perubahan</th><th>Lokasi</th></tr></thead>
                        <tbody>
                        <?php if (empty($logs)): ?>
                            <tr><td colspan="5" class="empty-state">Belum ada log perubahan status.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= e($log['waktu']); ?></td>
                                <td><?= e($log['nama_device']); ?><br><small class="text-secondary"><?= e($log['jenis_device']); ?></small></td>
                                <td><?= e($log['ip_address']); ?></td>
                                <td><?= status_badge($log['old_status']); ?> <i class="bi bi-arrow-right-short"></i> <?= status_badge($log['new_status']); ?></td>
                                <td><?= e($log['lokasi']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
<?php include __DIR__ . '/includes/footer.php'; ?>

