# AETH Clinic - Management & Reservation System

<div align="center">
  <h3>Sistem Informasi Manajemen Reservasi Klinik Kecantikan</h3>
  <p>Dibangun menggunakan <strong>Laravel 13</strong> dan <strong>Tailwind CSS</strong></p>
</div>

---

##  Deskripsi Proyek
**AETH Clinic** adalah sebuah sistem manajemen reservasi layanan (treatment) salon dan klinik kecantikan. Sistem ini memfasilitasi dua sisi pengguna, yaitu **Pelanggan** untuk melakukan pemesanan jadwal perawatan, dan **Admin** untuk mengelola seluruh aspek operasional mulai dari verifikasi pelanggan, manajemen reservasi, hingga validasi pembayaran.

Aplikasi ini mengedepankan keamanan transaksi melalui *3-Layer Verification Flow* (Verifikasi Akun, Verifikasi Jadwal Reservasi, dan Verifikasi Bukti Pembayaran).

##  Fitur Utama

### Admin Panel
- **Manajemen Pengguna (User Verification)**: Menyetujui atau menolak akun baru pelanggan untuk mencegah *spam/fake accounts*.
- **Manajemen Layanan (Treatment CRUD)**: Menambah, mengubah, menghapus, dan menampilkan daftar layanan klinik beserta harga dan durasi.
- **Validasi Reservasi**: Menyetujui atau menolak jadwal *treatment* yang diajukan pelanggan.
- **Validasi Pembayaran**: Memeriksa dan memverifikasi bukti transfer pembayaran dari pelanggan.
- **Manajemen Pengumuman/Promo**: Mengelola informasi publik yang ditampilkan di beranda (*support* *embed* video YouTube).

### Portal Pelanggan
- **Katalog Layanan**: Melihat daftar *treatment* yang tersedia di AETH Clinic.
- **Sistem Pemesanan (Reservation)**: Melakukan *booking* jadwal *treatment* secara mandiri.
- **Riwayat Reservasi**: Memantau status reservasi (Menunggu, Disetujui, Ditolak).
- **Upload Bukti Pembayaran**: Mengunggah foto bukti transfer langsung melalui sistem.
- **Dashboard Akun**: Melihat ringkasan status akun dan pengumuman dari klinik.

---

## Spesifikasi & Dependensi

Proyek ini menggunakan *stack* teknologi modern untuk memastikan performa yang cepat dan pengalaman pengembangan (DX) yang baik.

### Spesifikasi Sistem
- **Arsitektur**: Model-View-Controller (MVC)
- **Bahasa**: PHP (Backend), JavaScript (Frontend/Interactions)
- **Database**: MySQL (Direkomendasikan) / SQLite (Untuk *development*)
- **Frontend Build Tool**: Vite

### Dependensi Utama (Tech Stack)
- **Framework**: [Laravel ^13.8](https://laravel.com/) (PHP ^8.3)
- **Styling**: [Tailwind CSS](https://tailwindcss.com/) (via NPM)
- **Alerts & Modals**: [SweetAlert2](https://sweetalert2.github.io/)
- **Icons**: SVG Heroicons

---

## Panduan Instalasi (Development)

Berikut adalah panduan langkah demi langkah untuk menjalankan proyek ini di *environment* lokal Anda.

### Persyaratan Minimum (Prerequisites)
Pastikan sistem Anda sudah terinstal:
- **PHP** >= 8.3
- **Composer** (Dependency Manager untuk PHP)
- **Node.js** dan **NPM**
- **MySQL** / MariaDB (jika tidak ingin menggunakan SQLite)

### Langkah-langkah Instalasi

1. **Clone repositori ini** (Jika proyek berada di GitHub)
   ```bash
   git clone <url-repository>
   cd SalonKecantikan
   ```

2. **Install dependensi PHP (Composer)**
   ```bash
   composer install
   ```

3. **Install dependensi Node.js (NPM)**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   Salin file konfigurasi bawaan Laravel.
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` di teks editor, lalu atur koneksi database Anda (biasanya `DB_CONNECTION`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database & Seeding**
   Jalankan perintah ini untuk membuat struktur tabel di database dan mengisi *dummy data* (jika *seeder* sudah dikonfigurasi).
   ```bash
   php artisan migrate --seed
   ```

7. **Storage Link (Penting untuk Gambar)**
   Sistem ini menggunakan local storage untuk menyimpan bukti pembayaran dan gambar treatment. Anda harus membuat *symlink*.
   ```bash
   php artisan storage:link
   ```

8. **Jalankan Aplikasi Lokal (Development Server)**
   Anda perlu menjalankan dua *service* secara bersamaan.
   
   Jalankan server PHP:
   ```bash
   php artisan serve
   ```
   Buka terminal/tab baru dan jalankan Vite (*asset bundler* Tailwind):
   ```bash
   npm run dev
   ```

9. **Selesai!** 
   Buka browser dan akses aplikasi di: `http://localhost:8000`

---

## Struktur Direktori Penting

Sebagai gambaran untuk pengembang, berikut lokasi modul-modul penting:
- `app/Models/` : Relasi database dan logika enkapsulasi (*Accessors*).
- `app/Http/Controllers/Admin/` : Semua *controller* yang mengelola fitur Dashboard Administrator.
- `app/Http/Controllers/Pelanggan/` : Semua *controller* untuk interaksi Customer.
- `resources/views/layouts/` : Berisi `app.blade.php`, layout utama dengan *role-based UI check*.
- `routes/web.php` : Peta navigasi (*Routing*) utama, dilengkapi grup *middleware* otentikasi dan *role*.

## Lisensi
Proyek ini bersifat *Open Source* di bawah lisensi [MIT license](https://opensource.org/licenses/MIT).
