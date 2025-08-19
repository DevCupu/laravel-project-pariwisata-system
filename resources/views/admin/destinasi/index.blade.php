@extends('layouts.admin')

@section('title', 'Kelola Destinasi')
@section('page-title', 'Kelola Destinasi')
@section('page-subtitle', 'Kelola semua destinasi wisata dengan mudah')

@section('content')
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8">
        <div class="mb-4 sm:mb-0">
            <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-1 sm:mb-2">Daftar Destinasi</h3>
            <p class="text-sm sm:text-base text-gray-600">Kelola dan monitor semua destinasi wisata</p>
        </div>
        <div class="flex items-center space-x-2 sm:space-x-3 w-full sm:w-auto">
            <button
                class="flex-1 sm:flex-none bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 sm:px-4 py-2 rounded-xl font-medium transition-colors duration-200 text-sm">
                <i class="fas fa-download mr-2"></i>Export
            </button>
            <a href="{{ route('admin.destinasi.create') }}"
                class="flex-1 sm:flex-none bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 sm:px-6 py-2 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl text-sm">
                <i class="fas fa-plus mr-2"></i>Tambah Destinasi
            </a>

        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <div class="card-hover bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-3 sm:mb-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total Destinasi</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $totalDestinasi }}</p>
                    <p class="text-xs sm:text-sm text-green-600 mt-1">+{{ $bulanIni }} bulan ini</p>
                </div>

                <div class="bg-blue-100 p-2 sm:p-3 rounded-xl sm:rounded-2xl self-end sm:self-auto">
                    <i class="fas fa-map-marked-alt text-lg sm:text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="card-hover bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-3 sm:mb-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Aktif</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $destinasiAktif }}</p>
                    <p class="text-xs sm:text-sm text-blue-600 mt-1">
                        {{ $totalDestinasi > 0 ? round(($destinasiAktif / $totalDestinasi) * 100) : 0 }}% dari total
                    </p>
                </div>

                <div class="bg-green-100 p-2 sm:p-3 rounded-xl sm:rounded-2xl self-end sm:self-auto">
                    <i class="fas fa-check-circle text-lg sm:text-2xl text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.destinasi.index') }}" class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4">
                <div>
                    <h4 class="text-base sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-0">Filter & Pencarian</h4>
                    <p class="text-xs text-gray-500">Gunakan filter di bawah untuk mencari destinasi berdasarkan nama,
                        kategori, lokasi, atau status. Anda dapat mengombinasikan beberapa filter sekaligus.</p>
                </div>
                <a href="{{ route('admin.destinasi.index') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 sm:mt-0">Reset Filter</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Destinasi</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama, lokasi, atau deskripsi destinasi..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm"
                            aria-label="Cari Destinasi">
                        <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                    <span class="text-xs text-gray-400">Contoh: Pantai, Gunung, Kota Batu</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-blue-500 text-sm"
                        aria-label="Filter Kategori">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-xs text-gray-400">Pilih kategori destinasi wisata</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <select name="lokasi"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-blue-500 text-sm"
                        aria-label="Filter Lokasi">
                        <option value="">Semua Lokasi</option>
                        @foreach ($lokasiUnik as $lokasi)
                            <option value="{{ $lokasi }}" {{ request('lokasi') == $lokasi ? 'selected' : '' }}>
                                {{ $lokasi }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-xs text-gray-400">Filter berdasarkan lokasi destinasi</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-blue-500 text-sm"
                        aria-label="Filter Status">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                    <span class="text-xs text-gray-400">Tampilkan destinasi aktif atau draft</span>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-medium transition-colors duration-200 text-sm">
                    Cari
                </button>
            </div>
        </div>
        @if (request()->hasAny(['search', 'kategori', 'lokasi', 'status']) &&
                (request('search') || request('kategori') || request('lokasi') || request('status')))
            <div class="mt-2 text-xs text-gray-600">
                <span>Filter aktif:</span>
                @if (request('search'))
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded mr-1">Cari: "{{ request('search') }}"</span>
                @endif
                @if (request('kategori'))
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded mr-1">Kategori:
                        {{ $kategoris->firstWhere('id', request('kategori'))->nama ?? '' }}</span>
                @endif
                @if (request('lokasi'))
                    <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded mr-1">Lokasi:
                        {{ request('lokasi') }}</span>
                @endif
                @if (request('status'))
                    <span class="bg-purple-100 text-purple-700 px-2 py-1 rounded mr-1">Status:
                        {{ ucfirst(request('status')) }}</span>
                @endif
            </div>
        @endif
    </form>

    <!-- Destinations Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach ($destinasis as $destinasi)
            <div
                class="card-hover bg-white border border-gray-200 rounded-xl sm:rounded-2xl overflow-hidden group flex flex-col h-full">
                <div class="relative">
                    <img src="{{ $destinasi->gambar ? asset('storage/' . $destinasi->gambar) : '/placeholder.svg?height=200&width=300' }}"
                        alt="{{ $destinasi->nama }}"
                        class="w-full h-40 sm:h-48 object-cover group-hover:scale-105 transition-transform duration-300">

                    <div class="absolute top-3 left-3">
                        <span
                            class="px-2 py-1 rounded-full text-xs font-medium
    {{ $destinasi->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $destinasi->status ? 'Aktif' : 'Nonaktif' }}
                        </span>

                    </div>

                    <div class="absolute top-3 right-3">
                        <span class="bg-white bg-opacity-90 text-gray-700 px-2 py-1 rounded-full text-xs font-medium">
                            {{ $destinasi->kategori->nama ?? 'Tanpa Kategori' }}
                        </span>
                    </div>

                    <div
                        class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.destinasi.edit', $destinasi->id) }}"
                                class="bg-white bg-opacity-90 hover:bg-white p-2 rounded-full shadow-sm" title="Edit">
                                <i class="fas fa-edit text-blue-600 text-sm"></i>
                            </a>

                            <a href="{{ route('admin.destinasi.show', $destinasi->id) }}"
                                class="bg-white bg-opacity-90 hover:bg-white p-2 rounded-full shadow-sm" title="View">
                                <i class="fas fa-eye text-green-600 text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between mb-3">
                        <h3
                            class="font-semibold text-gray-800 text-sm sm:text-lg group-hover:text-blue-600 transition-colors duration-200 line-clamp-2">
                            {{ $destinasi->nama }}
                        </h3>
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 mt-1 flex-shrink-0">
                    </div>

                    <div class="flex items-center text-gray-500 text-xs sm:text-sm mb-3">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <span>{{ $destinasi->lokasi }}</span>
                    </div>

                    <p class="text-gray-600 text-xs sm:text-sm mb-4 line-clamp-2">
                        {{ $destinasi->deskripsi }}
                    </p>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center text-yellow-500">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-xs {{ $i <= 4 ? '' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="text-gray-600 ml-1 text-xs">(4.7)</span>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">
                            <i class="fas fa-ticket-alt mr-1"></i>
                            @if ($destinasi->harga_tiket == 0)
                                <span
                                    class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-[10px]">Gratis</span>
                            @else
                                Rp{{ number_format($destinasi->harga_tiket, 0, ',', '.') }}
                            @endif
                        </div>
                    </div>


                    <div class="flex items-center space-x-2 mt-auto">
                        <a href="{{ route('admin.destinasi.edit', $destinasi->id) }}"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 sm:px-4 rounded-xl text-xs sm:text-sm font-medium transition-colors duration-200 text-center">
                            Edit
                        </a>
                        <a href="{{ route('admin.destinasi.show', $destinasi->id) }}"
                            class="px-3 sm:px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-xl text-xs sm:text-sm font-medium transition-colors duration-200 text-center">
                            Lihat
                        </a>
                        <form action="{{ route('admin.destinasi.destroy', $destinasi->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="p-2 text-gray-400 hover:text-red-600 rounded-xl transition-colors duration-200">
                                <i class="fas fa-trash text-xs sm:text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
