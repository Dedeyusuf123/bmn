<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db_env.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$cfg = db_env_config();

try {
    $conn = mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['db'], $cfg['port']);
    mysqli_set_charset($conn, 'utf8mb4');
    ensure_app_schema($conn);
} catch (mysqli_sql_exception $e) {
    die('Koneksi database gagal. Pastikan database sudah diimport dan konfigurasi koneksi (environment variable) benar. Detail: ' . $e->getMessage());
}
