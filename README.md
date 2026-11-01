<div align="center">

# 🌲 Dante Pine Enrekang – Platform Pariwisata

Landing page + portal admin untuk mengelola konten wisata, aktivitas, paket camping, menu kafe, dan sistem booking berbasis Laravel 12.

</div>

---

## 1. Arsitektur & Fitur

| Area | Feature Utama | Detail |
| --- | --- | --- |
| Publik | Home, Aktivitas, Menu, Booking | Hero transparan, section Tentang Kami, daftar Aktivitas & Fasilitas, Menu kafe, form Booking dengan pengecekan kuota. |
| Admin | Manajemen konten | Modul About Sections, Aktivitas, Menu Item, Fasilitas, Booking, Testimoni, Spot Foto, Fasilitas Umum, Paket Camping. Layout dipecah menjadi partial untuk header/sidebar/modal. |
| Booking | Sumber data tunggal | Tabel `facilities` menjadi pusat resource yang bisa dibooking. Aktivitas & Paket Camping otomatis membuat entri facility (relasi morph `bookable`). |
| Konten Pendukung | Spot Foto, Fasilitas Umum, Paket Camping | Masing-masing punya tabel sendiri sehingga tidak bentrok dengan kapasitas booking tetapi tetap tampil di landing page. |
| Testimoni | Ulasan pengunjung | Tampil di landing page, dikelola dari admin (toggle aktif/featured). |

> **Default Admin**: `admin@wisata.test / password` (lihat `database/seeders/UserSeeder.php`). Ganti segera di produksi.

---

## 2. Teknologi Utama

- Laravel 12 + PHP 8.2
- Blade + Tailwind (via Vite) + Alpine-lite interactions
- MySQL/MariaDB (dijalankan via Laragon/XAMPP dsb.)
- Font Awesome CDN untuk ikon

---

## 3. Skema Database Ringkas

| Tabel | Deskripsi | Kolom Kunci |
| --- | --- | --- |
| `users` | akun admin/pengunjung | name, email, password, role |
| `activities` | aktivitas/wahana dengan hero image | title, slug, category, price_from, duration_minutes, is_featured |
| `camping_packages` | paket camping/glamping | name, slug, price, nights, capacity, includes (JSON) |
| `menu_items` | menu kafe | name, section, type, price, badge, is_available |
| `photo_spots` | data Spot Foto publik | title, location_text, best_time, tips, image_path |
| `general_facilities_info` | fasilitas umum non-booking | name, icon, description, availability_note |
| `about_sections` | konten Tentang Kami | title, slug, content, image_path, display_order |
| `testimonials` | ulasan pengunjung | name, origin, rating, message, is_active, is_featured |
| `facilities` | objek yang bisa dibooking | name, category, capacity_per_day, bookable_type/id, is_locked |
| `bookings` | permintaan booking pengunjung | booking_code, facility_id, visit_date, guests, status |

Relasi penting:
- `Activity` ↔ `Facility` (morphOne). `syncFacility()` membuat record `facility` kategori `wahana`.
- `CampingPackage` ↔ `Facility` (morphOne) kategori `camping`.
- `Facility` ↔ `Booking` (hasMany) untuk menghitung kuota.

---

## 4. Instalasi & Menjalankan Proyek

### 4.1 Persyaratan
- PHP >= 8.2, Composer
- Node.js >= 18 + npm
- MySQL/MariaDB (mis. via Laragon)
- Ekstensi PHP: OpenSSL, PDO, Mbstring, Ctype, JSON, BCMath, gd/fileinfo (untuk upload gambar)

### 4.2 Langkah Setup
```bash
git clone https://github.com/DevCupu/laravel-project-pariwisata-system.git
cd laravel-project-pariwisata-system

composer install
cp .env.example .env   # atau copy manual di Windows
php artisan key:generate

# set DB_* di .env, lalu
php artisan migrate --seed

npm install
npm run build          # atau npm run dev untuk mode watch

php artisan serve      # http://localhost:8000
```

### 4.3 Saat Pengembangan
- Jalankan watcher asset: `npm run dev`
- Jalankan `php artisan booking:sync-bookables` setiap kali mengimpor data aktivitas/paket dari sumber lain agar entri `facilities` tersinkron.
- Bersihkan cache jika mengganti konfigurasi: `php artisan optimize:clear`
- Jalankan tes: `php artisan test`

### 4.4 Storage
Jika membutuhkan upload gambar (hero/tentang kami) pastikan symbolic link tersedia:
```bash
php artisan storage:link
```

---

## 5. Modul Publik

| Halaman | File Utama | Catatan |
| --- | --- | --- |
| Home | `resources/views/home.blade.php` | Navbar transparan khusus home, menampilkan Tentang Kami, Testimoni, Spot Foto, Fasilitas Umum, Paket Camping. |
| Aktivitas | `resources/views/activities/index.blade.php` | Mengambil `Activity::active()->ordered()`. Tombol booking diarahkan ke form dengan query facility. |
| Menu Kafe | `resources/views/menu/index.blade.php` | Menampilkan `menu_items` per kategori. |
| Booking | `resources/views/booking/index.blade.php` | Form dengan filter kategori & pengecekan kuota (Ajax ke `booking.availability`). |
| Status Booking | `resources/views/booking/status.blade.php` | Cari berdasarkan booking code + email opsional. |
| Kontak | `resources/views/contact.blade.php` | Form kontak yang mengirim email ke admin. |

