<?php

require_once __DIR__ . '/koneksi.php';
require_login();

$pageTitle = 'Tambah Device';
$pageSubtitle = 'Masukkan IP Address perangkat satu kali untuk monitoring otomatis.';
$errors = [];
$values = [
    'nama_device' => '',
    'ip_address' => '',
    'jenis_device' => '',
    'lokasi' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['nama_device'] = trim($_POST['nama_device'] ?? '');
    $values['ip_address'] = trim($_POST['ip_address'] ?? '');
    $values['jenis_device'] = trim($_POST['jenis_device'] ?? '');
    $values['lokasi'] = trim($_POST['lokasi'] ?? '');

    if ($values['nama_device'] === '') {
        $errors[] = 'Nama device wajib diisi.';
    }

    if (!validate_ip_address($values['ip_address'])) {
        $errors[] = 'IP Address tidak valid.';
    }

    if (!in_array($values['jenis_device'], get_device_types(), true)) {
        $errors[] = 'Jenis device tidak valid.';
    }

    if ($values['lokasi'] === '') {
        $errors[] = 'Lokasi wajib diisi.';
    }

    if (empty($errors)) {
        try {
            create_device($values['nama_device'], $values['ip_address'], $values['jenis_device'], $values['lokasi']);
            redirect('devices.php?message=Device berhasil ditambahkan');
        } catch (mysqli_sql_exception $error) {
            $errors[] = 'IP Address sudah digunakan perangkat lain.';
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
            <section class="panel form-card">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger"><?= e(implode(' ', $errors)); ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="nama_device">Nama Device</label>
                            <input class="form-control" id="nama_device" name="nama_device" value="<?= e($values['nama_device']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="ip_address">IP Address</label>
                            <input class="form-control" id="ip_address" name="ip_address" value="<?= e($values['ip_address']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="jenis_device">Jenis Device</label>
                            <select class="form-select" id="jenis_device" name="jenis_device" required>
                                <option value="">Pilih jenis</option>
                                <?php foreach (get_device_types() as $type): ?>
                                    <option value="<?= e($type); ?>" <?= $values['jenis_device'] === $type ? 'selected' : ''; ?>><?= e($type); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="lokasi">Lokasi</label>
                            <input class="form-control" id="lokasi" name="lokasi" value="<?= e($values['lokasi']); ?>" required>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Simpan</button>
                        <a class="btn btn-outline-secondary" href="devices.php">Batal</a>
                    </div>
                </form>
            </section>
<?php include __DIR__ . '/includes/footer.php'; ?>

