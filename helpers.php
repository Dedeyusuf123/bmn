<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function route_url(string $page = '', array $params = []): string
{
    $script = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
    if ($script === '' || $script === '/' || $script === '.') {
        $script = 'index.php';
    }

    $query = $params;
    if ($page !== '') {
        $query = array_merge(['page' => $page], $query);
    }

    return $script . (!empty($query) ? '?' . http_build_query($query) : '');
}

function asset_url(string $path): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptDir = rtrim($scriptDir, '/');

    if ($scriptDir === '' || $scriptDir === '.') {
        $scriptDir = '';
    }

    $publicDir = basename($scriptDir) === 'public'
        ? $scriptDir
        : $scriptDir . '/public';

    return rtrim($publicDir, '/') . '/' . ltrim($path, '/');
}

function require_login(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['login'])) {
        header('Location: ' . route_url());
        exit;
    }
}

function current_user_role(): string
{
    $role = strtolower(trim((string) ($_SESSION['role'] ?? '')));
    return in_array($role, ['admin', 'pegawai', 'pimpinan'], true) ? $role : 'admin';
}

function current_username(): string
{
    return (string) ($_SESSION['username'] ?? 'Pengguna');
}

function role_label(?string $role = null): string
{
    $role = $role ?: current_user_role();
    $labels = [
        'admin' => 'Admin',
        'pegawai' => 'Pegawai',
        'pimpinan' => 'Pimpinan',
    ];

    return $labels[$role] ?? 'Admin';
}

function default_page_for_role(?string $role = null): string
{
    return 'dashboard';
}

function home_url(): string
{
    return route_url(default_page_for_role());
}

