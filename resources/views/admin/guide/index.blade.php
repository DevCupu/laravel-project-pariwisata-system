@extends('layouts.admin')

@section('title', 'Panduan Penggunaan')
@section('page-title', 'Panduan Penggunaan')
@section('page-subtitle', 'Petunjuk singkat untuk mengelola website dan booking Dante Pine')

@section('content')
    <div class="min-w-0 overflow-hidden rounded-2xl border border-base-300 bg-base-100 shadow-sm">
        <div class="grid min-w-0 grid-cols-1 gap-6 bg-gradient-to-br from-blue-700 via-blue-700 to-indigo-800 px-6 py-7 text-white sm:px-8 sm:py-9 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
            <div class="min-w-0 max-w-3xl">
                <p class="mb-3 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold tracking-wide ring-1 ring-white/20">
                    <i class="fas fa-book-open"></i>
                    PANDUAN ADMIN
                </p>
                <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">Kelola website dengan lebih mudah</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-blue-50 sm:text-base">
                    Ikuti petunjuk untuk memperbarui informasi wisata, memeriksa booking, dan memastikan perubahan tampil di website.
                    Pilih bagian yang ingin dipelajari dari daftar di bawah.
                </p>
            </div>
            <a href="#mulai" class="btn border-0 bg-white text-blue-800 hover:bg-blue-50">
                Mulai membaca <i class="fas fa-arrow-down text-xs"></i>
            </a>
        </div>
    </div>

    <div id="mulai" class="grid min-w-0 grid-cols-1 items-start gap-5 lg:grid-cols-[235px_minmax(0,1fr)]">
        <nav aria-label="Daftar isi panduan" class="min-w-0 rounded-2xl border border-base-300 bg-base-100 p-4 shadow-sm lg:sticky lg:top-5">
            <p class="px-2 pb-2 text-xs font-bold uppercase tracking-wider text-base-content/45">Daftar isi</p>
            <div class="grid grid-cols-2 gap-1 sm:grid-cols-3 lg:grid-cols-1">
                <a class="guide-nav-link" href="#akses"><i class="fas fa-right-to-bracket"></i><span>Mulai menggunakan</span></a>
                <a class="guide-nav-link" href="#dashboard"><i class="fas fa-gauge-high"></i><span>Dashboard</span></a>
                <a class="guide-nav-link" href="#konten"><i class="fas fa-images"></i><span>Kelola konten</span></a>
                <a class="guide-nav-link" href="#booking"><i class="fas fa-calendar-check"></i><span>Booking</span></a>
                <a class="guide-nav-link" href="#fasilitas"><i class="fas fa-list-check"></i><span>Fasilitas</span></a>
                <a class="guide-nav-link" href="#kafe"><i class="fas fa-mug-hot"></i><span>Menu kafe & kontak</span></a>
                <a class="guide-nav-link" href="#akun"><i class="fas fa-user-gear"></i><span>Profil & keluar</span></a>
                <a class="guide-nav-link" href="#kendala"><i class="fas fa-circle-question"></i><span>Kendala umum</span></a>
            </div>
        </nav>

        <div class="min-w-0 space-y-5">
            <section id="akses" class="guide-section">
                <div class="guide-section-heading">
                    <span class="guide-icon"><i class="fas fa-right-to-bracket"></i></span>
                    <div>
                        <h2>Mulai menggunakan panel admin</h2>
                        <p>Panel ini hanya bisa dibuka oleh akun yang memiliki akses admin.</p>
                    </div>
                </div>
                <ol class="guide-steps">
                    <li>Buka alamat website, lalu masuk melalui halaman <strong>Login</strong>.</li>
                    <li>Masukkan email dan kata sandi akun admin. Setelah berhasil masuk, pilih <strong>Dashboard</strong>.</li>
                    <li>Gunakan menu di sisi kiri untuk membuka bagian yang ingin dikelola. Di ponsel, ketuk tombol menu untuk membuka sidebar.</li>
                </ol>
                <div class="guide-note guide-note-blue mt-4">
                    <i class="fas fa-circle-info mt-0.5"></i>
                    <p><strong>Ingat:</strong> tombol <strong>Simpan</strong> atau <strong>Perbarui</strong> menerapkan perubahan ke data website. Periksa kembali judul, harga, tanggal, dan status sebelum menyimpan.</p>
                </div>
            </section>

            <section id="dashboard" class="guide-section">
                <div class="guide-section-heading">
                    <span class="guide-icon"><i class="fas fa-gauge-high"></i></span>
                    <div>
                        <h2>Dashboard</h2>
                        <p>Lihat ringkasan konten dan booking yang perlu diperhatikan.</p>
                    </div>
                </div>
                <figure class="guide-figure">
                    <img src="{{ asset('images/admin-guide/dashboard.png') }}" alt="Screenshot dashboard admin dengan penanda pada sidebar, kartu ringkasan, dan daftar booking" loading="lazy">
                    <figcaption>Tampilan dashboard. Nomor pada gambar merujuk ke keterangan di bawah.</figcaption>
                </figure>
                <div class="guide-callouts">
                    <p><span>1</span><strong>Menu samping</strong> untuk berpindah antarbagian panel.</p>
                    <p><span>2</span><strong>Kartu ringkasan</strong> menampilkan jumlah aktivitas, paket, event, spot foto, dan booking.</p>
                    <p><span>3</span><strong>Booking terbaru</strong> membantu melihat permintaan yang baru masuk.</p>
                    <p><span>4</span><strong>Perlu ditangani</strong> menandai booking berstatus menunggu konfirmasi.</p>
                </div>
            </section>

            <section id="konten" class="guide-section">
                <div class="guide-section-heading">
                    <span class="guide-icon"><i class="fas fa-images"></i></span>
                    <div>
                        <h2>Mengelola konten website</h2>
                        <p>Langkah dasar berikut berlaku untuk sebagian besar daftar konten di menu admin.</p>
                    </div>
                </div>
                <figure class="guide-figure">
                    <img src="{{ asset('images/admin-guide/content-list.png') }}" alt="Screenshot daftar Aktivitas admin dengan penanda tombol tambah, status, edit, highlight, dan hapus" loading="lazy">
                    <figcaption>Contoh halaman daftar Aktivitas. Tata letaknya serupa dengan beberapa daftar konten lainnya.</figcaption>
                </figure>
                <div class="guide-callouts">
                    <p><span>1</span><strong>Tambah</strong> membuka formulir untuk data baru.</p>
                    <p><span>2</span><strong>Status</strong> mengatur apakah konten tampil di website.</p>
                    <p><span>3</span><strong>Edit</strong> membuka formulir untuk memperbarui data.</p>
                    <p><span>4</span><strong>Highlight</strong> mengutamakan konten di bagian sorotan jika tersedia.</p>
                    <p><span>5</span><strong>Hapus</strong> menghilangkan data. Baca konfirmasi sebelum melanjutkan.</p>
                </div>

                <div class="guide-subsection mt-5">
                    <h3>Tambah atau ubah data</h3>
                    <ol class="guide-steps">
                        <li>Buka menu yang sesuai, lalu pilih <strong>Tambah</strong> atau <strong>Edit</strong>.</li>
                        <li>Isi kolom wajib yang diberi tanda bintang. Lengkapi deskripsi dengan informasi yang jelas dan mudah dibaca.</li>
                        <li>Atur status publikasi. Jika data belum siap dilihat pengunjung, matikan pilihan tampil/aktif.</li>
                        <li>Unggah gambar atau isi path gambar bila formulir menyediakan kolom tersebut. Perhatikan batas ukuran yang tertulis.</li>
                        <li>Pilih <strong>Simpan</strong> atau <strong>Perbarui</strong>, lalu buka tautan website publik untuk memeriksa hasilnya.</li>
                    </ol>
                    <p class="guide-paragraph mt-4">Jika tersedia, tombol cepat <strong>Aktifkan/Nonaktifkan</strong> dan <strong>Highlight/Biasa</strong> pada daftar langsung mengubah status. Bila tombol cepat tidak ada, ubah status melalui formulir <strong>Edit</strong>. Untuk menghapus data, pilih <strong>Hapus</strong>, baca pesan konfirmasi, lalu lanjutkan hanya jika yakin. Penghapusan biasanya tidak dapat dibatalkan.</p>
                    <div class="guide-note guide-note-amber mt-4">
                        <i class="fas fa-triangle-exclamation mt-0.5"></i>
                        <p>Slug boleh dikosongkan pada formulir yang menyediakan pilihan otomatis. Untuk <strong>Tentang Kami</strong>, slug wajib diisi karena digunakan sebagai tautan bagian halaman.</p>
                    </div>
                </div>

                <div class="guide-subsection mt-5">
                    <h3>Contoh formulir Aktivitas</h3>
                    <figure class="guide-figure">
                        <img src="{{ asset('images/admin-guide/activity-form.png') }}" alt="Screenshot formulir Aktivitas admin dengan penanda detail, publikasi, gambar, dan tombol simpan" loading="lazy">
                        <figcaption>Kolom dapat berbeda di setiap modul; gunakan petunjuk kecil di bawah kolom sebagai panduan.</figcaption>
                    </figure>
                    <div class="guide-callouts">
                        <p><span>1</span><strong>Detail aktivitas</strong> mencakup judul, kategori, durasi, harga, dan deskripsi.</p>
                        <p><span>2</span><strong>Publikasi</strong> mengatur status aktif dan highlight.</p>
                        <p><span>3</span><strong>Hero image (path)</strong> menerima path gambar, bukan unggahan file pada formulir ini.</p>
                        <p><span>4</span><strong>Simpan</strong> menerapkan data; pilih Batal jika ingin kembali tanpa menyimpan.</p>
                    </div>
                </div>

                <div class="guide-subsection mt-5">
                    <h3>Hal yang perlu diperhatikan per menu</h3>
                    <div class="guide-module-grid">
                        <article class="guide-module">
                            <h4><i class="fas fa-mountain"></i> Aktivitas</h4>
                            <p>Isi nama, kategori, harga, durasi, usia minimum, dan deskripsi. Dari daftar, gunakan <strong>Aktifkan/Nonaktifkan</strong> atau <strong>Highlight/Biasa</strong>. Aktivitas aktif tersedia di website dan pilihan booking. Menghapus aktivitas juga menghapus pilihan fasilitas booking yang terhubung.</p>
                        </article>
                        <article class="guide-module">
                            <h4><i class="fas fa-camera-retro"></i> Spot Foto</h4>
                            <p>Isi judul, lokasi, waktu terbaik, deskripsi, dan tips, lalu unggah gambar sesuai batas ukuran pada formulir. Dari daftar, ubah status tampil atau <strong>Highlight</strong> sesuai kebutuhan. Gunakan <strong>Edit</strong> untuk memperbarui informasi dan <strong>Hapus</strong> jika spot tidak diperlukan lagi.</p>
                        </article>
                        <article class="guide-module">
                            <h4><i class="fas fa-campground"></i> Paket Camping</h4>
                            <p>Periksa harga, jumlah malam, kapasitas, dan daftar fasilitas yang termasuk. Tulis setiap fasilitas di baris terpisah. Dari daftar, atur status tampil dan <strong>Highlight</strong>. Menonaktifkan paket menghilangkan pilihan booking baru; menghapusnya juga memengaruhi slot booking terkait.</p>
                        </article>
                        <article class="guide-module">
                            <h4><i class="fas fa-calendar-days"></i> Event & Promo</h4>
                            <p>Pilih tipe Event atau Promo, isi tanggal mulai dan akhir, ringkasan, serta deskripsi. Dari daftar, gunakan <strong>Aktifkan/Nonaktifkan</strong> dan <strong>Highlight/Biasa</strong>; gunakan <strong>Edit</strong> untuk perubahan atau <strong>Hapus</strong> untuk menghilangkan data yang sudah tidak diperlukan.</p>
                        </article>
                        <article class="guide-module">
                            <h4><i class="fas fa-comment-dots"></i> Testimoni</h4>
                            <p>Isi nama, rating, kutipan ulasan, serta informasi opsional. Dari daftar, aktifkan hanya testimoni yang siap dipublikasikan; <strong>Highlight</strong> menempatkannya lebih dahulu di beranda. Gunakan <strong>Edit</strong> atau <strong>Hapus</strong> dari daftar untuk mengelola ulasan.</p>
                        </article>
                        <article class="guide-module">
                            <h4><i class="fas fa-circle-info"></i> Tentang Kami</h4>
                            <p>Pilih <strong>Tambah Konten</strong> atau <strong>Edit</strong>, lalu isi judul, slug, konten, urutan tampil, dan status. Angka urutan yang lebih kecil tampil lebih dulu. Status diubah melalui formulir; modul ini tidak memakai highlight. Gambar sorotan maksimal 2 MB. Tombol <strong>Hapus</strong> menghilangkan blok konten.</p>
                        </article>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <a href="{{ route('activities.index') }}" target="_blank" rel="noopener" class="btn btn-outline btn-xs">Cek Aktivitas</a>
                        <a href="{{ route('events.index') }}" target="_blank" rel="noopener" class="btn btn-outline btn-xs">Cek Event & Promo</a>
                        <a href="{{ route('menu.index') }}" target="_blank" rel="noopener" class="btn btn-outline btn-xs">Cek Menu Kafe</a>
                        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline btn-xs">Cek Beranda</a>
                    </div>
                </div>
            </section>

            <section id="booking" class="guide-section">
                <div class="guide-section-heading">
                    <span class="guide-icon"><i class="fas fa-calendar-check"></i></span>
                    <div>
                        <h2>Memeriksa dan memproses booking</h2>
                        <p>Booking pengunjung masuk ke menu <strong>Daftar Booking</strong>.</p>
                    </div>
                </div>
                <figure class="guide-figure">
                    <img src="{{ asset('images/admin-guide/bookings.png') }}" alt="Screenshot daftar booking admin dengan penanda pencarian, status, filter tanggal, dan tindakan booking" loading="lazy">
                    <figcaption>Gunakan pencarian dan filter untuk menemukan booking tertentu.</figcaption>
                </figure>
                <div class="guide-callouts">
                    <p><span>1</span><strong>Pencarian</strong> menerima kode booking, nama, email, atau nomor telepon.</p>
                    <p><span>2</span><strong>Status</strong> menyaring booking berdasarkan tahap pemrosesan.</p>
                    <p><span>3</span><strong>Tanggal</strong> menyaring tanggal kunjungan dan dapat diurutkan.</p>
                    <p><span>4</span><strong>Ubah status</strong> memilih status baru lalu menekan Simpan.</p>
                </div>
                <div class="guide-subsection mt-5">
                    <h3>Alur status yang disarankan</h3>
                    <ol class="guide-steps">
                        <li><strong>Menunggu:</strong> permintaan baru yang belum diputuskan.</li>
                        <li><strong>Dikonfirmasi:</strong> booking disetujui. Periksa tanggal, fasilitas, dan jumlah tamu terlebih dahulu.</li>
                        <li><strong>Selesai:</strong> kunjungan sudah terlaksana.</li>
                        <li><strong>Dibatalkan:</strong> booking tidak jadi digunakan.</li>
                    </ol>
                    <div class="guide-note guide-note-blue mt-4">
                        <i class="fas fa-envelope mt-0.5"></i>
                        <p>Saat status berubah menjadi <strong>Dikonfirmasi</strong>, sistem mencoba mengirim email konfirmasi kepada pengunjung. Jika pengiriman gagal, booking tetap tersimpan dan panel menampilkan pemberitahuan.</p>
                    </div>
                </div>
            </section>

            <section id="fasilitas" class="guide-section">
                <div class="guide-section-heading">
                    <span class="guide-icon"><i class="fas fa-list-check"></i></span>
                    <div>
                        <h2>Memahami menu fasilitas</h2>
                        <p>Ada dua menu fasilitas dengan fungsi yang berbeda.</p>
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <article class="guide-compare-card">
                        <div class="guide-compare-icon bg-blue-50 text-blue-700"><i class="fas fa-calendar-check"></i></div>
                        <h3>Fasilitas Booking</h3>
                        <p>Berisi pilihan yang bisa dipesan pengunjung. Di sini admin dapat memeriksa kapasitas per hari, status aktif, kategori, dan penggunaan kuota.</p>
                        <p class="mt-2 text-xs text-base-content/55">Aktivitas dan paket camping aktif tersinkron ke pilihan booking.</p>
                    </article>
                    <article class="guide-compare-card">
                        <div class="guide-compare-icon bg-emerald-50 text-emerald-700"><i class="fas fa-restroom"></i></div>
                        <h3>Fasilitas Umum</h3>
                        <p>Berisi informasi pendukung seperti nama fasilitas, ikon, deskripsi, catatan ketersediaan, dan urutan tampil. Fasilitas ini tidak memiliki proses booking.</p>
                    </article>
                </div>
                <div class="guide-subsection mt-5">
                    <h3>Fasilitas Booking</h3>
                    <figure class="guide-figure">
                        <img src="{{ asset('images/admin-guide/booking-facilities.png') }}" alt="Screenshot fasilitas booking dengan penanda filter tanggal, kapasitas, status, dan tombol tambah" loading="lazy">
                        <figcaption>Kapasitas dihitung untuk tanggal yang dipilih. Booking menunggu dan dikonfirmasi ikut memakai kuota.</figcaption>
                    </figure>
                    <div class="guide-callouts">
                        <p><span>1</span><strong>Tanggal</strong> memilih hari yang ingin diperiksa kuotanya.</p>
                        <p><span>2</span><strong>Kapasitas</strong> menunjukkan tamu terpakai dan sisa kuota.</p>
                        <p><span>3</span><strong>Status aktif</strong> menentukan apakah fasilitas muncul di formulir booking.</p>
                        <p><span>4</span><strong>Tambah Fasilitas</strong> membuat pilihan booking baru yang dikelola manual.</p>
                    </div>
                    <p class="guide-paragraph mt-4">Untuk fasilitas yang dibuat manual, gunakan <strong>Edit</strong> untuk memperbarui data dan <strong>Aktifkan/Nonaktifkan</strong> untuk mengatur ketersediaannya. Menghapus fasilitas tidak menghapus booking lama, tetapi booking tersebut tidak lagi terhubung ke fasilitas.</p>
                </div>
                <div class="guide-subsection mt-5">
                    <h3>Fasilitas Umum</h3>
                    <figure class="guide-figure">
                        <img src="{{ asset('images/admin-guide/general-facilities.png') }}" alt="Screenshot daftar fasilitas umum dengan penanda tombol tambah, informasi fasilitas, status, dan aksi" loading="lazy">
                        <figcaption>Informasi pada menu ini membantu pengunjung memahami layanan yang tersedia di lokasi.</figcaption>
                    </figure>
                    <div class="guide-callouts">
                        <p><span>1</span><strong>Tambah Fasilitas</strong> membuka formulir informasi fasilitas umum.</p>
                        <p><span>2</span><strong>Informasi</strong> mencakup deskripsi dan catatan ketersediaan.</p>
                        <p><span>3</span><strong>Status</strong> mengatur apakah informasi tampil di website.</p>
                        <p><span>4</span><strong>Edit atau Hapus</strong> mengubah data atau menghapusnya setelah konfirmasi.</p>
                    </div>
                </div>
                <div class="guide-note guide-note-amber mt-4">
                    <i class="fas fa-triangle-exclamation mt-0.5"></i>
                    <p>Sebelum menonaktifkan fasilitas booking, periksa jadwal yang akan datang karena fasilitas tersebut tidak lagi tersedia untuk pemesanan baru.</p>
                </div>
            </section>

            <section id="kafe" class="guide-section">
                <div class="guide-section-heading">
                    <span class="guide-icon"><i class="fas fa-mug-hot"></i></span>
                    <div>
                        <h2>Menu kafe dan pesan Kontak</h2>
                        <p>Kelola daftar menu kafe dan ketahui ke mana pesan dari pengunjung dikirim.</p>
                    </div>
                </div>
                <figure class="guide-figure">
                    <img src="{{ asset('images/admin-guide/menu-cafe.png') }}" alt="Screenshot daftar menu kafe admin dengan penanda pencarian, ketersediaan, dan tombol tambah menu" loading="lazy">
                    <figcaption>Menu yang tidak tersedia dapat disembunyikan sementara tanpa menghapus datanya.</figcaption>
                </figure>
                <div class="guide-callouts">
                    <p><span>1</span><strong>Cari dan saring</strong> membantu menemukan menu berdasarkan section atau tipe.</p>
                    <p><span>2</span><strong>Tambah Menu</strong> membuka formulir item baru.</p>
                    <p><span>3</span><strong>Ketersediaan</strong> dapat diubah saat stok habis.</p>
                    <p><span>4</span><strong>Edit</strong> memperbarui harga, gambar, atau informasi lainnya.</p>
                </div>
                <p class="guide-paragraph mt-4">Pilih <strong>Tambah Menu</strong> untuk membuat item, lalu simpan. Gunakan <strong>Edit</strong> untuk memperbarui data. Tombol ketersediaan akan menyembunyikan menu yang habis atau menampilkannya kembali. Jika item tidak akan digunakan lagi, pilih <strong>Hapus</strong> dan periksa konfirmasi.</p>
                <div class="guide-subsection mt-5">
                    <h3>Pesan dari formulir Kontak</h3>
                    <p class="guide-paragraph">Formulir Kontak pada website mengirim pesan ke alamat email admin yang dikonfigurasi. Pesan tersebut <strong>tidak disimpan sebagai inbox di panel admin</strong>. Jika email tidak diterima, minta bantuan pengelola teknis untuk memeriksa pengaturan email website.</p>
                </div>
            </section>

            <section id="akun" class="guide-section">
                <div class="guide-section-heading">
                    <span class="guide-icon"><i class="fas fa-user-gear"></i></span>
                    <div>
                        <h2>Mengatur profil dan keluar</h2>
                        <p>Gunakan menu akun di kanan atas untuk merawat keamanan akun.</p>
                    </div>
                </div>
                <figure class="guide-figure">
                    <img src="{{ asset('images/admin-guide/profile.png') }}" alt="Screenshot halaman pengaturan profil admin" loading="lazy">
                    <figcaption>Halaman profil menyediakan pengaturan informasi akun dan kata sandi.</figcaption>
                </figure>
                <div class="guide-callouts mt-3">
                    <p><span>1</span><strong>Profile Information</strong> berisi nama dan email akun.</p>
                    <p><span>2</span><strong>Update Password</strong> berisi kata sandi saat ini, kata sandi baru, dan konfirmasi.</p>
                    <p><span>3</span><strong>Save</strong> menyimpan bagian profil atau kata sandi yang sedang diubah.</p>
                </div>
                <ol class="guide-steps mt-4">
                    <li>Klik nama atau ikon akun di kanan atas, lalu pilih <strong>Profile</strong>.</li>
                    <li>Perbarui nama atau email di bagian informasi profil. Simpan perubahan setelah memeriksa kembali alamat email.</li>
                    <li>Untuk mengganti kata sandi, isi kata sandi saat ini dan kata sandi baru sesuai petunjuk pada formulir.</li>
                    <li>Untuk keluar, buka menu akun lalu pilih <strong>Log Out</strong>. Di sidebar, tombol ikon keluar juga tersedia.</li>
                </ol>
                <div class="guide-note guide-note-blue mt-4">
                    <i class="fas fa-lock mt-0.5"></i>
                    <p>Gunakan kata sandi yang tidak dipakai di tempat lain dan jangan membagikan akses admin kepada orang yang tidak perlu mengelola website.</p>
                </div>
            </section>

            <section id="kendala" class="guide-section">
                <div class="guide-section-heading">
                    <span class="guide-icon"><i class="fas fa-circle-question"></i></span>
                    <div>
                        <h2>Kendala umum</h2>
                        <p>Periksa hal-hal berikut sebelum mencoba menyimpan kembali.</p>
                    </div>
                </div>
                <div class="guide-troubleshooting">
                    <details open>
                        <summary>Konten belum terlihat di website</summary>
                        <p>Periksa apakah statusnya aktif/ditampilkan, lalu muat ulang halaman publik. Untuk konten sorotan, pastikan pilihan highlight juga sudah diaktifkan.</p>
                    </details>
                    <details>
                        <summary>Gambar gagal diunggah atau tidak berubah</summary>
                        <p>Periksa jenis dan batas ukuran file pada formulir. Pastikan gambar yang benar dipilih sebelum menekan Simpan. Jika tetap gagal, hubungi pengelola teknis.</p>
                    </details>
                    <details>
                        <summary>Booking tidak bisa dikonfirmasi atau fasilitas penuh</summary>
                        <p>Periksa tanggal kunjungan, status fasilitas, kapasitas per hari, dan jumlah tamu pada booking.</p>
                    </details>
                    <details>
                        <summary>Email konfirmasi booking tidak sampai</summary>
                        <p>Pastikan alamat email pengunjung benar dan minta mereka memeriksa folder spam. Bila panel menunjukkan email gagal dikirim, hubungi pengelola teknis; perubahan status booking tetap tersimpan.</p>
                    </details>
                    <details>
                        <summary>Pesan Kontak tidak terlihat di panel</summary>
                        <p>Pesan formulir Kontak masuk ke email admin, bukan ke daftar booking atau inbox panel.</p>
                    </details>
                </div>
                <div class="mt-5 flex flex-wrap gap-2">
                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><i class="fas fa-arrow-up-right-from-square"></i> Lihat website publik</a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm"><i class="fas fa-gauge-high"></i> Kembali ke Dashboard</a>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .guide-nav-link { display:flex; align-items:center; gap:.65rem; border-radius:.75rem; padding:.65rem .7rem; color:rgb(71 85 105); font-size:.82rem; font-weight:600; transition:background-color .15s,color .15s; }
        .guide-nav-link i { width:1rem; color:rgb(100 116 139); text-align:center; }
        .guide-nav-link:hover { background:rgb(239 246 255); color:rgb(29 78 216); }
        .guide-section { scroll-margin-top:1.25rem; border:1px solid #e2e8f0; border-radius:1rem; background:#fff; padding:1.25rem; box-shadow:0 1px 2px rgb(15 23 42 / .04); }
        @media (min-width:640px) { .guide-section { padding:1.6rem; } }
        .guide-section-heading { display:flex; align-items:flex-start; gap:.85rem; margin-bottom:1.15rem; }
        .guide-section-heading h2 { font-size:1.15rem; font-weight:800; letter-spacing:-.02em; }
        .guide-section-heading p { margin-top:.2rem; color:#64748b; font-size:.86rem; line-height:1.55; }
        .guide-icon { display:flex; height:2.5rem; width:2.5rem; flex:none; align-items:center; justify-content:center; border-radius:.85rem; background:rgb(239 246 255); color:rgb(37 99 235); }
        .guide-steps { display:grid; gap:.7rem; padding-left:1.4rem; color:#475569; font-size:.9rem; line-height:1.65; list-style:decimal; }
        .guide-steps li { padding-left:.2rem; }
        .guide-steps li::marker { color:rgb(37 99 235); font-weight:800; }
        .guide-note { display:flex; gap:.7rem; border-radius:.8rem; padding:.85rem 1rem; font-size:.84rem; line-height:1.6; }
        .guide-note-blue { background:rgb(239 246 255); color:rgb(30 64 175); }
        .guide-note-amber { background:rgb(255 251 235); color:rgb(146 64 14); }
        .guide-figure { overflow:hidden; border:1px solid #e2e8f0; border-radius:.85rem; background:#f8fafc; }
        .guide-figure img { display:block; width:100%; height:auto; }
        .guide-figure figcaption { border-top:1px solid #e2e8f0; padding:.65rem .85rem; color:#64748b; font-size:.75rem; line-height:1.5; }
        .guide-callouts { display:grid; gap:.55rem; margin-top:.85rem; }
        @media (min-width:640px) { .guide-callouts { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        .guide-callouts p { display:flex; align-items:flex-start; gap:.55rem; color:#64748b; font-size:.8rem; line-height:1.5; }
        .guide-callouts p span { display:inline-flex; height:1.3rem; width:1.3rem; flex:none; align-items:center; justify-content:center; border-radius:999px; background:rgb(37 99 235); color:white; font-size:.68rem; font-weight:800; }
        .guide-callouts strong { color:#1e293b; }
        .guide-subsection { border-top:1px solid #e2e8f0; padding-top:1.2rem; }
        .guide-subsection h3 { margin-bottom:.85rem; font-size:.98rem; font-weight:800; }
        .guide-module-grid { display:grid; gap:.75rem; }
        @media (min-width:640px) { .guide-module-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
        .guide-module { border:1px solid #e2e8f0; border-radius:.85rem; padding:.9rem; }
        .guide-module h4 { display:flex; align-items:center; gap:.5rem; font-size:.85rem; font-weight:800; }
        .guide-module h4 i { width:1rem; color:rgb(37 99 235); text-align:center; }
        .guide-module p { margin-top:.45rem; color:#475569; font-size:.78rem; line-height:1.6; }
        .guide-compare-card { border:1px solid #e2e8f0; border-radius:.9rem; padding:1rem; }
        .guide-compare-icon { display:flex; height:2.25rem; width:2.25rem; align-items:center; justify-content:center; border-radius:.7rem; }
        .guide-compare-card h3 { margin-top:.75rem; font-size:.95rem; font-weight:800; }
        .guide-compare-card p { margin-top:.4rem; color:#475569; font-size:.82rem; line-height:1.6; }
        .guide-paragraph { color:#475569; font-size:.88rem; line-height:1.7; }
        .guide-troubleshooting { display:grid; gap:.55rem; }
        .guide-troubleshooting details { border:1px solid #e2e8f0; border-radius:.8rem; padding:.8rem .95rem; }
        .guide-troubleshooting summary { cursor:pointer; font-size:.86rem; font-weight:700; }
        .guide-troubleshooting details p { margin-top:.6rem; color:#475569; font-size:.82rem; line-height:1.65; }
    </style>
@endpush
