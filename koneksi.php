<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db_env.php';

$cfg = db_env_config();

try {
    $conn = mysqli_connect(
        $cfg['host'],
        $cfg['user'],
        $cfg['pass'],
        $cfg['db'],
        $cfg['port']
    );

    if (!$conn) {
        die(
            'Koneksi database gagal: ' . mysqli_connect_error()
        );
    }

    mysqli_set_charset($conn, 'utf8mb4');

    ensure_app_schema($conn);

} catch (Exception $e) {
    die(
        'Koneksi database gagal. Pastikan database sudah diimport dan konfigurasi environment variable benar. Detail: '
        . $e->getMessage()
    );
}