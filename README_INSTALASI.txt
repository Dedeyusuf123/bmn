PANDUAN INSTALASI SISTEM BMN BAWASLU

1. Buat database MySQL/MariaDB dengan nama: bmn_db
2. Import file: bmn_db.sql
3. Pastikan konfigurasi database pada file koneksi.php dan models/database.php sesuai dengan komputer/server Anda.
   Default:
   host: localhost
   user: root
   password: kosong
   database: bmn_db
4. Akses sistem melalui salah satu URL berikut:
   - http://localhost/nama_folder_project/
   - http://localhost/nama_folder_project/public/

AKUN LOGIN DEFAULT
1. Admin
   username: admin
   password: admin
   akses: dashboard, tambah barang, edit barang, hapus barang, laporan data barang, dan laporan barang rusak dari pegawai.

2. Pegawai
   username: pegawai
   password: pegawai
   akses: hanya mengisi form Lapor Barang Rusak.
   Pegawai tidak bisa melihat data laporan barang, tambah barang, edit barang, hapus barang, atau melihat dashboard admin.

3. Pimpinan
   username: pimpinan
   password: pimpinan
   akses: hanya melihat dan mencetak Laporan Data Barang BMN serta Laporan Barang Rusak dari Pegawai.
   Pimpinan tidak bisa tambah barang, edit barang, hapus barang, atau mengubah database barang.

CATATAN REVISI TERBARU
- Ditambahkan role pengguna: admin, pegawai, dan pimpinan.
- Pegawai hanya bisa mengakses halaman Lapor Barang Rusak.
- Pimpinan hanya bisa mengakses halaman Laporan Data Barang BMN dan Laporan Barang Rusak dari Pegawai.
- Hak akses tambah, edit, dan hapus barang hanya diberikan kepada admin.
- Form Edit Barang sudah disamakan opsinya dengan Form Tambah Barang.
  Dropdown yang disamakan:
  a. Kondisi
  b. Kondisi Inventarisasi
  c. Tercatat Inventarisasi
  d. Status Kodefikasi
  e. Status Inventarisasi
- Ditambahkan tabel laporan_kerusakan pada bmn_db.sql.
- Sistem akan mencoba menambahkan kolom role dan tabel laporan_kerusakan secara otomatis jika database lama sudah terpasang.
- Form tambah barang dan edit barang mendukung upload foto barang.
- Fitur laporan sudah dipisahkan menjadi dua halaman: Laporan Data Barang BMN untuk data inventaris, dan Laporan Barang Rusak dari Pegawai untuk laporan kerusakan yang dikirim pegawai.
- Menu Lapor Barang Rusak khusus pegawai hanya berisi form pengiriman laporan kerusakan, bukan tabel data laporan.
- Laporan dapat dicetak seluruhnya, dicari, atau dicetak per barang.
- Foto barang/kerusakan disimpan di folder public/uploads/barang.
