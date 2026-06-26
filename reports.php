<?php

require_once __DIR__ . '/koneksi.php';
require_login();

$pageTitle = 'Reports';
$pageSubtitle = 'Filter riwayat monitoring dan export laporan PDF.';
$startDate = trim($_GET['start_date'] ?? '');
$endDate = trim($_GET['end_date'] ?? '');
$logs = get_logs($startDate, $endDate);

if (isset($_GET['export']) && $_GET['export'] === 'pdf') {
    output_report_pdf($logs, $startDate, $endDate);
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
            <section class="panel mb-3 no-print">
                <form class="row g-3 align-items-end" method="get">
                    <div class="col-md-4">
                        <label class="form-label" for="start_date">Tanggal Mulai</label>
                        <input class="form-control" id="start_date" name="start_date" type="date" value="<?= e($startDate); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="end_date">Tanggal Selesai</label>
                        <input class="form-control" id="end_date" name="end_date" type="date" value="<?= e($endDate); ?>">
                    </div>
                    <div class="col-md-4 d-flex gap-2 flex-wrap">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-funnel"></i> Filter</button>
                        <a class="btn btn-outline-secondary" href="reports.php">Reset</a>
                        <button class="btn btn-outline-primary" name="export" value="pdf" type="submit"><i class="bi bi-file-earmark-pdf"></i> Export PDF</button>
                    </div>
                </form>
            </section>
            <section class="table-panel">
                <div class="panel-title p-3 mb-0">
                    <h2>Laporan Monitoring</h2>
                    <span class="text-secondary"><?= count($logs); ?> log ditemukan</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th>Waktu</th><th>Device</th><th>IP Address</th><th>Jenis</th><th>Perubahan</th><th>Lokasi</th></tr></thead>
                        <tbody>
                        <?php if (empty($logs)): ?>
                            <tr><td colspan="6" class="empty-state">Tidak ada data pada periode ini.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td><?= e($log['waktu']); ?></td>
                                <td><?= e($log['nama_device']); ?></td>
                                <td><?= e($log['ip_address']); ?></td>
                                <td><?= e($log['jenis_device']); ?></td>
                                <td><?= status_badge($log['old_status']); ?> <i class="bi bi-arrow-right-short"></i> <?= status_badge($log['new_status']); ?></td>
                                <td><?= e($log['lokasi']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
<?php include __DIR__ . '/includes/footer.php'; ?>

