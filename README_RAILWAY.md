# Deploy ke Railway

Project ini sudah disiapkan agar bisa langsung di-deploy ke Railway menggunakan Docker
(file `Dockerfile` disertakan). Koneksi database sudah diubah agar membaca kredensial
dari environment variable (bukan hardcode `localhost`), supaya cocok dengan database
MySQL yang disediakan Railway.

## Langkah-langkah

1. **Push project ini ke sebuah GitHub repository** (atau upload langsung via Railway CLI).

2. **Buat Project baru di Railway**
   - Klik **New Project** > **Deploy from GitHub repo**, pilih repo ini.
   - Railway akan otomatis mendeteksi `Dockerfile` dan mem-build image.

3. **Tambahkan database MySQL**
   - Di project yang sama, klik **New** > **Database** > **Add MySQL**.
   - Railway otomatis membuat variabel: `MYSQLHOST`, `MYSQLUSER`, `MYSQLPASSWORD`,
     `MYSQLDATABASE`, `MYSQLPORT` pada service database tersebut.

4. **Hubungkan variabel database ke service aplikasi (web)**
   - Buka service aplikasi (bukan database) > tab **Variables**.
   - Tambahkan variabel berikut, arahkan ke variabel milik service MySQL memakai referensi
     `${{MySQL.MYSQLHOST}}` dst. (Railway biasanya menawarkan opsi "Add Reference" otomatis):
     - `MYSQLHOST` = `${{MySQL.MYSQLHOST}}`
     - `MYSQLUSER` = `${{MySQL.MYSQLUSER}}`
     - `MYSQLPASSWORD` = `${{MySQL.MYSQLPASSWORD}}`
     - `MYSQLDATABASE` = `${{MySQL.MYSQLDATABASE}}`
     - `MYSQLPORT` = `${{MySQL.MYSQLPORT}}`
   - Aplikasi (`koneksi.php` & `models/database.php`) sudah otomatis membaca variabel ini
     lewat `db_env.php`.

5. **Import skema database (`bmn_db.sql`)**
   Railway tidak meng-import file SQL secara otomatis. Pilih salah satu cara:
   - **Railway CLI**: `railway connect MySQL` lalu jalankan `source bmn_db.sql;` di dalam
     prompt MySQL, atau:
     `railway run mysql -h $MYSQLHOST -u $MYSQLUSER -p$MYSQLPASSWORD -P $MYSQLPORT $MYSQLDATABASE < bmn_db.sql`
   - **MySQL client lokal**: ambil kredensial dari tab Variables service MySQL (Railway
     menyediakan connection string publik), lalu:
     `mysql -h <host> -P <port> -u <user> -p<password> <database> < bmn_db.sql`
   - **Adminer/phpMyAdmin**: deploy template Adminer terpisah di Railway lalu import via GUI.

   Catatan: aplikasi ini juga punya fungsi `ensure_app_schema()` yang otomatis menambah
   kolom/tabel baru (role, laporan_kerusakan, dst) jika tabel `barang` & `user` sudah ada
   duluan — tapi tabel awal tetap harus diimport manual dari `bmn_db.sql`.

6. **Set domain**
   - Di tab **Settings** service aplikasi, klik **Generate Domain** untuk mendapatkan URL publik.
   - Aplikasi otomatis listen di port yang diberikan Railway lewat variabel `PORT`
     (ditangani oleh `docker-entrypoint.sh`), jadi tidak perlu setting manual.

## Catatan penting: penyimpanan foto upload

Foto barang/kerusakan disimpan ke folder `public/uploads/barang` di dalam container.
Filesystem container Railway bersifat **ephemeral** — setiap kali redeploy, isi folder ini
akan hilang. Jika upload foto perlu permanen, tambahkan **Railway Volume** dan mount ke
path `/var/www/html/public/uploads` lewat tab **Settings > Volumes** pada service aplikasi.

## Testing build secara lokal (opsional)

```bash
docker build -t bmn-app .
docker run -p 8080:8080 \
  -e MYSQLHOST=host.docker.internal \
  -e MYSQLUSER=root \
  -e MYSQLPASSWORD= \
  -e MYSQLDATABASE=bmn_db \
  -e MYSQLPORT=3306 \
  bmn-app
```

Lalu akses `http://localhost:8080`.

## Akun login default (setelah import bmn_db.sql)

| Role     | Username | Password |
|----------|----------|----------|
| Admin    | admin    | admin    |
| Pegawai  | pegawai  | pegawai  |
| Pimpinan | pimpinan | pimpinan |

**Segera ganti password default ini setelah aplikasi live di production.**
