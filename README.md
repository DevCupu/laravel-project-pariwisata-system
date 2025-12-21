## Cara Menonaktifkan Email (Mode Development/Test)

Untuk bagian EMAIL SERVER untuk LOCAL DEV perlu menjalankan perintah worker di bawah agar request user dan request email di bedakan jadi tidak membebani
```
php artisan queue:work
```

Jika Anda tidak ingin email benar-benar dikirim (misal untuk development, testing, atau demo), gunakan konfigurasi berikut di file `.env`:

```
MAIL_MAILER=log
```

Dengan mode ini, semua email hanya dicatat di file `storage/logs/laravel.log` dan tidak dikirim ke alamat email manapun.

**Catatan:**
- Untuk mengaktifkan kembali email, ubah ke `MAIL_MAILER=smtp` dan pastikan konfigurasi SMTP sudah benar.
- Mode log sangat berguna untuk development agar tidak mengirim email sungguhan ke user.
## Setup Queue Worker untuk Production

Untuk memastikan email dan job queue berjalan otomatis di server production, ikuti langkah berikut:

### 1. Pastikan .env sudah benar

```
QUEUE_CONNECTION=database
MAIL_MAILER=smtp
```

### 2. Migrasi tabel jobs

```
php artisan queue:table
php artisan migrate
```

### 3. Install Supervisor (Linux)

```
sudo apt-get install supervisor
```

### 4. Buat konfigurasi supervisor

Buat file `/etc/supervisor/conf.d/laravel-queue.conf` dengan isi:

```
[program:laravel-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/laravel/storage/logs/queue-worker.log
```

Ganti `/path/to/artisan` dan `/path/to/laravel` sesuai lokasi project Anda.

### 5. Aktifkan supervisor

```
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-queue
```

### 6. Monitoring

- Cek log worker di `storage/logs/queue-worker.log`.
- Cek tabel `failed_jobs` jika ada job gagal.
- Untuk monitoring lebih lanjut, bisa install Laravel Horizon.

Dengan setup ini, worker queue akan otomatis berjalan di background, email dan job lain akan diproses tanpa perlu buka terminal manual.
<div align="center">

# 🌲 Dante Pine Enrekang – Platform Pariwisata

Landing page + portal admin untuk mengelola konten wisata, aktivitas, paket camping, menu kafe, dan sistem booking berbasis Laravel 12.

</div>

---

## 1. Arsitektur & Fitur

| Area | Feature Utama | Detail |
| --- | --- | --- |
| Publik | Home, Aktivitas, Menu, Booking | Hero transparan, section Tentang Kami dinamika, daftar Aktivitas & Fasilitas, Menu kafe, form Booking dengan pengecekan kuota. |
| Admin | Manajemen konten | Modul About Sections, Aktivitas, Menu Item, Fasilitas legacy, Booking viewer, dsb. Layout sudah dipecah menjadi partial untuk header/sidebar/modal. |
| Booking | Sumber data tunggal | Tabel `facilities` menjadi pusat resource yang bisa dibooking. Aktivitas & Paket Camping otomatis membuat entri facility (melalui relasi morph `bookable`). |
| Konten Pendukung | Spot Foto, Fasilitas Umum, Paket Camping | Masing-masing punya tabel sendiri sehingga tidak bentrok dengan kapasitas booking tapi tetap tampil di landing page. |

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
| `camping_packages` | paket camping/ glamping | name, slug, price, nights, capacity, includes (JSON) |
| `menu_items` | menu kafe | name, section, type, price, badge, is_available |
| `photo_spots` | data Spot Foto publik | title, location_text, best_time, tips, image_path |
| `general_facilities_info` | fasilitas umum non-booking | name, icon, description, availability_note |
| `about_sections` | konten Tentang Kami | title, slug, content, image_path, display_order |
| `facilities` | objek yang bisa dibooking | name, category, capacity_per_day, bookable_type/id, is_locked |
| `bookings` | permintaan booking pengunjung | booking_code, facility_id, visit_date, guests, status |
| `events`, `akomodasis`, dsb. | tabel bawaan domain lama (tersedia untuk ekspansi) |

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
git clone https://github.com/DevCupu/laravel-project-pariwisata.git
cd laravel-project-pariwisata

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
- Gunakan `php artisan booking:sync-bookables` setiap kali Anda mengimpor data aktivitas/paket dari sumber lain agar entri `facilities` tersinkron.
- Bersihkan cache jika mengganti konfigurasi: `php artisan optimize:clear`