function bmn_menu_items(): array
{
    $role = current_user_role();

    if ($role === 'pegawai') {
        return [
            ['page' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2'],
            ['page' => 'lapor_rusak', 'label' => 'Lapor Barang Rusak', 'icon' => 'bi-exclamation-triangle'],
        ];
    }

    if ($role === 'pimpinan') {
        return [
            ['page' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2'],
            ['page' => 'laporan', 'label' => 'Laporan Data Barang BMN', 'icon' => 'bi-file-earmark-text'],
            ['page' => 'laporan_barang_rusak', 'label' => 'Laporan Barang Rusak Pegawai', 'icon' => 'bi-clipboard-data'],
        ];
    }

    return [
        ['page' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-speedometer2'],
        ['page' => 'tambah', 'label' => 'Tambah Barang', 'icon' => 'bi-plus-circle'],
        ['page' => 'laporan', 'label' => 'Laporan Data Barang BMN', 'icon' => 'bi-file-earmark-text'],
        ['page' => 'laporan_barang_rusak', 'label' => 'Laporan Barang Rusak Pegawai', 'icon' => 'bi-clipboard-data'],
    ];
}

function render_sidebar(string $activePage = ''): void
{
    ?>
    <div class="sidebar no-print">
        <div class="logo">
            <img src="<?= e(asset_url('images/BWSLOG.png')) ?>" width="50" alt="Logo">
            BAWASLU PROVINSI JABAR
        </div>

        <?php foreach (bmn_menu_items() as $item): ?>
            <a href="<?= e(route_url($item['page'])) ?>" class="<?= $activePage === $item['page'] ? 'active' : '' ?>">
                <i class="bi <?= e($item['icon']) ?>"></i>
                <span class="text-menu"><?= e($item['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
}

function render_topbar(string $activePage = ''): void
{
    ?>
    <nav class="navbar navbar-dark bg-danger shadow no-print">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= e(home_url()) ?>">
                <img src="<?= e(asset_url('images/BWSLOG.png')) ?>" width="50" alt="Logo">
                SISTEM BMN BAWASLU
            </a>

            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-light text-danger">Login: <?= e(current_username()) ?> (<?= e(role_label()) ?>)</span>
                <a href="<?= e(route_url('logout')) ?>" class="btn btn-light">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>
    <?php
    render_sidebar($activePage);
}

function require_role(array $roles): void
{
    require_login();

    if (!in_array(current_user_role(), $roles, true)) {
        header('Location: ' . home_url());
        exit;
    }
}

function rupiah($value): string
{
    if ($value === null || $value === '') {
        return '-';
    }

    return 'Rp ' . number_format((float) $value, 0, ',', '.');
}

function selected($currentValue, $optionValue): string
{
    return (string) $currentValue === (string) $optionValue ? 'selected' : '';
}

function barang_upload_dir(): string
{
    return __DIR__ . '/public/uploads/barang';
}

function barang_photo_url(?string $filename): string
{
    if ($filename === null || trim($filename) === '') {
        return '';
    }

    return asset_url('uploads/barang/' . basename($filename));
}

function upload_barang_photo(array $file, ?string &$error = null): ?string
{
    if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Foto gagal diunggah. Silakan coba lagi.';
        return null;
    }

    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        $error = 'Ukuran foto maksimal 5 MB.';
        return null;
    }

    $tmpName = $file['tmp_name'] ?? '';
    $imageInfo = @getimagesize($tmpName);
    if ($imageInfo === false) {
        $error = 'File foto harus berupa gambar.';
        return null;
    }

    $allowedMime = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    $mime = $imageInfo['mime'] ?? '';
    if (!isset($allowedMime[$mime])) {
        $error = 'Format foto harus JPG, PNG, GIF, atau WEBP.';
        return null;
    }

    $uploadDir = barang_upload_dir();
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $allowedMime[$mime];
    $destination = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($tmpName, $destination)) {
        $error = 'Foto gagal disimpan ke folder upload.';
        return null;
    }

    return $filename;
}

function delete_barang_photo(?string $filename): void
{
    if ($filename === null || trim($filename) === '') {
        return;
    }

    $path = barang_upload_dir() . '/' . basename($filename);
    if (is_file($path)) {
        @unlink($path);
    }
}

function ensure_barang_foto_column(mysqli $conn): void
{
    try {
        $result = mysqli_query($conn, "SHOW COLUMNS FROM barang LIKE 'foto'");
        if ($result && mysqli_num_rows($result) === 0) {
            mysqli_query($conn, "ALTER TABLE barang ADD COLUMN foto varchar(255) DEFAULT NULL AFTER status_inventarisasi");
        }
    } catch (Throwable $e) {
        // Dibiarkan agar halaman lain tetap jalan jika tabel belum diimport.
    }
}

function ensure_user_role_column(mysqli $conn): void
{
    try {
        $result = mysqli_query($conn, "SHOW COLUMNS FROM `user` LIKE 'role'");
        if ($result && mysqli_num_rows($result) === 0) {
            mysqli_query($conn, "ALTER TABLE `user` ADD COLUMN `role` varchar(20) NOT NULL DEFAULT 'admin' AFTER `password`");
        }

        mysqli_query($conn, "UPDATE `user` SET role = 'admin' WHERE role IS NULL OR role = ''");

        $defaultUsers = [
            ['admin', 'admin', 'admin'],
            ['pegawai', 'pegawai', 'pegawai'],
            ['pimpinan', 'pimpinan', 'pimpinan'],
        ];

        foreach ($defaultUsers as $defaultUser) {
            [$username, $password, $role] = $defaultUser;
            $stmt = mysqli_prepare($conn, 'SELECT id FROM `user` WHERE username = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) === 0) {
                $insert = mysqli_prepare($conn, 'INSERT INTO `user` (username, password, role) VALUES (?, ?, ?)');
                mysqli_stmt_bind_param($insert, 'sss', $username, $password, $role);
                mysqli_stmt_execute($insert);
            }
        }
    } catch (Throwable $e) {
        // Dibiarkan agar halaman lain tetap jalan jika tabel belum diimport.
    }
}

function ensure_laporan_kerusakan_table(mysqli $conn): void
{
    try {
        mysqli_query($conn, "
            CREATE TABLE IF NOT EXISTS laporan_kerusakan (
                id int(11) NOT NULL AUTO_INCREMENT,
                barang_id int(11) NOT NULL,
                user_id int(11) DEFAULT NULL,
                nama_pelapor varchar(100) DEFAULT NULL,
                deskripsi_kerusakan text NOT NULL,
                foto varchar(255) DEFAULT NULL,
                status varchar(50) NOT NULL DEFAULT 'Diajukan',
                created_at timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (id),
                KEY barang_id (barang_id),
                KEY user_id (user_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    } catch (Throwable $e) {
        // Dibiarkan agar halaman lain tetap jalan jika tabel belum diimport.
    }
}

function ensure_app_schema(mysqli $conn): void
{
    ensure_barang_foto_column($conn);
    ensure_user_role_column($conn);
    ensure_laporan_kerusakan_table($conn);
}
