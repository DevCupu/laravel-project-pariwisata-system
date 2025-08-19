@extends('layouts.public')

@section('title', 'Artikel Wisata')

@section('content')
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-green-600 to-blue-700 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">Artikel Wisata</h1>
            <p class="text-xl">Tips, panduan, dan inspirasi untuk perjalanan sempurna Anda</p>
        </div>
    </section>

    <!-- Featured Article -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="md:flex">
                    <div class="md:w-1/2">
                        <img src="/placeholder.svg?height=400&width=600" alt="Featured Article"
                            class="w-full h-64 md:h-full object-cover">
                    </div>
                    <div class="md:w-1/2 p-8">
                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <span
                                class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold mr-4">Featured</span>
                            <i class="fas fa-calendar mr-2"></i>
                            <span>{{ date('d M Y') }}</span>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-4">Panduan Lengkap Wisata Backpacker di Indonesia
                        </h2>
                        <p class="text-gray-600 mb-6">Temukan tips dan trik untuk menjelajahi Indonesia dengan budget
                            terbatas namun tetap mendapatkan pengalaman yang tak terlupakan. Dari transportasi hingga
                            akomodasi...</p>
                        <a href="#"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
                            Baca Selengkapnya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-wrap items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Artikel Terbaru</h2>
                <div class="flex gap-4">
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option>Semua Kategori</option>
                        <option>Tips Wisata</option>
                        <option>Panduan Destinasi</option>
                        <option>Kuliner</option>
                        <option>Budaya</option>
                        <option>Adventure</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 9; $i++)
                    <article class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                        <img src="/placeholder.svg?height=200&width=400" alt="Artikel {{ $i }}"
                            class="w-full h-48 object-cover">
                        <div class="p-6">
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>
                                    <span>{{ date('d M Y', strtotime('-' . rand(1, 30) . ' days')) }}</span>
                                </div>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ $i % 3 == 0 ? 'Tips' : ($i % 2 == 0 ? 'Panduan' : 'Review') }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-3">
                                {{ $i % 4 == 0 ? 'Destinasi Tersembunyi di Indonesia Timur' : ($i % 3 == 0 ? 'Tips Hemat Liburan Keluarga' : ($i % 2 == 0 ? 'Kuliner Khas yang Wajib Dicoba' : 'Panduan Wisata Solo Traveler')) }}
                            </h3>
                            <p class="text-gray-600 mb-4">Temukan informasi menarik dan tips berguna untuk perjalanan wisata
                                Anda. Artikel ini akan membantu merencanakan liburan yang sempurna...</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-user mr-2"></i>
                                    <span>Admin Travel</span>
                                </div>
                                <a href="#" class="text-green-600 hover:text-green-800 font-semibold">Baca
                                    Selengkapnya</a>
                            </div>
                        </div>
                    </article>
                @endfor
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button
                    class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
                    Muat Lebih Banyak
                </button>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-gradient-to-r from-green-600 to-blue-700 text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Berlangganan Newsletter</h2>
            <p class="text-xl mb-8">Dapatkan artikel terbaru dan tips wisata langsung di email Anda</p>
            <div class="flex flex-col md:flex-row gap-4 max-w-md mx-auto">
                <input type="email" placeholder="Masukkan email Anda"
                    class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white">
                <button
                    class="bg-white text-green-600 hover:bg-gray-100 px-6 py-3 rounded-lg font-semibold transition duration-300">
                    Berlangganan
                </button>
            </div>
        </div>
    </section>
@endsection
