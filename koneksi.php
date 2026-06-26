<?php

require_once __DIR__ . '/config/app.php';

if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    $sessionPath = session_save_path();

    if ($sessionPath !== '' && (!is_dir($sessionPath) || !is_writable($sessionPath))) {
        $fallbackSessionPath = sys_get_temp_dir();

        if (is_dir($fallbackSessionPath) && is_writable($fallbackSessionPath)) {
            session_save_path($fallbackSessionPath);
        }
    }

    session_start();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $error) {
    http_response_code(500);
    exit('Koneksi database gagal. Pastikan database smartnet_db sudah dibuat dan konfigurasi benar.');
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function app_url($path = '')
{
    $base = rtrim(APP_BASE_URL, '/');
    $path = ltrim($path, '/');
    return $base . ($path !== '' ? '/' . $path : '');
}

function redirect($path)
{
    header('Location: ' . app_url($path));
    exit;
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

function current_user()
{
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? '',
        'role' => $_SESSION['role'] ?? '',
    ];
}

function db_statement($sql, $params = [], $types = '')
{
    global $conn;

    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        if ($types === '') {
            foreach ($params as $param) {
                $types .= is_int($param) ? 'i' : 's';
            }
        }

        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt;
}

function db_fetch($sql, $params = [], $types = '')
{
    $stmt = db_statement($sql, $params, $types);
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function db_fetch_all($sql, $params = [], $types = '')
{
    $stmt = db_statement($sql, $params, $types);
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function db_execute($sql, $params = [], $types = '')
{
    $stmt = db_statement($sql, $params, $types);
    return $stmt->affected_rows;
}

function ensure_default_admin()
{
    $row = db_fetch('SELECT COUNT(*) AS total FROM users');

    if ((int) $row['total'] === 0) {
        db_execute(
            'INSERT INTO users (username, password, role) VALUES (?, ?, ?)',
            [DEFAULT_ADMIN_USERNAME, password_hash(DEFAULT_ADMIN_PASSWORD, PASSWORD_DEFAULT), DEFAULT_ADMIN_ROLE]
        );
    }
}

function authenticate_user($username, $password)
{
    $user = db_fetch('SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1', [$username]);

    if (!$user || !password_verify($password, $user['password'])) {
        return false;
    }

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    return true;
}

function change_user_password($userId, $currentPassword, $newPassword)
{
    $user = db_fetch('SELECT password FROM users WHERE id = ? LIMIT 1', [(int) $userId], 'i');

    if (!$user || !password_verify($currentPassword, $user['password'])) {
        return 'Password saat ini tidak sesuai.';
    }

    if (strlen($newPassword) < 6) {
        return 'Password baru minimal 6 karakter.';
    }

    db_execute(
        'UPDATE users SET password = ? WHERE id = ?',
        [password_hash($newPassword, PASSWORD_DEFAULT), (int) $userId],
        'si'
    );

    return true;
}

function get_device_types()
{
    return ['Router', 'Switch', 'Server', 'Printer', 'PC', 'Laptop', 'Access Point', 'Other'];
}

function get_statuses()
{
    return ['ONLINE', 'OFFLINE', 'ERROR'];
}

function validate_ip_address($ipAddress)
{
    return filter_var($ipAddress, FILTER_VALIDATE_IP) !== false;
}

function get_dashboard_stats()
{
    $stats = [
        'total' => 0,
        'online' => 0,
        'offline' => 0,
        'error' => 0,
    ];

    $rows = db_fetch_all('SELECT status, COUNT(*) AS total FROM devices GROUP BY status');

    foreach ($rows as $row) {
        $key = strtolower($row['status']);
        $stats[$key] = (int) $row['total'];
        $stats['total'] += (int) $row['total'];
    }

    return $stats;
}

function get_devices($limit = null)
{
    $sql = 'SELECT id, nama_device, ip_address, jenis_device, lokasi, status, last_check, created_at FROM devices ORDER BY nama_device ASC';

    if ($limit !== null) {
        $sql .= ' LIMIT ?';
        return db_fetch_all($sql, [(int) $limit], 'i');
    }

    return db_fetch_all($sql);
}

function get_device_by_id($id)
{
    return db_fetch('SELECT id, nama_device, ip_address, jenis_device, lokasi, status, last_check, created_at FROM devices WHERE id = ? LIMIT 1', [(int) $id], 'i');
}

function create_device($namaDevice, $ipAddress, $jenisDevice, $lokasi)
{
    return db_execute(
        'INSERT INTO devices (nama_device, ip_address, jenis_device, lokasi, status) VALUES (?, ?, ?, ?, ?)',
        [$namaDevice, $ipAddress, $jenisDevice, $lokasi, 'OFFLINE']
    );
}

function update_device($id, $namaDevice, $ipAddress, $jenisDevice, $lokasi)
{
    return db_execute(
        'UPDATE devices SET nama_device = ?, ip_address = ?, jenis_device = ?, lokasi = ? WHERE id = ?',
        [$namaDevice, $ipAddress, $jenisDevice, $lokasi, (int) $id],
        'ssssi'
    );
}

function delete_device($id)
{
    return db_execute('DELETE FROM devices WHERE id = ?', [(int) $id], 'i');
}

function ping_device($ipAddress)
{
    if (!validate_ip_address($ipAddress)) {
        return 'ERROR';
    }

    $command = 'ping -n 1 ' . escapeshellarg($ipAddress) . ' 2>&1';
    $output = [];
    $exitCode = 1;
    exec($command, $output, $exitCode);
    $response = strtoupper(implode("\n", $output));

    if (strpos($response, 'TTL=') !== false) {
        return 'ONLINE';
    }

    if (
        strpos($response, 'REQUEST TIMED OUT') !== false ||
        strpos($response, 'DESTINATION HOST UNREACHABLE') !== false ||
        strpos($response, '100% LOSS') !== false
    ) {
        return 'OFFLINE';
    }

    return 'ERROR';
}

function update_device_status($device)
{
    $newStatus = ping_device($device['ip_address']);
    $oldStatus = $device['status'];

    db_execute(
        'UPDATE devices SET status = ?, last_check = NOW() WHERE id = ?',
        [$newStatus, (int) $device['id']],
        'si'
    );

    if ($oldStatus !== $newStatus) {
        db_execute(
            'INSERT INTO monitor_logs (device_id, old_status, new_status) VALUES (?, ?, ?)',
            [(int) $device['id'], $oldStatus, $newStatus],
            'iss'
        );
    }

    return [
        'id' => (int) $device['id'],
        'nama_device' => $device['nama_device'],
        'ip_address' => $device['ip_address'],
        'jenis_device' => $device['jenis_device'],
        'lokasi' => $device['lokasi'],
        'old_status' => $oldStatus,
        'status' => $newStatus,
        'changed' => $oldStatus !== $newStatus,
        'last_check' => date('Y-m-d H:i:s'),
    ];
}

function update_all_device_statuses()
{
    $devices = get_devices();
    $updated = [];

    foreach ($devices as $device) {
        $updated[] = update_device_status($device);
    }

    return $updated;
}

function get_recent_logs($limit = 8)
{
    return db_fetch_all(
        'SELECT monitor_logs.id, monitor_logs.old_status, monitor_logs.new_status, monitor_logs.waktu,
                devices.nama_device, devices.ip_address
         FROM monitor_logs
         INNER JOIN devices ON devices.id = monitor_logs.device_id
         ORDER BY monitor_logs.waktu DESC
         LIMIT ?',
        [(int) $limit],
        'i'
    );
}

function get_logs($startDate = '', $endDate = '')
{
    $sql = 'SELECT monitor_logs.id, monitor_logs.old_status, monitor_logs.new_status, monitor_logs.waktu,
                   devices.nama_device, devices.ip_address, devices.jenis_device, devices.lokasi
            FROM monitor_logs
            INNER JOIN devices ON devices.id = monitor_logs.device_id';
    $params = [];
    $types = '';
    $conditions = [];

    if ($startDate !== '') {
        $conditions[] = 'DATE(monitor_logs.waktu) >= ?';
        $params[] = $startDate;
        $types .= 's';
    }

    if ($endDate !== '') {
        $conditions[] = 'DATE(monitor_logs.waktu) <= ?';
        $params[] = $endDate;
        $types .= 's';
    }

    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY monitor_logs.waktu DESC';

    return db_fetch_all($sql, $params, $types);
}

function get_monitoring_activity()
{
    return db_fetch_all(
        'SELECT DATE(waktu) AS tanggal, COUNT(*) AS total
         FROM monitor_logs
         WHERE waktu >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
         GROUP BY DATE(waktu)
         ORDER BY tanggal ASC'
    );
}

function get_monitoring_activity_chart()
{
    $activityRows = get_monitoring_activity();
    $activityMap = [];

    foreach ($activityRows as $row) {
        $activityMap[$row['tanggal']] = (int) $row['total'];
    }

    $labels = [];
    $values = [];

    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-{$i} day"));
        $labels[] = date('d M', strtotime($date));
        $values[] = $activityMap[$date] ?? 0;
    }

    return [
        'labels' => $labels,
        'values' => $values,
    ];
}

function status_class($status)
{
    $classes = [
        'ONLINE' => 'success',
        'OFFLINE' => 'danger',
        'ERROR' => 'warning',
    ];

    return $classes[$status] ?? 'secondary';
}

function status_badge($status)
{
    return '<span class="badge text-bg-' . status_class($status) . '">' . e($status) . '</span>';
}

function pdf_escape($text)
{
    return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], (string) $text);
}

