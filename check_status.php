<?php

require_once __DIR__ . '/koneksi.php';
require_login();

header('Content-Type: application/json');

$updatedDevices = update_all_device_statuses();
$stats = get_dashboard_stats();
$devices = get_devices();
$activity = get_monitoring_activity_chart();
$recentLogs = get_recent_logs(8);

echo json_encode([
    'success' => true,
    'checked_at' => date('Y-m-d H:i:s'),
    'stats' => $stats,
    'updated' => $updatedDevices,
    'devices' => $devices,
    'activity' => $activity,
    'recent_logs' => $recentLogs,
]);
