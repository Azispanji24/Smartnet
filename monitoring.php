<?php

require_once __DIR__ . '/koneksi.php';
require_login();

$pageTitle = 'Monitoring';
$pageSubtitle = 'Status real-time perangkat diperbarui otomatis setiap 10 detik.';
$devices = get_devices();
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
                <div class="text-secondary">
                    <i class="bi bi-arrow-repeat"></i>
                    <span id="lastRefresh">Menunggu refresh otomatis</span>
                </div>
                <button class="btn btn-primary" type="button" id="refreshStatus">
                    <i class="bi bi-arrow-clockwise"></i>
                    Refresh Status
                </button>
            </div>
            <section class="table-panel">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Device Name</th>
                            <th>IP Address</th>
                            <th>Device Type</th>
                            <th>Status</th>
                            <th>Last Check</th>
                            <th>Detail</th>
                        </tr>
                        </thead>
                        <tbody id="monitoringRows">
                        <?php if (empty($devices)): ?>
                            <tr><td colspan="6" class="empty-state">Belum ada perangkat untuk dimonitor.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($devices as $device): ?>
                            <tr>
                                <td><?= e($device['nama_device']); ?></td>
                                <td><?= e($device['ip_address']); ?></td>
                                <td><?= e($device['jenis_device']); ?></td>
                                <td><?= status_badge($device['status']); ?></td>
                                <td><?= e($device['last_check'] ?? '-'); ?></td>
                                <td><?= e($device['lokasi']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <script>
                const monitoringRows = document.getElementById('monitoringRows');
                const lastRefresh = document.getElementById('lastRefresh');
                const refreshButton = document.getElementById('refreshStatus');

                function escapeHtml(value) {
                    return String(value ?? '').replace(/[&<>"']/g, match => ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    }[match]));
                }

                function badge(status) {
                    const className = status === 'ONLINE' ? 'success' : status === 'OFFLINE' ? 'danger' : 'warning';
                    return `<span class="badge text-bg-${className}">${escapeHtml(status)}</span>`;
                }

                async function refreshMonitoring() {
                    refreshButton.disabled = true;
                    try {
                        const response = await fetch('check_status.php');
                        const data = await response.json();
                        const rows = data.devices.map(device => `
                            <tr>
                                <td>${escapeHtml(device.nama_device)}</td>
                                <td>${escapeHtml(device.ip_address)}</td>
                                <td>${escapeHtml(device.jenis_device)}</td>
                                <td>${badge(device.status)}</td>
                                <td>${escapeHtml(device.last_check || '-')}</td>
                                <td>${escapeHtml(device.lokasi)}</td>
                            </tr>
                        `).join('');
                        monitoringRows.innerHTML = rows || '<tr><td colspan="6" class="empty-state">Belum ada perangkat untuk dimonitor.</td></tr>';
                        lastRefresh.textContent = `Terakhir dicek: ${data.checked_at}`;
                    } finally {
                        refreshButton.disabled = false;
                    }
                }

                refreshButton.addEventListener('click', refreshMonitoring);
                setInterval(refreshMonitoring, <?= (int) MONITORING_INTERVAL_MS; ?>);
            </script>
<?php include __DIR__ . '/includes/footer.php'; ?>