---

## 6. Modul Admin

| Modul | Controller | View Folder | Highlight |
| --- | --- | --- | --- |
| Dashboard & layout | `resources/views/layouts/admin.blade.php` + partials | `resources/views/layouts/partials/admin` | Sidebar, mobile nav, toast, modal konfirmasi. |
| Booking | `app/Http/Controllers/Admin/BookingController.php` | `resources/views/admin/booking` | Stat card sebagai filter, pencarian, rentang tanggal, urutan, ubah status inline, salin kode & WhatsApp. |
| Aktivitas | `app/Http/Controllers/Admin/ActivityController.php` | `resources/views/admin/activities` | CRUD + toggle featured/active, otomatis sinkron ke booking. |
| Menu Item | `app/Http/Controllers/Admin/MenuItemController.php` | `resources/views/admin/menu-items` | CRUD menu kafe dengan section & badge. |
| Testimoni | `app/Http/Controllers/Admin/TestimonialController.php` | `resources/views/admin/testimonials` | CRUD + toggle aktif/featured. |
| Spot Foto | `app/Http/Controllers/Admin/PhotoSpotController.php` | `resources/views/admin/photo-spots` | CRUD konten spot foto. |
| Fasilitas Umum | `app/Http/Controllers/Admin/GeneralFacilityInfoController.php` | `resources/views/admin/general-facilities` | CRUD fasilitas non-booking. |
| Paket Camping | `app/Http/Controllers/Admin/CampingPackageController.php` | `resources/views/admin/camping-packages` | CRUD paket camping, sinkron ke facility. |
| Fasilitas | `app/Http/Controllers/Admin/FacilityController.php` | `resources/views/admin/facilities` | Dipakai untuk seat/kursi kafe atau wahana lama. |

---

## 7. Seeder & Data Dummy

Perintah `php artisan migrate --seed` menjalankan:
- `UserSeeder` – membuat admin default.
- `FacilitySeeder` – menambah contoh wahana/seat manual.
- `AboutSectionSeeder` – konten Tentang Kami.
- `ActivitySeeder`, `MenuItemSeeder` – data awal modul baru.
- `PhotoSpotSeeder`, `GeneralFacilityInfoSeeder`, `CampingPackageSeeder` – konten publik.
- `TestimonialSeeder` – contoh ulasan pengunjung.

Menjalankan ulang seeder tertentu:
```bash
php artisan db:seed --class=ActivitySeeder
```

---

## 8. Alur Booking

1. Pengunjung membuka halaman booking dan memilih kategori → filter facility ditampilkan.
2. Saat tanggal berubah, frontend memanggil `booking/availability?date=YYYY-MM-DD` untuk menandai slot penuh.
3. Setelah submit, `BookingController@store` memvalidasi kapasitas (`capacity_per_day`), membuat kode unik `DPE-XXXXXX`, menyimpan booking, lalu mengirim email konfirmasi permintaan.
4. Admin memproses booking dan dapat mengunci fasilitas dengan `is_locked` jika dibutuhkan.
5. Saat status diubah menjadi **Dikonfirmasi**, sistem mengirim email konfirmasi ke pengunjung.

---

## 9. Email & Notifikasi

### 9.1 Cara kerja

- Email **dikirim langsung** saat request (`send()`), **tidak memakai queue worker**.
- `QUEUE_CONNECTION=sync` di `.env` membuat semua pemanggilan `queue()` tetap dieksekusi saat itu juga.
- Kalau pengiriman gagal, booking **tetap tersimpan**; error dicatat di `storage/logs/laravel.log` dan admin melihat peringatan di halaman booking.

Karena tidak memakai antrean, **shared hosting tanpa Supervisor/cron tetap bisa mengirim email**.

### 9.2 Opsi A — Mailpit (lokal, direkomendasikan)

Laragon menyertakan Mailpit untuk menangkap email tanpa mengirim ke internet.

