# UNIMARCH - UNIMA Archive Management

Sistem arsip surat perpustakaan berbasis web. UNIMARCH membantu pencatatan, pemrosesan, disposisi, dan pengarsipan surat dalam satu alur, dari pengirim, staff, hingga pimpinan.

**Live demo:** https://unimarch.my.id

## Akun Uji Coba

Akun berikut khusus untuk demo dan penilaian.

| Role | Email | Password |
|---|---|---|
| Pengirim | `pengirim@unimarch.my.id` | `password` |
| Staff | `staff@unimarch.my.id` | `password` |
| Pimpinan | `pimpinan@unimarch.my.id` | `password` |

## Fitur

### Umum
- Login, register, dan lupa password
- Pengaturan akun
- Halaman error khusus (403, 404, 419, 429, 500)
- Pembatasan akses berdasarkan role

### Pengirim
- Dashboard ringkasan surat
- Membuat surat baru
- Daftar surat yang pernah dikirim
- Detail surat beserta status terkini

### Staff
- Dashboard ringkasan surat masuk
- Daftar surat masuk
- Detail dan pemrosesan surat
- Pemberian nomor agenda surat

### Pimpinan
- Dashboard ringkasan surat
- Daftar surat
- Detail surat
- Pemberian disposisi

### Arsip dan Pelacakan
- Halaman arsip surat
- Riwayat perubahan status surat

## Teknologi

- PHP dan framework Laravel
- MySQL
- Blade (templating)
- Chart.js (grafik di dashboard, dimuat lewat CDN)
- Composer (pengelola dependensi PHP)

## Cara Instalasi Lokal

Prasyarat: PHP 8.2 atau lebih baru, Composer, dan MySQL (misalnya lewat XAMPP).

1. Clone repositori:
   ```
   git clone https://github.com/azizahalamri40-max/unimarch.git
   cd unimarch
   ```
2. Pasang dependensi:
   ```
   composer install
   ```
3. Salin file konfigurasi, lalu buat kunci aplikasi:
   ```
   copy .env.example .env
   php artisan key:generate
   ```
   (Di Linux/macOS gunakan `cp .env.example .env`.)
4. Buat database kosong di MySQL (misalnya `unimarch`), lalu isi pengaturan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di file `.env`.
5. Jalankan migration dan seeder untuk membuat tabel dan akun demo:
   ```
   php artisan migrate --seed
   ```
   Password akun demo diambil dari `SEEDER_PASSWORD` di `.env`. Jika tidak diisi, dipakai `password123`.
6. Jalankan aplikasi:
   ```
   php artisan serve
   ```
7. Buka http://127.0.0.1:8000 di browser.

## Anggota Kelompok 4

| No | Nama | NIM | Kontribusi |
|---|---|---|---|
| 1 | Azizah Alamri | 25210072 | Database dan hosting, modul pimpinan, README |
| 2 | Nova Elisa Tompunu | 25210131 | Modul staff, tampilan antarmuka |
| 3 | Asmasari Djaelani | 25210079 | Modul pengirim, pengujian, laporan |

## Catatan

Projek ini dibuat untuk mata kuliah Pemrograman Berorientasi Objek.
