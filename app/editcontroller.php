<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../koneksi.php';

class EditController
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

        $stmt = mysqli_prepare($conn, 'SELECT * FROM barang WHERE id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $d = mysqli_fetch_assoc($result);

        if (!$d) {
            header('Location: ' . route_url('dashboard'));
            exit;
        }

        $error = '';

        if (isset($_POST['update'])) {
            $kodeBarang = trim($_POST['kode_barang'] ?? '');
            $nup = trim($_POST['nup'] ?? '');
            $namaBarang = trim($_POST['nama_barang'] ?? '');
            $merkTipe = trim($_POST['merk_tipe'] ?? '');
            $tglPerolehan = trim($_POST['tgl_perolehan'] ?? '') ?: null;
            $kondisi = trim($_POST['kondisi'] ?? '');
            $hargaBarang = ($_POST['harga_barang'] ?? '') !== '' ? (float) $_POST['harga_barang'] : null;
            $kondisiInv = trim($_POST['kondisi_inv'] ?? '');
            $hargaBarangInv = ($_POST['harga_barang_inv'] ?? '') !== '' ? (float) $_POST['harga_barang_inv'] : null;
            $statusPenggunaanInv = trim($_POST['status_penggunaan_inv'] ?? '');
            $tercatatInv = trim($_POST['tercatat_inv'] ?? '');
            $kodeRuangan = trim($_POST['kode_ruangan'] ?? '');
            $namaRuangan = trim($_POST['nama_ruangan'] ?? '');
            $statusKodefikasi = trim($_POST['status_kodefikasi'] ?? '');
            $statusInventarisasi = trim($_POST['status_inventarisasi'] ?? '');
            $fotoName = $d['foto'] ?? null;
            $newFotoName = null;

            if ($kodeBarang === '' || $namaBarang === '') {
                $error = 'Kode barang dan nama barang wajib diisi.';
            }

            if ($error === '') {
                $newFotoName = upload_barang_photo($_FILES['foto'] ?? [], $error);
            }

            if ($error === '') {
                if ($newFotoName !== null) {
                    $fotoName = $newFotoName;
                }

                $stmt = mysqli_prepare($conn, '
                    UPDATE barang SET
                        kode_barang = ?,
                        nup = ?,
                        nama_barang = ?,
                        merk_tipe = ?,
                        tgl_perolehan = ?,
                        kondisi = ?,
                        harga_barang = ?,
                        kondisi_inv = ?,
                        harga_barang_inv = ?,
                        status_penggunaan_inv = ?,
                        tercatat_inv = ?,
                        kode_ruangan = ?,
                        nama_ruangan = ?,
                        status_kodefikasi = ?,
                        status_inventarisasi = ?,
                        foto = ?
                    WHERE id = ?
                ');

                mysqli_stmt_bind_param(
                    $stmt,
                    'ssssssdsdsssssssi',
                    $kodeBarang,
                    $nup,
                    $namaBarang,
                    $merkTipe,
                    $tglPerolehan,
                    $kondisi,
                    $hargaBarang,
                    $kondisiInv,
                    $hargaBarangInv,
                    $statusPenggunaanInv,
                    $tercatatInv,
                    $kodeRuangan,
                    $namaRuangan,
                    $statusKodefikasi,
                    $statusInventarisasi,
                    $fotoName,
                    $id
                );
                mysqli_stmt_execute($stmt);

                if ($newFotoName !== null && !empty($d['foto']) && $d['foto'] !== $newFotoName) {
                    delete_barang_photo($d['foto']);
                }

                header('Location: ' . route_url('dashboard'));
                exit;
            }

            $d = array_merge($d, [
                'kode_barang' => $kodeBarang,
                'nup' => $nup,
                'nama_barang' => $namaBarang,
                'merk_tipe' => $merkTipe,
                'tgl_perolehan' => $tglPerolehan,
                'kondisi' => $kondisi,
                'harga_barang' => $hargaBarang,
                'kondisi_inv' => $kondisiInv,
                'harga_barang_inv' => $hargaBarangInv,
                'status_penggunaan_inv' => $statusPenggunaanInv,
                'tercatat_inv' => $tercatatInv,
                'kode_ruangan' => $kodeRuangan,
                'nama_ruangan' => $namaRuangan,
                'status_kodefikasi' => $statusKodefikasi,
                'status_inventarisasi' => $statusInventarisasi,
            ]);
        }

        include __DIR__ . '/../view/edit.php';
    }
}
