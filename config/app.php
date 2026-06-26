<?php

define('APP_NAME', 'SmartNet');
define('APP_DESCRIPTION', 'Sistem Informasi Monitoring Jaringan Laboratorium');
define('APP_BASE_URL', '/jaringan');
define('APP_TIMEZONE', 'Asia/Jakarta');

date_default_timezone_set(APP_TIMEZONE);

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smartnet_db');

define('DEFAULT_ADMIN_USERNAME', 'admin');
define('DEFAULT_ADMIN_PASSWORD', 'admin123');
define('DEFAULT_ADMIN_ROLE', 'admin');

define('MONITORING_INTERVAL_MS', 10000);