### 4.4 Storage
Jika membutuhkan upload gambar (hero/tentang kami) pastikan symbolic link tersedia:
```bash
php artisan storage:link
```

---

## 5. Modul Publik

| Halaman | File Utama | Catatan |
| --- | --- | --- |
| Home | `resources/views/home.blade.php` | Memakai navbar transparan khusus home, menampilkan Tentang Kami, Spot Foto (`photo_spots`), Fasilitas Umum, Paket Camping. |
| Aktivitas | `resources/views/activities/index.blade.php` | Mengambil `Activity::active()->ordered()`. Tombol booking dapat diarahkan ke form dengan query facility. |
| Menu Kafe | `resources/views/menu/index.blade.php` | Menampilkan `menu_items` per kategori. |
| Booking | `resources/views/booking/index.blade.php` | Form dengan filter kategori & pengecekan kuota (Ajax ke `booking.availability`). |
| Status Booking | `resources/views/booking/status.blade.php` | Cari berdasarkan booking code + email opsional. |

---

## 6. Modul Admin

| Modul | Controller | View Folder | Highlight |
| --- | --- | --- | --- |
| Dashboard & layout | `resources/views/layouts/admin.blade.php` + partials | `resources/views/layouts/partials/admin` | Sidebar sudah menampilkan logo, link baru, mobile nav. |
| Aktivitas | `app/Http/Controllers/Admin/ActivityController.php` | `resources/views/admin/activities` | CRUD + toggle featured/active, otomatis sinkron ke booking. |
| Menu Item | `app/Http/Controllers/Admin/MenuItemController.php` | `resources/views/admin/menu-items` | CRUD menu kafe dengan section & badge. |
| Facilities legacy | `app/Http/Controllers/Admin/FacilityController.php` (default Breeze/Jetstream style) | `resources/views/admin/facilities` | Dipakai untuk seat/kursi kafe atau wahana lama. |
| Booking list | `resources/views/admin/bookings` (jika sudah dibuat) | memantau status booking. |

> **Catatan**: Modul pengelolaan `PhotoSpot`, `GeneralFacilityInfo`, dan `CampingPackage` publik bisa ditambahkan dengan pola resource controller serupa. Saat ini konten bisa diedit lewat seeder atau tinker.

---

## 7. Seeder & Data Dummy

Perintah `php artisan migrate --seed` menjalankan:
- `UserSeeder` – membuat admin default.
- `FacilitySeeder` – menambah contoh wahana/seat manual.
- `AboutSectionSeeder` – konten Tentang Kami.
- `ActivitySeeder`, `MenuItemSeeder` – data awal modul baru.
- `PhotoSpotSeeder`, `GeneralFacilityInfoSeeder`, `CampingPackageSeeder` – konten publik untuk tiga section baru.

Anda bisa menjalankan ulang seeder tertentu:
```bash
php artisan db:seed --class=ActivitySeeder
```

---

## 8. Alur Booking Singkat
1. Pengunjung membuka halaman booking dan memilih kategori → filter facility ditampilkan.
2. Saat tanggal berubah, frontend memanggil `booking/availability?date=YYYY-MM-DD` untuk menandai slot penuh.
3. Setelah submit, `BookingController@store` memvalidasi kapasitas (`capacity_per_day`) dan membuat kode unik `DPE-XXXXXX`.
4. Admin memproses booking dan dapat mengunci fasilitas dengan `is_locked` jika dibutuhkan.

---

## 9. Troubleshooting

| Masalah | Solusi |
| --- | --- |
| Navbar home tidak tampil putih | Pastikan route name `home` aktif. Setting ada di `layouts/public.blade.php`. |
| Aktivitas tidak muncul di dropdown booking | Jalankan `php artisan booking:sync-bookables` atau pastikan `is_active` true. |
| Gambar tidak tampil | Pastikan file ada di `storage/app/public` dan jalankan `storage:link`. |
| Test `ProfileTest` gagal | Layout default `layouts.app` belum dibuat ulang. Bisa diarahkan ke layout baru atau buat stub view. |

---

## 10. Script Berguna

```bash
# Development
npm run dev
php artisan serve

# Cek kuota booking per hari (REST)
curl http://localhost:8000/booking/availability?date=2025-12-01

# Sinkronisasi ulang fasilitas bookable
php artisan booking:sync-bookables
```

---