function output_report_pdf($rows, $startDate, $endDate)
{
    $lines = [
        APP_NAME . ' - Laporan Monitoring',
        'Periode: ' . ($startDate !== '' ? $startDate : 'Awal') . ' s/d ' . ($endDate !== '' ? $endDate : 'Akhir'),
        'Total Log: ' . count($rows),
        '',
    ];

    foreach ($rows as $row) {
        $lines[] = $row['waktu'] . ' | ' . $row['nama_device'] . ' | ' . $row['ip_address'] . ' | ' . $row['old_status'] . ' -> ' . $row['new_status'];
    }

    $content = "BT\n/F1 11 Tf\n50 800 Td\n14 TL\n";

    foreach ($lines as $index => $line) {
        if ($index > 0) {
            $content .= "T*\n";
        }
        $content .= '(' . pdf_escape(substr($line, 0, 95)) . ") Tj\n";
    }

    $content .= "ET\n";

    $objects = [];
    $objects[] = "<< /Type /Catalog /Pages 2 0 R >>";
    $objects[] = "<< /Type /Pages /Kids [3 0 R] /Count 1 >>";
    $objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>";
    $objects[] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
    $objects[] = "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "endstream";

    $pdf = "%PDF-1.4\n";
    $offsets = [0];

    foreach ($objects as $index => $object) {
        $offsets[] = strlen($pdf);
        $pdf .= ($index + 1) . " 0 obj\n" . $object . "\nendobj\n";
    }

    $xref = strlen($pdf);
    $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
    $pdf .= "0000000000 65535 f \n";

    for ($i = 1; $i <= count($objects); $i++) {
        $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
    }

    $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
    $pdf .= "startxref\n" . $xref . "\n%%EOF";

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="laporan-smartnet.pdf"');
    header('Content-Length: ' . strlen($pdf));
    echo $pdf;
    exit;
}
