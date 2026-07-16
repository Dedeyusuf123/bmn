<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../koneksi.php';

$error = '';

if (isset($_POST['simpan'])) {
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
    $fotoName = null;

    if ($kodeBarang === '' || $namaBarang === '') {
        $error = 'Kode barang dan nama barang wajib diisi.';
    }

    if ($error === '') {
        $fotoName = upload_barang_photo($_FILES['foto'] ?? [], $error);
    }

    if ($error === '') {
        $stmt = mysqli_prepare($conn, '
            INSERT INTO barang (
                kode_barang,
                nup,
                nama_barang,
                merk_tipe,
                tgl_perolehan,
                kondisi,
                harga_barang,
                kondisi_inv,
                harga_barang_inv,
                status_penggunaan_inv,
                tercatat_inv,
                kode_ruangan,
                nama_ruangan,
                status_kodefikasi,
                status_inventarisasi,
                foto
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');

        mysqli_stmt_bind_param(
            $stmt,
            'ssssssdsdsssssss',
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
            $fotoName
        );
        mysqli_stmt_execute($stmt);

        header('Location: ' . route_url('dashboard'));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Barang BMN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5 mb-5">
    <div class="card shadow-lg">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0">Tambah Barang BMN</h4>
        </div>

        <div class="card-body">
            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control" value="<?= e($_POST['kode_barang'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">NUP</label>
                        <input type="text" name="nup" class="form-control" value="<?= e($_POST['nup'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="<?= e($_POST['nama_barang'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Merk/Tipe</label>
                        <input type="text" name="merk_tipe" class="form-control" value="<?= e($_POST['merk_tipe'] ?? '') ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Perolehan</label>
                        <input type="date" name="tgl_perolehan" class="form-control" value="<?= e($_POST['tgl_perolehan'] ?? '') ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kondisi</label>
                        <select name="kondisi" class="form-select">
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="Baik" <?= selected($_POST['kondisi'] ?? '', 'Baik') ?>>Baik</option>
                            <option value="Rusak Ringan" <?= selected($_POST['kondisi'] ?? '', 'Rusak Ringan') ?>>Rusak Ringan</option>
                            <option value="Rusak Berat" <?= selected($_POST['kondisi'] ?? '', 'Rusak Berat') ?>>Rusak Berat</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Harga Barang</label>
                        <input type="number" name="harga_barang" class="form-control" min="0" step="0.01" value="<?= e($_POST['harga_barang'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kondisi Inventarisasi</label>
                        <select name="kondisi_inv" class="form-select">
                            <option value="">-- Pilih Kondisi inventaris --</option>
                            <option value="Baik" <?= selected($_POST['kondisi_inv'] ?? '', 'Baik') ?>>Baik</option>
                            <option value="Rusak Ringan" <?= selected($_POST['kondisi_inv'] ?? '', 'Rusak Ringan') ?>>Rusak Ringan</option>
                            <option value="Rusak Berat" <?= selected($_POST['kondisi_inv'] ?? '', 'Rusak Berat') ?>>Rusak Berat</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Barang Inventarisasi</label>
                        <input type="number" name="harga_barang_inv" class="form-control" min="0" step="0.01" value="<?= e($_POST['harga_barang_inv'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Penggunaan Inventarisasi</label>
                        <input type="text" name="status_penggunaan_inv" class="form-control" value="<?= e($_POST['status_penggunaan_inv'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tercatat Inventarisasi</label>
                        <select name="tercatat_inv" class="form-select">
                            <option value="">-- Pilih Tercatat Inventarisasi --</option>
                            <option value="DBR" <?= selected($_POST['tercatat_inv'] ?? '', 'DBR') ?>>DBR</option>
                            <option value="KIB" <?= selected($_POST['tercatat_inv'] ?? '', 'KIB') ?>>KIB</option>
                            <option value="Tidak Tercatat" <?= selected($_POST['tercatat_inv'] ?? '', 'Tidak Tercatat') ?>>Tidak Tercatat</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Ruangan</label>
                        <input type="text" name="kode_ruangan" class="form-control" value="<?= e($_POST['kode_ruangan'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text" name="nama_ruangan" class="form-control" value="<?= e($_POST['nama_ruangan'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Kodefikasi</label>
                        <select name="status_kodefikasi" class="form-select">
                            <option value="">-- Pilih Status Kodefikasi --</option>
                            <option value="Sesuai" <?= selected($_POST['status_kodefikasi'] ?? '', 'Sesuai') ?>>Sesuai</option>
                            <option value="Tidak Sesuai" <?= selected($_POST['status_kodefikasi'] ?? '', 'Tidak Sesuai') ?>>Tidak Sesuai</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Inventarisasi</label>
                        <select name="status_inventarisasi" class="form-select">
                            <option value="">-- Pilih Status Inventarisasi --</option>
                            <option value="Ditemukan" <?= selected($_POST['status_inventarisasi'] ?? '', 'Ditemukan') ?>>Ditemukan</option>
                            <option value="Tidak Ditemukan" <?= selected($_POST['status_inventarisasi'] ?? '', 'Tidak Ditemukan') ?>>Tidak Ditemukan</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Foto Barang</label>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">Format JPG, PNG, GIF, atau WEBP. Maksimal 5 MB.</small>
                    </div>
                </div>

                <button type="submit" name="simpan" class="btn btn-danger">Simpan</button>
                <a href="<?= e(route_url('dashboard')) ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
