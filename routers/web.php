<?php
$page = isset($_GET['page']) ? trim($_GET['page']) : '';

switch ($page) {
    case 'dashboard':
        require_once __DIR__ . '/../app/dashboardcontroller.php';
        (new DashboardController())->index();
        break;

    case 'laporan':
        require_once __DIR__ . '/../app/laporancontroller.php';
        (new LaporanController())->index();
        break;

    case 'lapor_rusak':
        require_once __DIR__ . '/../app/laporrusakcontroller.php';
        (new LaporRusakController())->index();
        break;

    case 'laporan_barang_rusak':
        require_once __DIR__ . '/../app/laporanbarangrusakcontroller.php';
        (new LaporanBarangRusakController())->index();
        break;

    // Alias lama agar link lama tetap aman, tetapi diarahkan ke halaman baru yang sudah dipisah.
    case 'data_laporan_barang':
        header('Location: ' . route_url('laporan_barang_rusak'));
        exit;

    case 'tambah':
        require_once __DIR__ . '/../app/tambahcontroller.php';
        (new TambahController())->index();
        break;

    case 'edit':
        require_once __DIR__ . '/../app/editcontroller.php';
        (new EditController())->index();
        break;

    case 'hapus':
        require_once __DIR__ . '/../app/hapuscontroller.php';
        (new HapusController())->index();
        break;

    case 'logout':
        require_once __DIR__ . '/../app/authcontroller.php';
        (new AuthController())->logout();
        break;

    default:
        require_once __DIR__ . '/../app/authcontroller.php';
        (new AuthController())->login();
        break;
}
