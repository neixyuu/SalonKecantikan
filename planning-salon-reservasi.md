# Planning Aplikasi Reservasi Salon Kecantikan (Laravel)

## 1. Ringkasan Use Case & Flow

### Aktor: Pelanggan
1. **Daftar akun** → status `pending`
2. **Login** (hanya bisa penuh akses kalau status `verified`)
3. **Lihat status pendaftaran** (pending/verified/rejected)
4. **Reservasi jadwal perawatan** → pilih treatment, tanggal, jam → status reservasi `pending`
5. **Lihat status reservasi** (pending/approved/rejected)
6. **Upload bukti pembayaran** (setelah reservasi approved) → status pembayaran `pending`
7. **Lihat pengumuman**

### Aktor: Admin
1. **Login**
2. **Verifikasi pendaftaran akun** (approve/reject)
3. **Verifikasi reservasi** (approve/reject)
4. **Verifikasi pembayaran** (approve/reject)
5. **Kelola pengumuman** (create, update, delete) — dengan gambar/video

### Flow keseluruhan (end-to-end)
```
Register → (pending) → Admin approve → Login penuh
  → Pelanggan reservasi treatment → (pending) → Admin approve/reject
  → Jika approved → Pelanggan upload bukti bayar → (pending) → Admin approve/reject
  → Reservasi lunas → selesai
```

---

## 2. ERD / Struktur Database (5 tabel)

### `users`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | varchar | |
| email | varchar unique | |
| password | varchar | |
| role | enum('pelanggan','admin') | default pelanggan |
| account_status | enum('pending','verified','rejected') | default pending |
| phone | varchar | nullable |
| created_at, updated_at | timestamp | |

### `treatments`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | varchar | |
| description | text | |
| price | decimal | |
| image | varchar | path gambar |
| created_at, updated_at | timestamp | |

### `reservations`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| user_id | FK → users | |
| treatment_id | FK → treatments | |
| schedule_date | date | |
| schedule_time | time | |
| status | enum('pending','approved','rejected') | default pending |
| notes | text | nullable |
| created_at, updated_at | timestamp | |

### `payments`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| reservation_id | FK → reservations | |
| proof_image | varchar | bukti transfer |
| amount | decimal | |
| status | enum('pending','approved','rejected') | default pending |
| created_at, updated_at | timestamp | |

### `announcements`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| admin_id | FK → users | |
| title | varchar | |
| content | text | |
| image | varchar | nullable |
| video_url | varchar | nullable (embed YouTube atau upload) |
| created_at, updated_at | timestamp | |

**Relasi:**
- User (pelanggan) `hasMany` Reservation
- Treatment `hasMany` Reservation
- Reservation `hasOne` Payment
- User (admin) `hasMany` Announcement

---

## 3. Struktur Route (garis besar)

```
# Auth (Breeze)
GET/POST /register
GET/POST /login
POST /logout

# Pelanggan (middleware: auth, role:pelanggan, verified_account)
GET  /dashboard
GET  /status-akun
GET  /treatments               (lihat daftar layanan)
GET  /reservations/create
POST /reservations
GET  /reservations              (riwayat + status milik sendiri)
GET  /payments/create/{reservation}
POST /payments
GET  /announcements

# Admin (middleware: auth, role:admin)
GET  /admin/dashboard
GET  /admin/users               (daftar pendaftaran)
PATCH /admin/users/{id}/verify  (approve/reject)
GET  /admin/reservations
PATCH /admin/reservations/{id}/verify
GET  /admin/payments
PATCH /admin/payments/{id}/verify
Resource route: /admin/announcements (CRUD penuh)
Resource route: /admin/treatments (CRUD, buat isi data awal)
```

---

## 4. Struktur Folder Utama Laravel

```
app/
  Models/ User, Treatment, Reservation, Payment, Announcement
  Http/Controllers/
    Auth/ (bawaan Breeze)
    Pelanggan/ ReservationController, PaymentController, AnnouncementController
    Admin/ UserVerificationController, ReservationVerificationController,
           PaymentVerificationController, AnnouncementController, TreatmentController
  Http/Middleware/ CheckRole, CheckAccountVerified
database/
  migrations/ (5 tabel di atas)
  seeders/ TreatmentSeeder, AdminUserSeeder
resources/views/
  layouts/ app.blade.php (navbar beda utk admin/pelanggan)
  auth/ (Breeze default, tambah field phone)
  pelanggan/ dashboard, status-akun, treatments, reservations, payments, announcements
  admin/ dashboard, users, reservations, payments, announcements, treatments
public/storage/ (symlink utk gambar upload)
```

Stack: **Laravel (starter kit: None/Blade polos) + Tailwind + MySQL**. Auth dibuat manual (register, login, middleware role & status akun) — bukan pakai Breeze/Livewire/React/Vue, biar full kontrol dan sesuai kriteria "framework php" tanpa overhead API terpisah.

---

## 5. Kebutuhan Sesuai Kriteria Penilaian

| Kriteria | Cara pemenuhan |
|---|---|
| Framework PHP | Laravel |
| CSS Framework | Tailwind CSS (instalasi manual via npm, bukan bawaan starter kit) |
| Database MySQL | 5 tabel relasional |
| Auth & otorisasi | Breeze + middleware role + status akun |
| CRUD | Announcement, Treatment, Reservation, Payment |
| Alert saat error/sukses | Session flash message + SweetAlert2 atau alert Bootstrap/Tailwind |
| Multimedia | Gambar treatment, bukti bayar, gambar/video pengumuman |
| Version control | Git + GitHub, commit bertahap per fitur (bukan 1 commit besar) |
| Dokumentasi README | Instalasi, spesifikasi, dependensi, ERD, cara pakai |

