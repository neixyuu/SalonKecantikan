# Aplikasi Reservasi AETH Clinic

Aplikasi reservasi salon kecantikan premium yang dibangun menggunakan Laravel 13, Tailwind CSS v4, dan MySQL. Sistem ini dirancang untuk memudahkan pelanggan melakukan reservasi layanan salon dan admin dalam mengelola data.

## Spesifikasi Teknis

- **Framework PHP:** Laravel 13.x (PHP 8.3)
- **CSS Framework:** Tailwind CSS v4 (via `@tailwindcss/vite`)
- **Database:** MySQL
- **Autentikasi:** Custom Auth Manual (dengan Middleware Role & Status Akun)
- **Alert System:** SweetAlert2 (Session flash messages)
- **Design Style:** AETH Clinic — Elegant Minimalist (inspired by Nude Wix template)

## Fitur Utama

### Aktor: Pelanggan
- Register akun baru (status default: pending).
- Login (akses penuh ke fitur pelanggan jika akun sudah verified).
- Lihat status verifikasi akun.
- Katalog layanan/treatment.
- Buat reservasi jadwal treatment.
- Upload bukti pembayaran untuk reservasi yang disetujui.
- Lihat riwayat reservasi.
- Lihat pengumuman salon (mendukung gambar dan video YouTube embed).

### Aktor: Admin
- Verifikasi pendaftaran pelanggan (Approve/Reject).
- Verifikasi reservasi (Approve/Reject).
- Verifikasi pembayaran.
- Kelola (CRUD) Layanan Treatment.
- Kelola (CRUD) Pengumuman.
- Dashboard statistik lengkap.

## ERD (Entity Relationship Diagram)

Sistem ini menggunakan 5 tabel utama yang saling berelasi:

1. **`users`**: Menyimpan data akun admin dan pelanggan. Kolom tambahan: `role`, `account_status`, `phone`.
2. **`treatments`**: Menyimpan katalog layanan salon (nama, deskripsi, harga, durasi, gambar).
3. **`reservations`**: Menyimpan data reservasi pelanggan (terhubung ke `users` dan `treatments`).
4. **`payments`**: Menyimpan data pembayaran (terhubung ke `reservations`, dengan kolom bukti transfer).
5. **`announcements`**: Menyimpan pengumuman dari admin (terhubung ke `users` admin, mendukung gambar dan video URL).

## Prasyarat Instalasi

Pastikan sistem Anda sudah terinstal:
- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL Server (misal: XAMPP)

## Panduan Instalasi (Lokal)

1. **Clone repository ini** (jika ada) atau letakkan di folder server lokal Anda.
2. Buka terminal, masuk ke direktori proyek:
   ```bash
   cd e:\DEv_Stud\LSP\SalonKecantikan
   ```
3. **Install dependensi PHP dan Node.js:**
   ```bash
   composer install
   npm install
   ```
4. **Konfigurasi Environment:**
   Buka file `.env` dan pastikan konfigurasi database sudah benar:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=salonkecantikan
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Kosongkan password jika Anda menggunakan XAMPP secara default)*
5. **Jalankan Migrasi Database dan Seeder:**
   *(Pastikan database `salonkecantikan` sudah dibuat di MySQL/phpMyAdmin)*
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Seeder ini akan otomatis membuatkan akun admin dan beberapa data treatment default.*
6. **Hubungkan Folder Storage (untuk upload gambar):**
   ```bash
   php artisan storage:link
   ```
7. **Compile Assets (Tailwind CSS):**
   ```bash
   npm run build
   # atau untuk mode development:
   # npm run dev
   ```
8. **Jalankan Laravel Server:**
   ```bash
   php artisan serve
   ```
   Buka browser dan akses `http://localhost:8000`.

## Akun Demo (Default)

Setelah menjalankan seeder, Anda dapat login menggunakan akun berikut:

- **Admin**
  - Email: `admin@salon.com`
  - Password: `password123`

Untuk mencoba alur pelanggan, silakan buat akun baru melalui halaman Register, lalu verifikasi akun tersebut menggunakan akun Admin.

## Urutan Testing (End-to-End Flow)

1. Buka halaman utama (Landing Page).
2. Klik **Daftar** dan buat akun pelanggan baru.
3. Anda akan diarahkan ke halaman "Status Akun" dengan status **Menunggu Verifikasi**.
4. Logout, lalu Login sebagai **Admin**.
5. Masuk ke menu **Verifikasi Akun Pelanggan**, temukan akun yang baru dibuat, lalu klik **Setujui**.
6. Logout dari Admin, Login kembali sebagai **Pelanggan**.
7. Anda sekarang memiliki akses ke Dashboard.
8. Masuk ke menu **Layanan** atau klik **Reservasi Baru**, pilih treatment, tanggal, dan jam, lalu Submit.
9. Status reservasi akan menjadi **Menunggu**.
10. Login kembali sebagai **Admin**, masuk ke **Verifikasi Reservasi**, lalu setujui reservasi tersebut.
11. Login kembali sebagai **Pelanggan**, periksa riwayat reservasi, klik tombol **Upload Bukti Pembayaran**, lalu unggah gambar bukti transfer.
12. Terakhir, Login sebagai **Admin**, masuk ke **Verifikasi Pembayaran**, dan setujui pembayarannya. Alur selesai!
