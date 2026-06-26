<?php

require_once __DIR__ . '/koneksi.php';
require_login();

$pageTitle = 'Dashboard';
$pageSubtitle = 'Ringkasan status perangkat jaringan laboratorium.';
$stats = get_dashboard_stats();
$devices = get_devices(8);
$recentLogs = get_recent_logs(8);
$activityChart = get_monitoring_activity_chart();
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="app-shell">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="app-content">
        <?php include __DIR__ . '/includes/navbar.php'; ?>
        <main class="main-content">
            <div class="content-grid">
                <section class="stats-grid">
                    <div class="stat-card">
                        <div><span>Total Devices</span><strong id="statTotal"><?= e($stats['total']); ?></strong></div>
                        <div class="stat-icon"><i class="bi bi-hdd-network"></i></div>
                    </div>
                    <div class="stat-card">
                        <div><span>Online Devices</span><strong id="statOnline"><?= e($stats['online']); ?></strong></div>
                        <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                    </div>
                    <div class="stat-card">
                        <div><span>Offline Devices</span><strong id="statOffline"><?= e($stats['offline']); ?></strong></div>
                        <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
                    </div>
                    <div class="stat-card">
                        <div><span>Error Devices</span><strong id="statError"><?= e($stats['error']); ?></strong></div>
                        <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
                    </div>
                </section>

                <section class="chart-grid">
                    <div class="panel">
                        <div class="panel-title"><h2>Status Perangkat</h2></div>
                        <div class="chart-box"><canvas id="statusChart"></canvas></div>
                    </div>
                    <div class="panel">
                        <div class="panel-title"><h2>Aktivitas Monitoring</h2></div>
                        <div class="chart-box"><canvas id="activityChart"></canvas></div>
                    </div>
                </section>

                <section class="chart-grid">
                    <div class="table-panel">
                        <div class="panel-title p-3 mb-0">
                            <h2>Recent Activity</h2>
                            <a class="btn btn-sm btn-outline-primary" href="logs.php">Lihat semua</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead><tr><th>Device</th><th>Status</th><th>Waktu</th></tr></thead>
                                <tbody id="recentActivityRows">
                                <?php if (empty($recentLogs)): ?>
                                    <tr><td colspan="3" class="empty-state">Belum ada perubahan status.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($recentLogs as $log): ?>
                                    <tr>
                                        <td><?= e($log['nama_device']); ?><br><small class="text-secondary"><?= e($log['ip_address']); ?></small></td>
                                        <td><?= status_badge($log['old_status']); ?> <i class="bi bi-arrow-right-short"></i> <?= status_badge($log['new_status']); ?></td>
                                        <td><?= e($log['waktu']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="table-panel">
                        <div class="panel-title p-3 mb-0">
                            <h2>Device Status</h2>
                            <a class="btn btn-sm btn-primary" href="monitoring.php">Monitoring</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead><tr><th>Device</th><th>IP Address</th><th>Status</th><th>Last Check</th></tr></thead>
                                <tbody id="dashboardDeviceRows">
                                <?php if (empty($devices)): ?>
                                    <tr><td colspan="4" class="empty-state">Belum ada perangkat.</td></tr>
                                <?php endif; ?>
                                <?php foreach ($devices as $device): ?>
                                    <tr>
                                        <td><?= e($device['nama_device']); ?></td>
                                        <td><?= e($device['ip_address']); ?></td>
                                        <td><?= status_badge($device['status']); ?></td>
                                        <td><?= e($device['last_check'] ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
            <script>
                const statusChart = new Chart(document.getElementById('statusChart'), {
                    type: 'pie',
                    data: {
                        labels: ['ONLINE', 'OFFLINE', 'ERROR'],
                        datasets: [{
                            data: [<?= (int) $stats['online']; ?>, <?= (int) $stats['offline']; ?>, <?= (int) $stats['error']; ?>],
                            backgroundColor: ['#16A34A', '#DC2626', '#F59E0B']
                        }]
                    },
                    options: {responsive: true, maintainAspectRatio: false}
                });

                const activityChart = new Chart(document.getElementById('activityChart'), {
                    type: 'line',
                    data: {
                        labels: <?= json_encode($activityChart['labels']); ?>,
                        datasets: [{
                            label: 'Perubahan status',
                            data: <?= json_encode($activityChart['values']); ?>,
                            borderColor: '#2563EB',
                            backgroundColor: 'rgba(37, 99, 235, 0.12)',
                            tension: 0.35,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {precision: 0}
                            }
                        }
                    }
                });

                function badge(status) {
                    const className = status === 'ONLINE' ? 'success' : status === 'OFFLINE' ? 'danger' : 'warning';
                    return `<span class="badge text-bg-${className}">${status}</span>`;
                }

                function escapeHtml(value) {
                    return String(value ?? '').replace(/[&<>"']/g, match => ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    }[match]));
                }

                async function refreshDashboard() {
                    const response = await fetch('check_status.php');
                    const data = await response.json();
                    document.getElementById('statTotal').textContent = data.stats.total;
                    document.getElementById('statOnline').textContent = data.stats.online;
                    document.getElementById('statOffline').textContent = data.stats.offline;
                    document.getElementById('statError').textContent = data.stats.error;
                    statusChart.data.datasets[0].data = [data.stats.online, data.stats.offline, data.stats.error];
                    statusChart.update();
                    activityChart.data.labels = data.activity.labels;
                    activityChart.data.datasets[0].data = data.activity.values;
                    activityChart.update();
                    const rows = data.devices.map(device => `
                        <tr>
                            <td>${escapeHtml(device.nama_device)}</td>
                            <td>${escapeHtml(device.ip_address)}</td>
                            <td>${badge(device.status)}</td>
                            <td>${escapeHtml(device.last_check || '-')}</td>
                        </tr>
                    `).join('');
                    document.getElementById('dashboardDeviceRows').innerHTML = rows || '<tr><td colspan="4" class="empty-state">Belum ada perangkat.</td></tr>';

                    const activityRows = data.recent_logs.map(log => `
                        <tr>
                            <td>${escapeHtml(log.nama_device)}<br><small class="text-secondary">${escapeHtml(log.ip_address)}</small></td>
                            <td>${badge(log.old_status)} <i class="bi bi-arrow-right-short"></i> ${badge(log.new_status)}</td>
                            <td>${escapeHtml(log.waktu)}</td>
                        </tr>
                    `).join('');
                    document.getElementById('recentActivityRows').innerHTML = activityRows || '<tr><td colspan="3" class="empty-state">Belum ada perubahan status.</td></tr>';
                }

                setInterval(refreshDashboard, <?= (int) MONITORING_INTERVAL_MS; ?>);
            </script>
<?php include __DIR__ . '/includes/footer.php'; ?>
