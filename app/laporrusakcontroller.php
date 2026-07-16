<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../koneksi.php';

class LaporRusakController
{
    public function index(): void
    {
        require_role(['pegawai']);
        global $conn;

        $error = '';
        $success = '';

        if (isset($_POST['kirim_laporan'])) {
            $barangId = filter_input(INPUT_POST, 'barang_id', FILTER_VALIDATE_INT);
            $namaPelapor = trim($_POST['nama_pelapor'] ?? current_username());
            $deskripsi = trim($_POST['deskripsi_kerusakan'] ?? '');
            $fotoName = null;
            $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;

            if (!$barangId) {
                $error = 'Pilih barang yang rusak terlebih dahulu.';
            } elseif ($deskripsi === '') {
                $error = 'Keterangan kerusakan wajib diisi.';
            }

            if ($error === '') {
                $fotoName = upload_barang_photo($_FILES['foto'] ?? [], $error);
            }

            if ($error === '') {
                $stmt = mysqli_prepare($conn, '
                    INSERT INTO laporan_kerusakan
                        (barang_id, user_id, nama_pelapor, deskripsi_kerusakan, foto, status)
                    VALUES (?, ?, ?, ?, ?, ?)
                ');
                $status = 'Diajukan';
                mysqli_stmt_bind_param($stmt, 'iissss', $barangId, $userId, $namaPelapor, $deskripsi, $fotoName, $status);
                mysqli_stmt_execute($stmt);
                $success = 'Laporan barang rusak berhasil dikirim.';
                $_POST = [];
            }
        }

        $barangList = mysqli_query($conn, 'SELECT id, kode_barang, nup, nama_barang, merk_tipe, nama_ruangan, kondisi FROM barang ORDER BY nama_barang ASC');


        include __DIR__ . '/../view/lapor_rusak.php';
    }
}
