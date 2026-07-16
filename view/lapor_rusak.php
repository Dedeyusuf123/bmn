<?php
require_once __DIR__ . '/../helpers.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lapor Barang Rusak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body{ background:#f4f6f9; }
        .card{ border:none; border-radius:15px; }
        .card-header{ border-radius:15px 15px 0 0 !important; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-danger shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?= e(route_url('lapor_rusak')) ?>">
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

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">Lapor Barang Rusak</h4>
                    <small>Form khusus pegawai untuk mengirim laporan kerusakan barang.</small>
                </div>
                <div class="card-body">
                    <div class="alert alert-info py-2">
                        Pegawai hanya dapat mengirim laporan barang rusak. Laporan barang rusak akan masuk ke halaman terpisah <strong>Laporan Barang Rusak dari Pegawai</strong> yang hanya bisa dilihat admin dan pimpinan.
                    </div>

                    <?php if ($error !== ''): ?>
                        <div class="alert alert-danger py-2"><?= e($error) ?></div>
                    <?php endif; ?>

                    <?php if ($success !== ''): ?>
                        <div class="alert alert-success py-2"><?= e($success) ?></div>
                    <?php endif; ?>

                    
                    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <a href="<?= e(route_url('dashboard')) ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nama Pelapor</label>
                            <input type="text" name="nama_pelapor" class="form-control" value="<?= e($_POST['nama_pelapor'] ?? current_username()) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pilih Barang Rusak</label>
                            <select name="barang_id" class="form-select" required>
                                <option value="">-- Pilih Barang --</option>
                                <?php while ($barang = mysqli_fetch_assoc($barangList)): ?>
                                    <?php
                                    $label = trim(($barang['kode_barang'] ?? '') . ' | ' . ($barang['nup'] ?? '') . ' | ' . ($barang['nama_barang'] ?? '') . ' | ' . ($barang['merk_tipe'] ?? '') . ' | ' . ($barang['nama_ruangan'] ?? ''));
                                    ?>
                                    <option value="<?= e($barang['id']) ?>" <?= selected($_POST['barang_id'] ?? '', $barang['id']) ?>><?= e($label) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Kerusakan</label>
                            <textarea name="deskripsi_kerusakan" class="form-control" rows="5" required placeholder="Contoh: layar monitor pecah, printer tidak bisa mencetak, kursi patah, dan sebagainya."><?= e($_POST['deskripsi_kerusakan'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Kerusakan <span class="text-muted">(opsional)</span></label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
                            <small class="text-muted">Format JPG, PNG, GIF, atau WEBP. Maksimal 5 MB.</small>
                        </div>

                        <button type="submit" name="kirim_laporan" class="btn btn-danger w-100">
                            <i class="bi bi-send"></i> Kirim Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
