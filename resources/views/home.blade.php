@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center"
        style="background-image: url('{{ asset('assets-beranda/home.jpg') }}'); background-size: cover; background-position: center;"
        aria-label="Hero Section">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white px-4">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">Jelajahi Keindahan Indonesia</h1>
                <p class="text-lg md:text-xl mb-8 text-gray-200 font-medium drop-shadow">Temukan destinasi wisata terbaik di
                    seluruh Nusantara</p>
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <a href="#"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Mulai Jelajahi
                    </a>
                    <a href="#"
                        class="bg-white bg-opacity-20 hover:bg-opacity-40 text-white hover:text-blue-700 px-8 py-3 rounded-full text-lg font-semibold shadow-lg border border-white transition duration-300 focus:outline-none focus:ring-2 focus:ring-white">
                        <i class="fas fa-play-circle mr-2"></i>Lihat Video
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg p-8 -mt-32 relative z-20">
                <h2 class="text-2xl font-bold text-center mb-8">Cari Destinasi Impian Anda</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Destinasi</label>
                        <input type="text" placeholder="Mau ke mana?"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Semua Kategori</option>
                            <option>Pantai</option>
                            <option>Gunung</option>
                            <option>Budaya</option>
                            <option>Kuliner</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <select
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option>Semua Lokasi</option>
                            <option>Jawa</option>
                            <option>Bali</option>
                            <option>Sumatra</option>
                            <option>Kalimantan</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                            <i class="fas fa-search mr-2"></i>Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Destinations -->
    {{-- <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Destinasi Populer</h2>
                <p class="text-xl text-gray-600">Jelajahi tempat-tempat menakjubkan yang paling disukai wisatawan</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse ($destinasiPopuler as $destinasi)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $destinasi->gambar) }}" alt="{{ $destinasi->nama }}"
                                class="w-full h-64 object-cover">
                            <div class="absolute top-4 left-4">
                                <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $destinasi->kategori->nama ?? 'Populer' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $destinasi->nama }}</h3>
                            <p class="text-gray-600 mb-4">{{ \Illuminate\Support\Str::limit($destinasi->deskripsi, 80) }}
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-yellow-500">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span class="text-gray-600 ml-2">(4.8)</span>
                                </div>
                                <a href="{{ route('destinations.show', $destinasi->id) }}"
                                    class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500">
                        Tidak ada destinasi populer untuk ditampilkan.
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('destinations') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
                    Lihat Semua Destinasi
                </a>
            </div>
        </div>
    </section> --}}

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Mengapa Memilih Kami?</h2>
                <p class="text-xl text-gray-600">Kami memberikan pengalaman wisata terbaik untuk Anda</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-map-marked-alt text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Destinasi Terlengkap</h3>
                    <p class="text-gray-600">Ribuan destinasi wisata di seluruh Indonesia siap untuk Anda jelajahi</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Komunitas Traveler</h3>
                    <p class="text-gray-600">Bergabung dengan komunitas traveler dan berbagi pengalaman wisata</p>
                </div>
                <div class="text-center">
                    <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-star text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Review Terpercaya</h3>
                    <p class="text-gray-600">Dapatkan review dan rating dari traveler lain untuk membantu pilihan Anda</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Articles -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Artikel Terbaru</h2>
                <p class="text-xl text-gray-600">Tips dan panduan wisata untuk perjalanan yang sempurna</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 3; $i++)
                    <article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                        <img src="/placeholder.svg?height=200&width=400" alt="Artikel {{ $i }}"
                            class="w-full h-48 object-cover">
                        <div class="p-6">
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <i class="fas fa-calendar mr-2"></i>
                                <span>{{ date('d M Y') }}</span>
                                <i class="fas fa-user ml-4 mr-2"></i>
                                <span>Admin</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">Tips Wisata Hemat untuk Backpacker
                                {{ $i }}</h3>
                            <p class="text-gray-600 mb-4">Panduan lengkap untuk traveling dengan budget terbatas namun tetap
                                menyenangkan...</p>
                            <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Baca Selengkapnya</a>
                        </div>
                    </article>
                @endfor
            </div>
            <div class="text-center mt-12">
                <a href="#"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
                    Lihat Semua Artikel
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-purple-700 text-white">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">Siap Memulai Petualangan?</h2>
            <p class="text-xl mb-8">Bergabunglah dengan ribuan traveler lainnya dan temukan destinasi impian Anda</p>
            <div class="space-x-4">
                <a href="#"
                    class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold transition duration-300">
                    Mulai Sekarang
                </a>
                <a href="#"
                    class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg font-semibold transition duration-300">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>
@endsection
