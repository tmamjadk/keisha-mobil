# Keisha Mobil — Marketplace Mobil (PHP + MySQL)

Fitur:
- Website katalog mobil responsif
- Pencarian dan filter mobil
- Tombol WhatsApp dengan pesan otomatis per mobil
- Halaman detail mobil
- Login admin
- Dashboard admin
- CRUD mobil (tambah, lihat, edit, hapus)
- Upload foto mobil
- Pengaturan nama toko, WhatsApp, alamat, jam operasional, deskripsi
- CSRF token, password hashing, prepared statements, validasi upload
- Database MySQL melalui `database.sql`

## Persyaratan
- XAMPP dengan Apache, PHP 7.4+ (disarankan PHP 8.x), MySQL/MariaDB
- Browser modern

## Instalasi di Mac dengan XAMPP
1. Ekstrak ZIP `keisha-mobil.zip`.
2. Pindahkan folder `keisha-mobil` ke:
   `/Applications/XAMPP/xamppfiles/htdocs/`
3. Buka XAMPP Manager dan nyalakan **Apache** dan **MySQL**.
4. Buka phpMyAdmin:
   `http://localhost/phpmyadmin`
5. Pilih tab **Import**, pilih file `database.sql` dari folder `keisha-mobil`, lalu klik **Import/Go**.
   Alternatif: buka tab SQL dan jalankan isi file `database.sql`.
6. Periksa pengaturan database di `config.php`. Default XAMPP lokal:
   - host: `localhost`
   - user: `root`
   - password: kosong
   - database: `keisha_mobil`
7. Buka:
   `http://localhost/keisha-mobil/`
8. Login admin:
   - Username: `admin`
   - Password: `KeishaMobil!2026`
9. **Segera ubah password admin** dari menu admin setelah login.

## Mengubah nomor WhatsApp dan alamat
Login admin → **Pengaturan Toko**. Isi nomor WhatsApp dengan kode negara tanpa `+`, spasi, atau angka 0 awal.
Contoh: nomor Indonesia `081234567890` ditulis `6281234567890`.

## Menambah mobil
Login admin → **Tambah Mobil**. Isi merek, model, harga, tahun, transmisi, bahan bakar, kapasitas, kilometer, deskripsi, status, dan foto.

## Catatan penting
- Data mobil awal adalah contoh/dummy; ubah atau hapus sebelum dipakai sungguhan.
- Untuk publikasi internet, gunakan hosting dengan HTTPS, ganti password admin, dan atur kredensial database yang aman. Jangan gunakan konfigurasi lokal `root` tanpa password di hosting.
- Website ini belum memproses pembayaran online. Transaksi dan negosiasi dilakukan langsung antara pembeli dan penjual.
