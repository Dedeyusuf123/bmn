<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../db_env.php';

class Database
{
    public function connect(): mysqli
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $cfg = db_env_config();
        $conn = mysqli_connect($cfg['host'], $cfg['user'], $cfg['pass'], $cfg['db'], $cfg['port']);
        mysqli_set_charset($conn, 'utf8mb4');
        ensure_app_schema($conn);

        return $conn;
    }
}
