<?php
// ==========================================
// PENGATURAN UTAMA WEBSITE (CONFIG)
// ==========================================

// 1. Pengaturan Database
// Mendukung XAMPP Lokal dan Docker/AWS
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'esport_db');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');

// 2. Pengaturan Website
define('SITE_NAME', 'Irwan mutholib');

// BASE_URL adalah alamat dasar web Anda. 
// Jika di AWS, biasanya cukup "/" atau "http://IP-AWS-ANDA/"
define('BASE_URL', '/');
?>
