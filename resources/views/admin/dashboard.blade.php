@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan aktivitas dan statistik website Anda')

@section('content')
<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
    <div class="relative z-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Selamat Datang Kembali! 👋</h1>
                <p class="text-blue-100 text-lg">Hari ini adalah hari yang baik untuk mengelola konten wisata</p>
                <div class="flex items-center mt-4 space-x-6">
                    <div class="flex items-center">
                        <i class="fas fa-eye mr-2"></i>
                        <span class="text-sm">12.5K views hari ini</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        <span class="text-sm">234 pengguna baru</span>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="w-32 h-32 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-4xl"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="absolute top-0 right-0 w-64 h-64 bg-white bg-opacity-10 rounded-full -mr-32 -mt-32"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white bg-opacity-10 rounded-full -ml-24 -mb-24"></div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <button class="card-hover bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center group">
        <div class="bg-blue-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors duration-200">
            <i class="fas fa-plus text-2xl text-blue-600"></i>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">Tambah Destinasi</h3>
        <p class="text-sm text-gray-500">Destinasi wisata baru</p>
    </button>

    <button class="card-hover bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center group">
        <div class="bg-green-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition-colors duration-200">
            <i class="fas fa-edit text-2xl text-green-600"></i>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">Tulis Artikel</h3>
        <p class="text-sm text-gray-500">Artikel wisata baru</p>
    </button>

    <button class="card-hover bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center group">
        <div class="bg-purple-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors duration-200">
            <i class="fas fa-calendar-plus text-2xl text-purple-600"></i>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">Buat Event</h3>
        <p class="text-sm text-gray-500">Event wisata baru</p>
    </button>

    <button class="card-hover bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center group">
        <div class="bg-orange-100 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-200 transition-colors duration-200">
            <i class="fas fa-image text-2xl text-orange-600"></i>
        </div>
        <h3 class="font-semibold text-gray-800 mb-1">Upload Foto</h3>
        <p class="text-sm text-gray-500">Galeri foto baru</p>
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="card-hover bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-blue-100 p-3 rounded-2xl">
                <i class="fas fa-map-marked-alt text-2xl text-blue-600"></i>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-800">156</div>
                <div class="text-sm text-gray-500">Total Destinasi</div>
            </div>
        </div>
        <div class="flex items-center">
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">+12 bulan ini</span>
        </div>
        <div class="absolute bottom-0 right-0 w-20 h-20 bg-blue-50 rounded-full -mr-10 -mb-10"></div>
    </div>

    <div class="card-hover bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-green-100 p-3 rounded-2xl">
                <i class="fas fa-newspaper text-2xl text-green-600"></i>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-800">89</div>
                <div class="text-sm text-gray-500">Total Artikel</div>
            </div>
        </div>
        <div class="flex items-center">
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">+5 minggu ini</span>
        </div>
        <div class="absolute bottom-0 right-0 w-20 h-20 bg-green-50 rounded-full -mr-10 -mb-10"></div>
    </div>

    <div class="card-hover bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-purple-100 p-3 rounded-2xl">
                <i class="fas fa-users text-2xl text-purple-600"></i>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-800">2.8K</div>
                <div class="text-sm text-gray-500">Total Pengguna</div>
            </div>
        </div>
        <div class="flex items-center">
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">+234 bulan ini</span>
        </div>
        <div class="absolute bottom-0 right-0 w-20 h-20 bg-purple-50 rounded-full -mr-10 -mb-10"></div>
    </div>

    <div class="card-hover bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-yellow-100 p-3 rounded-2xl">
                <i class="fas fa-calendar-alt text-2xl text-yellow-600"></i>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-800">23</div>
                <div class="text-sm text-gray-500">Event Aktif</div>
            </div>
        </div>
        <div class="flex items-center">
            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">8 mendatang</span>
        </div>
        <div class="absolute bottom-0 right-0 w-20 h-20 bg-yellow-50 rounded-full -mr-10 -mb-10"></div>
    </div>
</div>

<!-- Charts and Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Visitor Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Statistik Pengunjung</h3>
                <p class="text-sm text-gray-500">Tren pengunjung website dalam 7 hari terakhir</p>
            </div>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 text-sm bg-blue-100 text-blue-600 rounded-lg">7 Hari</button>
                <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">30 Hari</button>
                <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-lg">3 Bulan</button>
            </div>
        </div>
        <div class="h-64 bg-gradient-to-br from-blue-50 to-purple-50 rounded-xl flex items-center justify-center">
            <div class="text-center text-gray-500">
                <i class="fas fa-chart-line text-4xl mb-4 text-blue-400"></i>
                <p class="font-medium">Grafik Pengunjung</p>
                <p class="text-sm">Integrasi dengan analytics akan menampilkan data real-time</p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua</button>
        </div>
        <div class="space-y-4">
            @for ($i = 1; $i <= 5; $i++)
            <div class="flex items-start space-x-3">
                <div class="bg-{{ $i % 3 == 0 ? 'blue' : ($i % 2 == 0 ? 'green' : 'purple') }}-100 p-2 rounded-xl">
                    <i class="fas fa-{{ $i % 3 == 0 ? 'plus' : ($i % 2 == 0 ? 'edit' : 'star') }} text-{{ $i % 3 == 0 ? 'blue' : ($i % 2 == 0 ? 'green' : 'purple') }}-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">
                        {{ $i % 3 == 0 ? 'Destinasi baru ditambahkan' : ($i % 2 == 0 ? 'Artikel diperbarui' : 'Review baru diterima') }}
                    </p>
                    <p class="text-xs text-gray-500">{{ rand(1, 60) }} menit yang lalu</p>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>

<!-- Popular Destinations & Pending Tasks -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Popular Destinations -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Destinasi Terpopuler</h3>
            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Detail</button>
        </div>
        <div class="space-y-4">
            @for ($i = 1; $i <= 5; $i++)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                <div class="flex items-center space-x-3">
                    <img src="/placeholder.svg?height=40&width=40" alt="Destinasi" class="w-10 h-10 rounded-xl object-cover">
                    <div>
                        <p class="font-medium text-gray-800">Destinasi {{ $i }}</p>
                        <p class="text-sm text-gray-500">{{ rand(100, 1000) }} pengunjung</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="flex items-center text-yellow-400 mb-1">
                        @for ($j = 1; $j <= 5; $j++)
                        <i class="fas fa-star text-xs {{ $j <= 4 ? '' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                    <p class="text-xs text-green-600 font-medium">+{{ rand(5, 25) }}%</p>
                </div>
            </div>
            @endfor
        </div>
    </div>

</div>


@endsection
