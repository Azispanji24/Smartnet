<?php

require_once __DIR__ . '/koneksi.php';
require_login();

$pageTitle = 'Devices';
$pageSubtitle = 'Kelola perangkat jaringan dan IP Address untuk monitoring.';
$devices = get_devices();
$message = $_GET['message'] ?? '';
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
            <div class="action-bar">
                <div></div>
                <a class="btn btn-primary" href="add_device.php"><i class="bi bi-plus-circle"></i> Tambah Device</a>
            </div>
            <?php if ($message !== ''): ?>
                <div class="alert alert-success"><?= e($message); ?></div>
            <?php endif; ?>
            <section class="table-panel">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Nama Device</th>
                            <th>IP Address</th>
                            <th>Jenis</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($devices)): ?>
                            <tr><td colspan="6" class="empty-state">Belum ada perangkat yang terdaftar.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($devices as $device): ?>
                            <tr>
                                <td><?= e($device['nama_device']); ?></td>
                                <td><?= e($device['ip_address']); ?></td>
                                <td><?= e($device['jenis_device']); ?></td>
                                <td><?= e($device['lokasi']); ?></td>
                                <td><?= status_badge($device['status']); ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="edit_device.php?id=<?= (int) $device['id']; ?>"><i class="bi bi-pencil-square"></i></a>
                                    <a class="btn btn-sm btn-outline-danger" href="delete_device.php?id=<?= (int) $device['id']; ?>" data-confirm="Hapus perangkat ini?"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
<?php include __DIR__ . '/includes/footer.php'; ?>

