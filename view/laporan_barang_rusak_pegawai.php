<?php
require_once __DIR__ . '/../helpers.php';
$laporan = new Laporan();
$q = trim($_GET['q'] ?? '');
$dataLaporan = $laporan->getLaporanKerusakan($q);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Barang Rusak dari Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= e(asset_url('style.css')) ?>">
    <style>
        .table th, .table td{ vertical-align:middle; }
        .btn-custom{ border-radius:10px; }
        .foto-barang{ width:70px; height:55px; object-fit:cover; border:1px solid #ddd; border-radius:6px; padding:2px; background:#fff; }
        .report-card{ border:none; border-radius:15px; }
        .report-card .card-header{ border-radius:15px 15px 0 0 !important; }
        @page{ size: landscape; margin: 10mm; }
        @media print{
            body{ background:white !important; }
            .no-print{ display:none !important; }
            .content{ margin-left:0 !important; width:100% !important; padding:0 !important; }
            .container-fluid{ max-width:100% !important; width:100% !important; margin:0 !important; padding:0 !important; }
            .report-card{ border:none !important; box-shadow:none !important; }
            .report-card .card-header{ background:white !important; color:black !important; border-bottom:2px solid black !important; }
            .table{ width:100% !important; font-size:9px; }
            .table th, .table td{ border:1px solid #000 !important; padding:4px !important; }
            .table-danger{ background:#ddd !important; color:#000 !important; }
            .foto-barang{ width:55px; height:45px; border:1px solid #000; }
        }
    </style>
</head>
<body>
<?php render_topbar('laporan_barang_rusak'); ?>

<main class="content">
    <div class="container-fluid">
        <div class="card shadow-lg mb-4 report-card">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-0">Laporan Barang Rusak dari Pegawai</h4>
                    <small>Laporan khusus barang rusak yang dikirim melalui akun pegawai</small>
                </div>
                <span class="badge bg-light text-danger no-print">Login: <?= e(current_username()) ?> (<?= e(role_label()) ?>)</span>
            </div>

            <div class="card-body">
                <div class="mb-3 d-flex gap-2 no-print flex-wrap">
                    <a href="<?= e(home_url()) ?>" class="btn btn-secondary btn-custom">Kembali</a>
                    <!-- <a href="<?= e(route_url('laporan')) ?>" class="btn btn-outline-danger btn-custom">Ke Laporan Data Barang BMN</a> -->
                    <button type="button" onclick="window.print();" class="btn btn-danger btn-custom">Cetak Laporan Barang Rusak</button>
                </div>

                <?php if (current_user_role() === 'pimpinan'): ?>
                    <div class="alert alert-info py-2 no-print">
                        Akun pimpinan hanya dapat melihat dan mencetak data laporan barang. Akun ini tidak memiliki akses untuk mengubah database.
                    </div>
                <?php endif; ?>

                <form method="GET" class="mb-3 no-print">
                    <input type="hidden" name="page" value="laporan_barang_rusak">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <input type="text" name="q" class="form-control" placeholder="Cari nama pelapor, nama barang, kode barang, ruangan, atau keterangan kerusakan..." value="<?= e($q) ?>">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                    <?php if ($q !== ''): ?>
                        <a href="<?= e(route_url('laporan_barang_rusak')) ?>" class="btn btn-link px-0 mt-1">Reset pencarian</a>
                    <?php endif; ?>
                </form>

                <!-- <div class="alert alert-light border no-print">
                    Halaman ini khusus menampilkan <strong>laporan barang rusak dari pegawai</strong>. Data ini sudah dipisahkan dari menu <strong>Laporan Data Barang BMN</strong>, sehingga laporan inventaris dan laporan kerusakan tidak tercampur dalam satu tabel.
                </div> -->

                <h5 class="mb-3">Data Laporan Barang Rusak dari Pegawai</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-danger text-center">
                            <tr>
                                <th>No</th>
                                <th>Pelapor</th>
                                <th>Kode Barang</th>
                                <th>NUP</th>
                                <th>Nama Barang</th>
                                <th>Merk/Tipe</th>
                                <th>Ruangan</th>
                                <th>Keterangan Kerusakan</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th>Tanggal Lapor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php if (mysqli_num_rows($dataLaporan) > 0): ?>
                                <?php while ($r = mysqli_fetch_assoc($dataLaporan)): ?>
                                    <?php $fotoRusakUrl = barang_photo_url($r['foto'] ?? null); ?>
                                    <tr>
                                        <td class="text-center"><?= e($no++) ?></td>
                                        <td><?= e($r['nama_pelapor'] ?? '') ?></td>
                                        <td><?= e($r['kode_barang'] ?? '') ?></td>
                                        <td><?= e($r['nup'] ?? '') ?></td>
                                        <td><?= e($r['nama_barang'] ?? '') ?></td>
                                        <td><?= e($r['merk_tipe'] ?? '') ?></td>
                                        <td><?= e($r['nama_ruangan'] ?? '') ?></td>
                                        <td><?= e($r['deskripsi_kerusakan'] ?? '') ?></td>
                                        <td class="text-center">
                                            <?php if ($fotoRusakUrl !== ''): ?>
                                                <img src="<?= e($fotoRusakUrl) ?>" class="foto-barang" alt="Foto Kerusakan">
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><span class="badge bg-warning text-dark"><?= e($r['status'] ?? '') ?></span></td>
                                        <td class="text-center"><?= !empty($r['created_at']) ? e(date('d-m-Y H:i', strtotime($r['created_at']))) : '-' ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center text-muted">Belum ada laporan barang rusak dari pegawai.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>
