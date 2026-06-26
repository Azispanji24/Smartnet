<?php

require_once __DIR__ . '/koneksi.php';
require_login();

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    delete_device($id);
}

redirect('devices.php?message=Device berhasil dihapus');

