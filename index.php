<?php

require_once __DIR__ . '/koneksi.php';

redirect(is_logged_in() ? 'dashboard.php' : 'login.php');

