<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../koneksi.php';

function count_barang(mysqli $conn, ?string $kondisi = null): int
{
    if ($kondisi === null) {
        $result = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM barang');
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT COUNT(*) AS total FROM barang WHERE kondisi = ?');
        mysqli_stmt_bind_param($stmt, 's', $kondisi);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    }

    $row = mysqli_fetch_assoc($result);
    return (int) ($row['total'] ?? 0);
}

$role = current_user_role();

$totalBarang = count_barang($conn);
$totalBaik = count_barang($conn, 'Baik');
$totalRusakRingan = count_barang($conn, 'Rusak Ringan');
$totalRusakBerat = count_barang($conn, 'Rusak Berat');

$totalLaporanRusakResult = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM laporan_kerusakan');
$totalLaporanRusak = (int) (mysqli_fetch_assoc($totalLaporanRusakResult)['total'] ?? 0);

$data = null;
if ($role === 'admin') {
    $data = mysqli_query($conn, 'SELECT * FROM barang ORDER BY created_at DESC, id DESC');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard BMN BAWASLU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= e(asset_url('style.css')) ?>">
</head>
<body>
<?php render_topbar('dashboard'); ?>

<main class="content">
    <div class="container-fluid">
        <h3 class="mb-4">Dashboard Inventaris BMN</h3>

        <div class="row mt-4">
            <div class="col-md-3 mb-3">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h5>Total Barang</h5>
                        <h2><?= e($totalBarang) ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h5>Kondisi Baik</h5>
                        <h2><?= e($totalBaik) ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h5>Rusak Ringan</h5>
                        <h2><?= e($totalRusakRingan) ?></h2>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <h5>Laporan Barang Rusak</h5>
                        <h2><?= e($totalLaporanRusak) ?></h2>
                        <?php if ($role !== 'pegawai'): ?>
                            <a href="<?= e(route_url('laporan_barang_rusak')) ?>" class="btn btn-outline-danger btn-sm mt-2">Buka Laporan Pegawai</a>
                        <?php else: ?>
                            <a href="<?= e(route_url('lapor_rusak')) ?>" class="btn btn-outline-danger btn-sm mt-2">Buat Laporan</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($role === 'pegawai'): ?>
            <div class="card shadow border-0 mt-4">
                <div class="card-header bg-danger text-white">
                    Dashboard Pegawai
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        Akun pegawai hanya dapat menggunakan fitur <strong>Lapor Barang Rusak</strong>. Pegawai tidak memiliki akses untuk menambah, mengedit, menghapus, atau melihat laporan database BMN.
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-2">Laporkan Barang Rusak</h5>
                            <p class="text-muted mb-0">Gunakan menu ini untuk melaporkan barang BMN yang mengalami kerusakan. Data laporan akan masuk ke menu laporan barang rusak pegawai milik admin dan pimpinan.</p>
                        </div>
                        <div class="col-md-3 text-md-end mt-3 mt-md-0">
                            <a href="<?= e(route_url('lapor_rusak')) ?>" class="btn btn-danger">
                                <i class="bi bi-exclamation-triangle"></i> Lapor Barang Rusak
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($role === 'pimpinan'): ?>
            <div class="card shadow border-0 mt-4">
                <div class="card-header bg-danger text-white">
                    Dashboard Pimpinan
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        Akun pimpinan hanya dapat melihat dashboard dan mencetak laporan. Akun ini tidak memiliki akses untuk menambah, mengedit, atau menghapus data BMN.
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h5>Laporan Data Barang BMN</h5>
                                <p class="text-muted">Lihat dan cetak data inventaris Barang Milik Negara.</p>
                                <a href="<?= e(route_url('laporan')) ?>" class="btn btn-danger">
                                    <i class="bi bi-file-earmark-text"></i> Buka Laporan Data Barang
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h5>Laporan Barang Rusak Pegawai</h5>
                                <p class="text-muted">Lihat dan cetak laporan barang rusak yang dikirim oleh pegawai.</p>
                                <a href="<?= e(route_url('laporan_barang_rusak')) ?>" class="btn btn-danger">
                                    <i class="bi bi-clipboard-data"></i> Buka Laporan Barang Rusak
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="card shadow border-0 mt-4">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <span>Data Barang BMN</span>
                    <a href="<?= e(route_url('tambah')) ?>" class="btn btn-light btn-sm"><i class="bi bi-plus-circle"></i> Tambah Barang</a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
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
                                    <th>Ruangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php if ($data && mysqli_num_rows($data) > 0): ?>
                                    <?php while ($d = mysqli_fetch_assoc($data)): ?>
                                        <tr>
                                            <td class="text-center"><?= e($no++) ?></td>
                                            <td><?= e($d['kode_barang'] ?? '') ?></td>
                                            <td><?= e($d['nup'] ?? '') ?></td>
                                            <td><?= e($d['nama_barang'] ?? '') ?></td>
                                            <td><?= e($d['merk_tipe'] ?? '') ?></td>
                                            <td class="text-center"><?= !empty($d['tgl_perolehan']) ? e(date('d-m-Y', strtotime($d['tgl_perolehan']))) : '-' ?></td>
                                            <td><?= e($d['kondisi'] ?? '') ?></td>
                                            <td><?= e(rupiah($d['harga_barang'] ?? null)) ?></td>
                                            <td><?= e($d['nama_ruangan'] ?? '') ?></td>
                                            <td class="text-center">
                                                <a href="<?= e(route_url('edit', ['id' => $d['id']])) ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="<?= e(route_url('hapus', ['id' => $d['id']])) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">Data barang belum tersedia.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow border-0 mt-4">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <span>Menu Laporan Barang Rusak Pegawai</span>
                    <a href="<?= e(route_url('laporan_barang_rusak')) ?>" class="btn btn-light btn-sm"><i class="bi bi-clipboard-data"></i> Buka Menu</a>
                </div>

                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h5 class="mb-2">Laporan barang rusak dari pegawai sudah dipisahkan</h5>
                            <p class="text-muted mb-0">Data laporan barang rusak dari pegawai tidak ditampilkan sebagai tabel di dashboard admin. Laporan tersebut dibuka melalui menu tersendiri, yaitu <strong>Laporan Barang Rusak Pegawai</strong> pada sidebar admin.</p>
                        </div>
                        <div class="col-md-3 text-md-end mt-3 mt-md-0">
                            <a href="<?= e(route_url('laporan_barang_rusak')) ?>" class="btn btn-danger"><i class="bi bi-box-arrow-up-right"></i> Buka Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