```env
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

Jalankan `mailpit.exe` (Laragon → Menu → Mailpit), lalu buka **http://127.0.0.1:8025** untuk melihat email yang masuk.

### 9.3 Opsi B — Log

Email tidak dikirim, hanya ditulis ke `storage/logs/laravel.log`:

```env
MAIL_MAILER=log
```

Berguna untuk demo agar tidak ada email sungguhan yang terkirim ke pengunjung.

### 9.4 Produksi — Gmail SMTP

Gmail **tidak menerima password akun biasa** untuk SMTP. Wajib memakai **App Password**:

1. Aktifkan **2-Step Verification** di akun Google.
2. Buka https://myaccount.google.com/apppasswords → buat App Password untuk "Mail".
3. Salin 16 karakter yang muncul (tanpa spasi).

```env
MAIL_MAILER=smtp
MAIL_SCHEME=smtp          # port 587 (STARTTLS). Untuk port 465 gunakan smtps
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=alamat@gmail.com
MAIL_PASSWORD=16karakterapppassword
MAIL_FROM_ADDRESS=alamat@gmail.com   # harus sama dengan MAIL_USERNAME
MAIL_FROM_NAME="${APP_NAME}"
MAIL_TIMEOUT=10
```

Catatan:
- `MAIL_FROM_ADDRESS` **harus sama** dengan akun yang login, kalau berbeda Gmail menimpa header `From`.
- Batas Gmail gratis ±500 penerima/hari.
- App Password otomatis mati jika password Google diganti atau 2FA dimatikan.

Alternatif: email domain hosting (mis. `smtp.hostinger.com:465` dengan `MAIL_SCHEME=smtps`). Pengirim terlihat lebih profesional dan tidak terkena batas Gmail.

### 9.5 Verifikasi konfigurasi

```bash
php artisan mail:test email-tujuan@gmail.com
```

Perintah ini menampilkan host/port yang dipakai, mengirim email percobaan, dan memberi pesan error yang jelas jika gagal. Tanpa argumen, email dikirim ke `MAIL_FROM_ADDRESS`.

### 9.6 Kapan butuh queue worker?

Hanya jika Anda mengubah `QUEUE_CONNECTION` menjadi `database` (misal untuk mengirim email massal di VPS). Saat itu:

```bash
php artisan queue:work
# atau di VPS dengan Supervisor (tidak tersedia di shared hosting)
```

Untuk setup sekarang (`sync`), **worker tidak diperlukan**.

### 9.7 Troubleshooting email

| Pesan / Gejala | Penyebab | Solusi |
| --- | --- | --- |
| `535 ... Username and Password not accepted` | Memakai password akun, atau App Password ada spasinya | Buat App Password baru, hapus spasi |
| `The "tls" scheme is not supported` | `MAIL_SCHEME` salah | Gunakan `smtp` (587) atau `smtps` (465) |
| `Connection could not be established` | Port diblokir hosting | Coba `MAIL_PORT=465` + `MAIL_SCHEME=smtps` |
| `Connection timed out` | Firewall hosting | Pakai SMTP hosting sendiri |
| Email tidak pernah sampai, tabel `jobs` terisi | `QUEUE_CONNECTION=database` tanpa worker | Set `sync`, atau jalankan `queue:work` |
| Perubahan `.env` tidak berefek | Config ter-cache | `php artisan config:clear` |

---

## 10. Deploy ke Shared Hosting (cPanel)

### 10.1 Upload

Upload project (Git `git pull` atau File Manager). **Jangan upload `.env` dari lokal.**

### 10.2 Konfigurasi `.env`

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda

QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=alamat@gmail.com
MAIL_PASSWORD=16karakterapppassword
MAIL_FROM_ADDRESS=alamat@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
MAIL_TIMEOUT=10
```

Pastikan tidak ada duplikat baris `MAIL_MAILER` atau `QUEUE_CONNECTION`.

### 10.3 Perintah via SSH

```bash
cd ~/domains/domain-anda/public_html

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link

php artisan mail:test email-anda@gmail.com
```

### 10.4 Bersihkan email lama yang nyangkut

```bash
php artisan queue:work database --once --tries=1   # kirim sekali
php artisan queue:clear database                    # atau hapus semua
```

### 10.5 Verifikasi

1. Submit form booking dari situs live → email masuk dalam hitungan detik.
2. Admin → ubah status ke **Dikonfirmasi** → email konfirmasi masuk.
3. `storage/logs/laravel-*.log` tidak berisi `Gagal mengirim email`.

### 10.6 Catatan shared hosting

- Document root diarahkan ke folder `public/`.
- Folder `storage/` dan `bootstrap/cache/` harus writable (755 folder, 644 file).
- Supervisor **tidak tersedia** di shared hosting — tidak diperlukan untuk setup ini.
- Cron `schedule:run` hanya dibutuhkan jika nanti ada tugas terjadwal.

---

## 11. Troubleshooting

| Masalah | Solusi |
| --- | --- |
| Navbar home tidak tampil putih | Pastikan route name `home` aktif. Setting ada di `layouts/public.blade.php`. |
| Aktivitas tidak muncul di dropdown booking | Jalankan `php artisan booking:sync-bookables` atau pastikan `is_active` true. |
| Gambar tidak tampil | Pastikan file ada di `storage/app/public` dan jalankan `storage:link`. |
| Halaman error 500 setelah deploy | Cek permission `storage/` & `bootstrap/cache/`, lalu `php artisan config:clear`. |
| Email gagal terkirim | Lihat tabel **9.7** dan cek `storage/logs/laravel.log`. |

---

## 12. Script Berguna

```bash
# Development
npm run dev
php artisan serve
php artisan test

# Cek kuota booking per hari (REST)
curl http://localhost:8000/booking/availability?date=2025-12-01

# Sinkronisasi ulang fasilitas bookable
php artisan booking:sync-bookables

# Tes konfigurasi email
php artisan mail:test email-anda@gmail.com

# Bersihkan cache
php artisan optimize:clear
```
