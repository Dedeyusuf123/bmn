<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../koneksi.php';

class HapusController
{
    public function index(): void
    {
        require_role(['admin']);
        global $conn;

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            header('Location: ' . route_url('dashboard'));
            exit;
        }

        $stmt = mysqli_prepare($conn, 'SELECT foto FROM barang WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $barang = mysqli_fetch_assoc($result);

        $delete = mysqli_prepare($conn, 'DELETE FROM barang WHERE id = ?');
        mysqli_stmt_bind_param($delete, 'i', $id);
        mysqli_stmt_execute($delete);

        if (!empty($barang['foto'])) {
            delete_barang_photo($barang['foto']);
        }

        header('Location: ' . route_url('dashboard'));
        exit;
    }
}
