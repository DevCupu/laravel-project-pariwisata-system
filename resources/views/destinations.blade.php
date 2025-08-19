@extends('layouts.public')

@section('title', 'Destinasi Wisata')

@section('content')
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">Destinasi Wisata</h1>
            <p class="text-xl">Jelajahi keindahan Indonesia dari Sabang sampai Merauke</p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-8 bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap gap-4">
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option>Semua Kategori</option>
                        <option>Pantai</option>
                        <option>Gunung</option>
                        <option>Budaya</option>
                        <option>Kuliner</option>
                        <option>Sejarah</option>
                    </select>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option>Semua Lokasi</option>
                        <option>Jawa Barat</option>
                        <option>Jawa Tengah</option>
                        <option>Jawa Timur</option>
                        <option>Bali</option>
                        <option>Sumatra</option>
                        <option>Kalimantan</option>
                        <option>Sulawesi</option>
                        <option>Papua</option>
                    </select>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option>Urutkan</option>
                        <option>Terpopuler</option>
                        <option>Rating Tertinggi</option>
                        <option>Nama A-Z</option>
                        <option>Nama Z-A</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Destinations Grid -->
    <section class="py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse ($destinasis as $destinasi)
                    <div
                        class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100">
                        <div class="relative">
                            <img src="{{ asset('storage/' . $destinasi->gambar) }}" alt="{{ $destinasi->nama }}"
                                class="w-full h-60 object-cover group-hover:scale-105 transition-transform duration-300">
                            <span
                                class="absolute top-4 left-4 bg-blue-600 text-white text-xs px-3 py-1 rounded-full shadow">
                                {{ $destinasi->kategori->nama ?? 'Wisata' }}

                            </span>
                        </div>
                        <div class="p-6 flex flex-col h-48">
                            <h3
                                class="text-2xl font-semibold text-gray-800 mb-2 group-hover:text-blue-600 transition-colors">
                                {{ $destinasi->nama }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-3 flex-1">
                                {{ \Illuminate\Support\Str::limit($destinasi->deskripsi, 90) }}
                            </p>
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-blue-500"></i>
                                    {{ $destinasi->lokasi }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-ticket-alt text-green-500"></i>
                                    {{ $destinasi->harga_tiket > 0 ? 'Rp' . number_format($destinasi->harga_tiket, 0, ',', '.') : 'Gratis' }}
                                </span>
                            </div>
                            <a href="{{ route('destinations.show', $destinasi->id) }}"
                                class="mt-auto inline-block bg-blue-600 text-white px-4 py-2 rounded-lg font-medium shadow hover:bg-blue-700 transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-20 text-gray-400 text-lg">
                        Tidak ada destinasi ditemukan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Pagination -->
    <div class="flex justify-center mt-12">
        <nav class="flex items-center space-x-2">
            <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50" disabled>
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">1</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">2</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">3</button>
            <span class="px-2">...</span>
            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">10</button>
            <button class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fas fa-chevron-right"></i>
            </button>
        </nav>
    </div>
    </div>
    </section>
@endsection