---

## 6. Urutan Pengerjaan

Catatan: 10 jam di soal adalah batas waktu ujian, bukan alokasi waktu per tahap. Urutan kerja berikut disusun logis (dependency-based), bukan per jam.

1. Setup project Laravel (starter: None) → install Tailwind CSS manual via npm/Vite → koneksi MySQL
2. Migration 5 tabel + model + relasi
3. Seeder (akun admin default, beberapa treatment awal)
4. Auth manual: register (default status pending), login, middleware role (`admin`/`pelanggan`) & middleware status akun (`verified`)
5. Modul pelanggan: dashboard, lihat status akun, lihat treatment
6. Modul reservasi: pelanggan buat reservasi → admin verifikasi
7. Modul pembayaran: pelanggan upload bukti bayar → admin verifikasi
8. Modul pengumuman: CRUD admin, tampil ke pelanggan
9. Modul verifikasi akun: admin approve/reject pendaftaran
10. Alert (sukses/gagal) di semua aksi penting + validasi form
11. Testing end-to-end seluruh flow (register → verifikasi → reservasi → bayar)
12. README.md (instalasi, spesifikasi, dependensi, ERD) + push final ke GitHub

**Saran:** commit ke GitHub tiap selesai satu modul (bukan cuma di akhir), biar histori commit progresif — ini poin penilaian "manajemen proyek".

---

## 7. Referensi Desain

Referensi: Template Wix **"Nude"** (dari kategori Makeup Store) — sudah dilihat langsung screenshot-nya, jadi detail berikut akurat (bukan asumsi lagi).

### Ciri visual yang teramati
- **Background:** putih bersih, section diselingi abu-abu sangat terang (`bg-gray-50`) untuk kotak produk
- **Logo/brand text:** nama brand pakai font serif tipis elegan, warna gradasi tan/gold-ish
- **Navbar:** teks uppercase kecil, spasi antar huruf lebar (letter-spacing), sangat minimal — cuma teks, gak ada background warna
- **Hero section:** foto editorial full-width (model/orang), teks kecil uppercase di atas ("DISCOVER YOUR NEW ADDICTION") lalu judul besar serif, tombol outline kecil bertuliskan "SHOP NOW >"
- **Button style:** border tipis (outline, bukan solid fill), teks uppercase kecil, ada tanda ">" di akhir teks — konsisten dipakai di semua CTA
- **Product/service card:** gambar besar dengan background abu terang, nama & harga di bawah gambar (center-align), tombol outline full-width di bawah card
- **Warna aksen:** soft blush pink (`#F3D9D4`-ish) dan peach/tan (`#D9A98E`-ish) muncul di section banner promo, bukan warna mencolok
- **Bagian "Get To Know Our Makeup Services"** (gambar 3) — ini **paling relevan** buat kamu: 3 card foto dengan judul layanan (Events/Fashion/Creative), durasi, harga, tombol **"BOOK NOW >"**. Pola ini persis cocok dijadiin **card treatment perawatan salon** di halaman kamu
- **Footer:** newsletter form di kiri, 3 kolom link kecil di kanan, teks kecil "Powered by Wix" dihapus tentunya
- **Gallery strip:** grid foto kecil (Instagram-style) sebelum footer — opsional, bisa dipakai buat galeri hasil treatment

### Adaptasi ke Tailwind + Blade
| Elemen di referensi | Diadaptasi jadi |
|---|---|
| Hero + tombol "SHOP NOW" | Landing page pelanggan, hero foto salon + tombol "Reservasi Sekarang" |
| Card produk (grid 4 kolom) | Grid **treatment**, tapi tombol jadi "Reservasi" bukan "Add to Cart" |
| Card "Services" (Events/Fashion/Creative + BOOK NOW) | **Persis** dipakai untuk card treatment: nama treatment, durasi, harga, tombol "Reservasi" |
| Banner promo pink/peach | Section pengumuman/promo di dashboard pelanggan |
| Gallery strip Instagram | Galeri hasil treatment (opsional, multimedia requirement) |
| Font serif tipis + uppercase letter-spacing | `font-serif` untuk heading, `tracking-wide uppercase text-xs` untuk label/nav |

### Palet warna final untuk `tailwind.config.js`
```js
colors: {
  cream: '#FDFBF9',       // background utama
  blush: '#F3D9D4',       // aksen promo/banner
  tan: '#D9A98E',         // aksen sekunder / hover
  charcoal: '#2B2B2B',    // teks utama
  graylight: '#F5F5F5',   // background card
}
```

### Catatan penting
Tetap gunakan **layout & komponen**-nya sebagai inspirasi visual (card, button style, spacing), bukan copy struktur e-commerce-nya mentah-mentah — project kamu itu sistem reservasi berbasis approval (pending/approved/rejected), bukan toko yang langsung checkout. Jadi elemen kayak "Add to Cart" / harga produk fisik gak relevan, tapi gaya visual & pola card "Services" sangat bisa dipakai langsung.

---

## 8. Langkah Selanjutnya

Setelah planning ini oke, kita build scaffold lengkap: migrations → models → seeders → controllers → routes → views, per modul. Tiap kode yang aku bantu buatkan, sebaiknya kamu baca dan pahami dulu sebelum dipakai demo — apalagi asesor kemungkinan akan tanya soal logic-nya.
