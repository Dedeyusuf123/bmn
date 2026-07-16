<?php
/**
 * Helper untuk mengambil kredensial database dari environment variable.
 * Kompatibel dengan variabel yang disediakan Railway MySQL plugin
 * (MYSQLHOST, MYSQLUSER, MYSQLPASSWORD, MYSQLDATABASE, MYSQLPORT)
 * dan juga fallback ke variabel umum (DB_HOST, dst) atau default lokal (XAMPP/Laragon).
 */
function db_env_config(): array
{
    $host = getenv('MYSQLHOST') ?: getenv('DB_HOST') ?: 'localhost';
    $user = getenv('MYSQLUSER') ?: getenv('DB_USER') ?: 'root';
    $pass = getenv('MYSQLPASSWORD') ?: getenv('DB_PASSWORD') ?: '';
    $name = getenv('MYSQLDATABASE') ?: getenv('DB_DATABASE') ?: 'bmn_db';
    $port = (int) (getenv('MYSQLPORT') ?: getenv('DB_PORT') ?: 3306);

    return [
        'host' => $host,
        'user' => $user,
        'pass' => $pass,
        'db'   => $name,
        'port' => $port,
    ];
}
