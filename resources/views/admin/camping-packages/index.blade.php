@extends('layouts.admin')

@section('title', 'Paket Camping')
@section('page-title', 'Kelola Paket Camping')
@section('page-subtitle', 'Manajemen kuota dan fasilitas paket glamping/camping')

@section('content')
    <div class="space-y-5">
        @if (session('admin_success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('admin_success') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-4">
                <p class="text-xs font-semibold uppercase text-gray-500">Total Paket</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4">
                <p class="text-xs font-semibold uppercase text-gray-500">Aktif</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4">
                <p class="text-xs font-semibold uppercase text-gray-500">Highlight</p>
                <p class="text-2xl font-bold text-amber-500">{{ $stats['featured'] }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Paket</h3>
                    <p class="text-sm text-gray-500">Sinkron otomatis dengan fasilitas booking.</p>
                </div>
                <div class="flex w-full flex-col gap-3 lg:w-auto lg:flex-row lg:items-center">
                    <form method="GET" class="w-full lg:w-64">
                        <div class="relative">
                            <input type="text" name="q" value="{{ $filters['search'] }}" placeholder="Cari paket"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pl-10 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </form>
                    <form method="GET" class="flex flex-wrap items-center gap-2 text-sm">
                        <input type="hidden" name="q" value="{{ $filters['search'] }}">
                        <select name="status" onchange="this.form.submit()"
                            class="rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="active" @selected($filters['status'] === 'active')>Aktif</option>
                            <option value="inactive" @selected($filters['status'] === 'inactive')>Nonaktif</option>
                        </select>
                        <label class="inline-flex items-center gap-2 rounded-xl border border-amber-200 px-4 py-2.5 text-xs font-semibold text-amber-600">
                            <input type="checkbox" name="featured_only" value="1"
                                @checked($filters['featured_only']) onchange="this.form.submit()"
                                class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                            Highlight saja
                        </label>
                    </form>
                    <a href="{{ route('admin.camping-packages.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-700">
                        <i class="fas fa-plus text-xs"></i>
                        Tambah Paket
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[900px] w-full border-collapse text-sm text-gray-800">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Paket</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Harga & Durasi</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Status</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packages as $package)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="px-5 py-4 align-top">
                                    <p class="font-semibold text-gray-900">{{ $package->name }}</p>
                                    <p class="text-xs text-gray-500">Slug: {{ $package->slug }}</p>
                                    <p class="text-xs text-gray-500">Tagline: {{ $package->tagline ?: '—' }}</p>
                                    @if ($package->includes)
                                        <ul class="mt-2 list-inside list-disc text-xs text-gray-500">
                                            @foreach ($package->includes as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500">Durasi: {{ $package->nights ?? 1 }} malam</p>
                                    <p class="text-xs text-gray-500">Kapasitas: {{ $package->capacity ?? 0 }} orang</p>
                                    <p class="text-xs text-gray-500">Urutan: {{ $package->sort_order }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="flex flex-col gap-2 text-xs font-semibold">
                                        <span class="inline-flex items-center rounded-full px-3 py-0.5 {{ $package->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                        <span class="inline-flex items-center rounded-full px-3 py-0.5 {{ $package->is_featured ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $package->is_featured ? 'Highlight' : 'Biasa' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="flex flex-wrap gap-2 text-xs">
                                        <a href="{{ route('admin.camping-packages.edit', $package) }}"
                                            class="inline-flex items-center gap-1 rounded-lg border border-green-200 bg-green-50 px-3 py-1.5 font-semibold text-green-700 hover:bg-green-100">
                                            <i class="fas fa-pen text-xs"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.camping-packages.toggle-status', $package) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-1.5 font-semibold text-gray-700 hover:bg-gray-50">
                                                {{ $package->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.camping-packages.toggle-featured', $package) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 font-semibold text-amber-700 hover:bg-amber-100">
                                                {{ $package->is_featured ? 'Hapus Highlight' : 'Jadikan Highlight' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.camping-packages.destroy', $package) }}"
                                            onsubmit="return confirm('Hapus paket ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 font-semibold text-red-700 hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada paket camping.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-6 py-4">
                {{ $packages->links('vendor.pagination.admin') }}
            </div>
        </div>
    </div>
@endsection
