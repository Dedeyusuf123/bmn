<?php
require_once __DIR__ . '/../helpers.php';
$laporan = new Laporan();
$q = trim($_GET['q'] ?? '');
$printId = filter_input(INPUT_GET, 'print_id', FILTER_VALIDATE_INT);
$autoprint = ($_GET['autoprint'] ?? '') === '1';
$data = $laporan->getAll($q, $printId ?: null);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Data Barang BMN</title>
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
            .table{ width:100% !important; font-size:8px; }
            .table th, .table td{ border:1px solid #000 !important; padding:3px !important; }
            .table-danger{ background:#ddd !important; color:#000 !important; }
            .foto-barang{ width:55px; height:45px; border:1px solid #000; }
        }
    </style>
</head>
<body>
<?php render_topbar('laporan'); ?>

<main class="content">
    <div class="container-fluid">
        <div class="card shadow-lg mb-4 report-card">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="mb-0">Laporan Data Barang BMN</h4>
                    <small>Bawaslu Provinsi Jawa Barat</small>
                </div>
                <span class="badge bg-light text-danger no-print">Login: <?= e(current_username()) ?> (<?= e(role_label()) ?>)</span>
            </div>

            <div class="card-body">
                <div class="mb-3 d-flex gap-2 no-print flex-wrap">
                    <a href="<?= e(home_url()) ?>" class="btn btn-secondary btn-custom">Kembali</a>
                    <button type="button" onclick="window.print();" class="btn btn-danger btn-custom">Cetak Laporan Data Barang</button>
                    <!-- <a href="<?= e(route_url('laporan_barang_rusak')) ?>" class="btn btn-outline-danger btn-custom">Ke Laporan Barang Rusak Pegawai</a> -->
                    <?php if ($printId): ?>
                        <a href="<?= e(route_url('laporan')) ?>" class="btn btn-outline-danger btn-custom">Tampilkan Semua</a>
                    <?php endif; ?>
                </div>

                <?php if (current_user_role() === 'pimpinan'): ?>
                    <div class="alert alert-info py-2 no-print">
                        Akun pimpinan hanya dapat melihat dan mencetak laporan. Akun ini tidak memiliki akses untuk menambah, mengedit, atau menghapus data BMN.
                    </div>
                <?php endif; ?>

                <?php if ($printId): ?>
                    <div class="alert alert-info py-2 no-print">
                        Mode cetak 1 barang aktif. Hanya data barang yang dipilih yang ditampilkan.
                    </div>
                <?php endif; ?>

                <!-- <div class="alert alert-light border no-print">
                     Halaman ini khusus menampilkan <strong>data inventaris Barang Milik Negara</strong>. Laporan barang rusak dari pegawai sudah dipisahkan ke menu sidebar <strong>Laporan Barang Rusak Pegawai</strong>. -->
                </div>

                <form method="GET" class="mb-3 no-print">
                    <input type="hidden" name="page" value="laporan">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <input type="text" name="q" class="form-control" placeholder="Cari data barang BMN..." value="<?= e($q) ?>">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                    <?php if ($q !== ''): ?>
                        <a href="<?= e(route_url('laporan')) ?>" class="btn btn-link px-0 mt-1">Reset pencarian</a>
                    <?php endif; ?>
                </form>

                <h5 class="mb-3">Data Barang BMN</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-danger text-center">
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>NUP</th>
                                <th>Nama Barang</th>
                                <th>Merk/Tipe</th>
                                <th>Tanggal Perolehan</th>
                                <th>Kondisi</th>
                                <th>Harga Barang</th>
                                <th>Kondisi Inv</th>
                                <th>Harga Inv</th>
                                <th>Status Penggunaan Inv</th>
                                <th>Tercatat Inv</th>
                                <th>Kode Ruangan</th>
                                <th>Nama Ruangan</th>
                                <th>Status Kodefikasi</th>
                                <th>Status Inventarisasi</th>
                                <th>Foto</th>
                                <th>Tanggal Input</th>
                                <th class="no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php if (mysqli_num_rows($data) > 0): ?>
                                <?php while ($d = mysqli_fetch_assoc($data)): ?>
                                    <?php $fotoUrl = barang_photo_url($d['foto'] ?? null); ?>
                                    <tr>
                                        <td class="text-center"><?= e($no++) ?></td>
                                        <td><?= e($d['kode_barang'] ?? '') ?></td>
                                        <td><?= e($d['nup'] ?? '') ?></td>
                                        <td><?= e($d['nama_barang'] ?? '') ?></td>
                                        <td><?= e($d['merk_tipe'] ?? '') ?></td>
                                        <td class="text-center"><?= !empty($d['tgl_perolehan']) ? e(date('d-m-Y', strtotime($d['tgl_perolehan']))) : '-' ?></td>
                                        <td><?= e($d['kondisi'] ?? '') ?></td>
                                        <td><?= e(rupiah($d['harga_barang'] ?? null)) ?></td>
                                        <td><?= e($d['kondisi_inv'] ?? '') ?></td>
                                        <td><?= e(rupiah($d['harga_barang_inv'] ?? null)) ?></td>
                                        <td><?= e($d['status_penggunaan_inv'] ?? '') ?></td>
                                        <td><?= e($d['tercatat_inv'] ?? '') ?></td>
                                        <td><?= e($d['kode_ruangan'] ?? '') ?></td>
                                        <td><?= e($d['nama_ruangan'] ?? '') ?></td>
                                        <td><?= e($d['status_kodefikasi'] ?? '') ?></td>
                                        <td><?= e($d['status_inventarisasi'] ?? '') ?></td>
                                        <td class="text-center">
                                            <?php if ($fotoUrl !== ''): ?>
                                                <img src="<?= e($fotoUrl) ?>" class="foto-barang" alt="Foto Barang">
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?= !empty($d['created_at']) ? e(date('d-m-Y H:i', strtotime($d['created_at']))) : '-' ?></td>
                                        <td class="text-center no-print">
                                            <a href="<?= e(route_url('laporan', ['print_id' => $d['id'], 'autoprint' => 1])) ?>" class="btn btn-sm btn-outline-danger">Cetak 1 Barang</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="19" class="text-center text-muted">Data tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php if ($autoprint): ?>
<script>
    window.addEventListener('load', function () {
        window.print();
    });
</script>
<?php endif; ?>
</body>
</html>
