<?php
require_once __DIR__ . '/../helpers.php';
$fotoUrl = barang_photo_url($d['foto'] ?? null);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Barang BMN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .foto-preview{
            width:140px;
            height:100px;
            object-fit:cover;
            border:1px solid #ddd;
            border-radius:8px;
            padding:3px;
            background:#fff;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5 mb-5">
    <div class="card shadow-lg">
        <div class="card-header bg-warning">
            <h4 class="mb-0">Edit Barang BMN</h4>
        </div>

        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" autocomplete="off">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control" value="<?= e($d['kode_barang'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">NUP</label>
                        <input type="text" name="nup" class="form-control" value="<?= e($d['nup'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="<?= e($d['nama_barang'] ?? '') ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Merk/Tipe</label>
                        <input type="text" name="merk_tipe" class="form-control" value="<?= e($d['merk_tipe'] ?? '') ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Perolehan</label>
                        <input type="date" name="tgl_perolehan" class="form-control" value="<?= e($d['tgl_perolehan'] ?? '') ?>">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kondisi</label>
                        <select name="kondisi" class="form-select">
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="Baik" <?= selected($d['kondisi'] ?? '', 'Baik') ?>>Baik</option>
                            <option value="Rusak Ringan" <?= selected($d['kondisi'] ?? '', 'Rusak Ringan') ?>>Rusak Ringan</option>
                            <option value="Rusak Berat" <?= selected($d['kondisi'] ?? '', 'Rusak Berat') ?>>Rusak Berat</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Harga Barang</label>
                        <input type="number" name="harga_barang" class="form-control" min="0" step="0.01" value="<?= e($d['harga_barang'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kondisi Inventarisasi</label>
                        <select name="kondisi_inv" class="form-select">
                            <option value="">-- Pilih Kondisi inventaris --</option>
                            <option value="Baik" <?= selected($d['kondisi_inv'] ?? '', 'Baik') ?>>Baik</option>
                            <option value="Rusak Ringan" <?= selected($d['kondisi_inv'] ?? '', 'Rusak Ringan') ?>>Rusak Ringan</option>
                            <option value="Rusak Berat" <?= selected($d['kondisi_inv'] ?? '', 'Rusak Berat') ?>>Rusak Berat</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Barang Inventarisasi</label>
                        <input type="number" name="harga_barang_inv" class="form-control" min="0" step="0.01" value="<?= e($d['harga_barang_inv'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Penggunaan Inventarisasi</label>
                        <input type="text" name="status_penggunaan_inv" class="form-control" value="<?= e($d['status_penggunaan_inv'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tercatat Inventarisasi</label>
                        <select name="tercatat_inv" class="form-select">
                            <option value="">-- Pilih Tercatat Inventarisasi --</option>
                            <option value="DBR" <?= selected($d['tercatat_inv'] ?? '', 'DBR') ?>>DBR</option>
                            <option value="KIB" <?= selected($d['tercatat_inv'] ?? '', 'KIB') ?>>KIB</option>
                            <option value="Tidak Tercatat" <?= selected($d['tercatat_inv'] ?? '', 'Tidak Tercatat') ?>>Tidak Tercatat</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kode Ruangan</label>
                        <input type="text" name="kode_ruangan" class="form-control" value="<?= e($d['kode_ruangan'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Ruangan</label>
                        <input type="text" name="nama_ruangan" class="form-control" value="<?= e($d['nama_ruangan'] ?? '') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Kodefikasi</label>
                        <select name="status_kodefikasi" class="form-select">
                            <option value="">-- Pilih Status Kodefikasi --</option>
                            <option value="Sesuai" <?= selected($d['status_kodefikasi'] ?? '', 'Sesuai') ?>>Sesuai</option>
                            <option value="Tidak Sesuai" <?= selected($d['status_kodefikasi'] ?? '', 'Tidak Sesuai') ?>>Tidak Sesuai</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Inventarisasi</label>
                        <select name="status_inventarisasi" class="form-select">
                            <option value="">-- Pilih Status Inventarisasi --</option>
                            <option value="Ditemukan" <?= selected($d['status_inventarisasi'] ?? '', 'Ditemukan') ?>>Ditemukan</option>
                            <option value="Tidak Ditemukan" <?= selected($d['status_inventarisasi'] ?? '', 'Tidak Ditemukan') ?>>Tidak Ditemukan</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Foto Barang</label>
                        <?php if ($fotoUrl !== ''): ?>
                            <div class="mb-2">
                                <img src="<?= e($fotoUrl) ?>" class="foto-preview" alt="Foto Barang">
                            </div>
                        <?php else: ?>
                            <div class="text-muted mb-2">Belum ada foto.</div>
                        <?php endif; ?>
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto. Format JPG, PNG, GIF, atau WEBP. Maksimal 5 MB.</small>
                    </div>
                </div>

                <button type="submit" name="update" class="btn btn-warning">Update</button>
                <a href="<?= e(route_url('dashboard')) ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
