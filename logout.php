<?php

require_once __DIR__ . '/koneksi.php';

$_SESSION = [];
session_destroy();

redirect('login.php');

