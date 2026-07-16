-- Database: bmn_db
-- Revisi role pengguna: admin, pegawai, pimpinan
-- Admin: mengelola data barang BMN
-- Pegawai: hanya mengirim laporan barang rusak melalui form Lapor Barang Rusak
-- Pimpinan: hanya melihat dan mencetak Laporan Data Barang BMN serta Laporan Barang Rusak dari Pegawai

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `nup` varchar(50) DEFAULT NULL,
  `nama_barang` varchar(150) DEFAULT NULL,
  `merk_tipe` varchar(100) DEFAULT NULL,
  `tgl_perolehan` date DEFAULT NULL,
  `kondisi` varchar(50) DEFAULT NULL,
  `harga_barang` decimal(15,2) DEFAULT NULL,
  `kondisi_inv` varchar(50) DEFAULT NULL,
  `harga_barang_inv` decimal(15,2) DEFAULT NULL,
  `status_penggunaan_inv` varchar(100) DEFAULT NULL,
  `tercatat_inv` varchar(100) DEFAULT NULL,
  `kode_ruangan` varchar(50) DEFAULT NULL,
  `nama_ruangan` varchar(100) DEFAULT NULL,
  `status_kodefikasi` varchar(100) DEFAULT NULL,
  `status_inventarisasi` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin'),
(2, 'pegawai', 'pegawai', 'pegawai'),
(3, 'pimpinan', 'pimpinan', 'pimpinan');

CREATE TABLE `laporan_kerusakan` (
  `id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama_pelapor` varchar(100) DEFAULT NULL,
  `deskripsi_kerusakan` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Diajukan',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `laporan_kerusakan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_id` (`barang_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `laporan_kerusakan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
