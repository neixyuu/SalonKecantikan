# Dokumentasi Proyek: Sistem Reservasi AETH Clinic

AETH Clinic adalah aplikasi berbasis web premium yang dirancang untuk mengelola reservasi layanan salon kecantikan dan klinik perawatan secara digital. Sistem ini menjembatani interaksi antara pelanggan (untuk pemesanan layanan) dan administrator (untuk manajemen operasional dan verifikasi).

Proyek ini difokuskan pada fungsionalitas *end-to-end* yang mulus dipadukan dengan antarmuka pengguna (UI) yang sangat estetis dan minimalis, memberikan kesan layanan kelas atas kepada penggunanya.

---

## 1. Arsitektur & Teknologi

Aplikasi ini dibangun menggunakan tumpukan teknologi (tech stack) modern dengan pendekatan *server-side rendering* (SSR):

- **Backend Framework:** Laravel 13 (PHP 8.3)
- **Frontend Styling:** Tailwind CSS v4 (melalui integrasi Vite)
- **Database Management:** MySQL (Relational Database)
- **Sistem Notifikasi UI:** SweetAlert2 untuk konfirmasi aksi dan *flash messages*.
- **Ikonografi & Tipografi:** Mengandalkan integrasi SVG kustom, Google Fonts (Playfair Display & Instrument Sans) untuk nuansa elegan.

---

## 2. Fitur Utama Sistem

Sistem membagi pengguna ke dalam dua peran utama, masing-masing dengan antarmuka dan hak akses spesifik.

### Panel Administrator (Dashboard Admin)
Panel kendali terpusat bagi staf atau pengelola klinik untuk menjalankan operasional bisnis.
- **Manajemen Akun Terpusat:** Admin memverifikasi setiap pendaftaran akun baru sebelum pelanggan diizinkan melakukan reservasi.
- **Verifikasi Reservasi:** Admin meninjau permohonan jadwal treatment dari pelanggan berdasarkan ketersediaan.
- **Validasi Pembayaran:** Admin memeriksa bukti transfer yang diunggah pelanggan (dengan fitur pratinjau overlay gambar).
- **Manajemen Layanan (Katalog):** Modul CRUD untuk menambah, mengedit, atau menghapus layanan/treatment beserta harga, durasi, dan gambar referensi.
- **Pusat Informasi (Pengumuman):** Admin dapat menyebarkan informasi publik yang terintegrasi dengan embed video (mis. YouTube) dan gambar.
- **Sistem Histori Kekal (Immutable History):** Setiap keputusan persetujuan atau penolakan bersifat final dan terekam sebagai histori (badge read-only), mencegah perubahan yang tidak disengaja.

### Portal Pelanggan
Antarmuka publik dan area anggota yang dirancang untuk pengalaman pengguna yang intuitif.
- **Autentikasi Terkontrol:** Sistem registrasi yang menempatkan pengguna dalam status "Menunggu Verifikasi" demi keamanan komunitas klinik.
- **Katalog Visual:** Penjelajahan layanan klinik yang disajikan dalam bentuk grid estetik, lengkap dengan rincian durasi dan harga.
- **Booking Engine:** Formulir pemesanan jadwal yang membatasi input pada jam operasional klinik (09:00 - 20:00).
- **Pelacakan Status:** Pelanggan dapat memantau status reservasi mereka secara *real-time* (Menunggu, Disetujui, Ditolak).
- **Portal Pembayaran:** Integrasi formulir unggah bukti transfer yang aman setelah jadwal disetujui.

---

## 3. Alur Kerja & Logika Bisnis (Workflow)

Sistem ini menerapkan validasi tiga lapis (Triple Verification) untuk memastikan keabsahan data:

1. **Fase Registrasi:** Pengguna mendaftar -> Admin memverifikasi akun -> Pengguna mendapat akses penuh.
2. **Fase Reservasi:** Pelanggan memilih treatment & waktu -> Admin memverifikasi jadwal -> Reservasi disetujui.
3. **Fase Transaksi:** Pelanggan mengunggah bukti bayar -> Admin memverifikasi validitas pembayaran -> Transaksi selesai (Lunas).

---

## 4. Desain Sistem & Antarmuka (UI/UX)

Aplikasi ini menggunakan sistem desain kustom yang ketat untuk menjaga konsistensi visual:
- **Skema Warna "Nude":** Palet didominasi oleh warna-warna bumi (*earth tones*) seperti *Cream*, *Blush*, *Tan*, dan *Charcoal* untuk memancarkan aura kemewahan, kebersihan, dan ketenangan.
- **Tipografi:** Kombinasi *Playfair Display* (untuk judul/heading yang elegan) dan *Instrument Sans* (untuk teks paragraf yang bersih dan mudah dibaca).
- **Komponen Glassmorphism & Transparansi:** Penggunaan efek blur, shadow yang sangat lembut, serta *overlay modal* untuk menampilkan konten gambar tanpa mengganggu konteks halaman.
- **Layout Responsif:** Tata letak yang beradaptasi secara dinamis (misalnya sidebar admin yang berubah menjadi menu navigasi atas pada layar kecil).

---

## 5. Struktur Basis Data (ERD Overview)

Sistem bergantung pada 5 entitas utama yang saling terkait erat (Relational Schema):
- `Users`: Entitas sentral yang menyimpan data autentikasi dan profil.
- `Treatments`: Entitas master yang menyimpan daftar layanan klinik.
- `Reservations`: Entitas transaksional yang menghubungkan `Users` dan `Treatments` berdasarkan waktu (waktu pemesanan).
- `Payments`: Entitas dependen yang terikat pada `Reservations` untuk menampung data bukti transaksi.
- `Announcements`: Entitas publikasi yang dibuat oleh `Users` (dengan peran Admin) untuk dikonsumsi pelanggan.

---

## 6. Keamanan dan Kontrol Akses

- **Middleware Kustom:** Aplikasi menggunakan perlindungan lapisan ganda (`CheckRole` dan `CheckAccountVerified`) untuk memastikan bahwa halaman hanya dapat diakses oleh peran yang memiliki otorisasi tepat pada status akun yang valid.
- **Proteksi Rute (Route Protection):** Semua rute transaksional dijaga dari akses pengguna tamu (*guest*).
- **Form Protection:** Pencegahan CSRF pada seluruh permintaan formulir (*form submission*).

---
*Dokumentasi ini memberikan gambaran tingkat tinggi (high-level overview) dari arsitektur dan fungsionalitas sistem, ditujukan untuk analisis struktural dan tinjauan fitur aplikasi AETH Clinic.*
