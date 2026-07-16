<?php
require_once __DIR__ . '/database.php';

class Laporan
{
    private mysqli $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll(?string $keyword = null, ?int $printId = null): mysqli_result
    {
        $where = [];
        $params = [];
        $types = '';

        if ($printId !== null && $printId > 0) {
            $where[] = 'id = ?';
            $params[] = $printId;
            $types .= 'i';
        }

        $keyword = trim((string) $keyword);
        if ($keyword !== '') {
            $where[] = "CONCAT_WS(' ',
                id,
                kode_barang,
                nup,
                nama_barang,
                merk_tipe,
                DATE_FORMAT(tgl_perolehan, '%d-%m-%Y'),
                DATE_FORMAT(tgl_perolehan, '%Y-%m-%d'),
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
                foto,
                DATE_FORMAT(created_at, '%d-%m-%Y %H:%i'),
                DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s')
            ) LIKE ?";
            $params[] = '%' . $keyword . '%';
            $types .= 's';
        }

        $sql = 'SELECT * FROM barang';
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY created_at DESC, id DESC';

        if (empty($params)) {
            return mysqli_query($this->conn, $sql);
        }

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);

        return mysqli_stmt_get_result($stmt);
    }

    public function getLaporanKerusakan(?string $keyword = null): mysqli_result
    {
        $keyword = trim((string) $keyword);
        $sql = "
            SELECT
                lk.*,
                b.kode_barang,
                b.nup,
                b.nama_barang,
                b.merk_tipe,
                b.nama_ruangan
            FROM laporan_kerusakan lk
            JOIN barang b ON b.id = lk.barang_id
        ";

        if ($keyword !== '') {
            $sql .= " WHERE CONCAT_WS(' ',
                lk.id,
                lk.nama_pelapor,
                lk.deskripsi_kerusakan,
                lk.status,
                b.kode_barang,
                b.nup,
                b.nama_barang,
                b.merk_tipe,
                b.nama_ruangan,
                DATE_FORMAT(lk.created_at, '%d-%m-%Y %H:%i'),
                DATE_FORMAT(lk.created_at, '%Y-%m-%d %H:%i:%s')
            ) LIKE ?";

            $stmt = mysqli_prepare($this->conn, $sql . ' ORDER BY lk.created_at DESC, lk.id DESC');
            $like = '%' . $keyword . '%';
            mysqli_stmt_bind_param($stmt, 's', $like);
            mysqli_stmt_execute($stmt);
            return mysqli_stmt_get_result($stmt);
        }

        return mysqli_query($this->conn, $sql . ' ORDER BY lk.created_at DESC, lk.id DESC');
    }

    public function getGrouped(): mysqli_result
    {
        return mysqli_query($this->conn, "
            SELECT
                nama_barang,
                kode_barang,
                merk_tipe,
                COUNT(*) as jumlah
            FROM barang
            GROUP BY nama_barang, kode_barang, merk_tipe
            ORDER BY nama_barang ASC
        ");
    }
}
