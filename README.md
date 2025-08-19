## Rangkuman Fitur

### 1. Fitur Admin (Manajemen Data)
- 🗂️ CRUD Kategori Wisata
- 🏞️ CRUD Destinasi Wisata
- 📰 CRUD Artikel Wisata
- 🎉 CRUD Event Wisata
- 🛏️ CRUD Akomodasi
- 🖼️ CRUD Galeri Foto
- 📊 Dashboard Admin (statistik destinasi, ulasan, kunjungan)

### 2. Fitur Halaman Publik (Tanpa Login)
- 🏠 Landing Page
- 📍 Lihat daftar destinasi wisata
- 🔍 Filter destinasi (kategori/lokasi)
- 📰 Artikel wisata
- 🎉 Event wisata
- 🖼️ Galeri foto
- 📞 Kontak / Tentang Kami

### 3. Autentikasi
- 🔐 Login admin (via seeder/by system)

---

### Relasi Utama Antar Data

| Tabel      | Relasi                              |
|------------|-------------------------------------|
| Destinasi  | belongsTo → Kategori                |
| Ulasan     | belongsTo → User & Destinasi        |
| Galeri     | belongsTo → Destinasi               |
| Artikel    | belongsTo → Admin (opsional)        |
| Event      | Optional                            |
| Akomodasi  | Optional (relasi ke destinasi/kategori) |
