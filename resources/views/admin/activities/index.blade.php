@extends('layouts.admin')

@section('title', 'Aktivitas & Wahana')
@section('page-title', 'Kelola Aktivitas')
@section('page-subtitle', 'Atur konten aktivitas yang tampil di halaman publik')

@section('content')
    <div class="space-y-5">
        <div class="grid gap-4 sm:grid-cols-3">
            <x-ui.stat-card label="Total Aktivitas" :value="number_format($stats['total'])" icon="fa-layer-group"
                tone="blue" meta="Semua aktivitas" />
            <x-ui.stat-card label="Aktif" :value="number_format($stats['active'])" icon="fa-circle-check"
                tone="green" meta="Tampil di halaman publik" />
            <x-ui.stat-card label="Highlight" :value="number_format($stats['featured'])" icon="fa-star"
                tone="amber" meta="Ditandai unggulan" />
        </div>

        <x-ui.card title="Daftar Aktivitas" subtitle="Kelola wahana, pengalaman, dan aktivitas lain di Dante Pine.">
            @slot('actions')
                <form method="GET" class="w-full lg:w-56">
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari aktivitas"
                            class="w-full rounded-xl border border-gray-200 bg-white py-2 pl-9 pr-3 text-sm shadow-sm placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                    </div>
                </form>
                <form method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <select name="category" onchange="this.form.submit()"
                        class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        <option value="">Semua Kategori</option>
                        @foreach ($categoryOptions as $category)
                            <option value="{{ $category }}" @selected($categoryFilter === $category)>
                                {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $category)) }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" onchange="this.form.submit()"
                        class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        <option value="">Semua Status</option>
                        <option value="active" @selected($statusFilter === 'active')>Aktif</option>
                        <option value="inactive" @selected($statusFilter === 'inactive')>Nonaktif</option>
                    </select>
                </form>
                <x-ui.button :href="route('admin.activities.create')">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Aktivitas
                </x-ui.button>
            @endslot

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                        <tr>
                            <th class="text-left">Aktivitas</th>
                            <th class="text-left">Kategori</th>
                            <th class="text-left">Durasi</th>
                            <th class="text-left">Harga Mulai</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Highlight</th>
                            <th class="text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activities as $activity)
                            <tr>
                                <td class="align-top">
                                    <p class="font-semibold text-gray-900">{{ $activity->title }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">{{ $activity->short_description }}</p>
                                    <p class="text-[11px] text-gray-400">slug: {{ $activity->slug }}</p>
                                </td>
                                <td class="align-top">{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $activity->category)) }}</td>
                                <td class="align-top">{{ $activity->duration_minutes ? $activity->duration_minutes . ' mnt' : '–' }}</td>
                                <td class="align-top">{{ $activity->price_from ? 'Rp ' . number_format($activity->price_from, 0, ',', '.') : 'Hubungi kami' }}</td>
                                <td class="align-top">
                                    <x-ui.badge :tone="$activity->is_active ? 'success' : 'neutral'" dot>
                                        {{ $activity->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </x-ui.badge>
                                </td>
                                <td class="align-top">
                                    <x-ui.badge :tone="$activity->is_featured ? 'warning' : 'neutral'">
                                        {{ $activity->is_featured ? 'Ditampilkan' : 'Biasa' }}
                                    </x-ui.badge>
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <x-ui.button size="sm" variant="soft-blue"
                                            :href="route('admin.activities.edit', $activity)">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            Edit
                                        </x-ui.button>
                                        <form method="POST" action="{{ route('admin.activities.toggle', $activity) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="secondary" type="submit">
                                                <i class="fas fa-toggle-on text-[10px]"></i>
                                                {{ $activity->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.activities.featured', $activity) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="warning" type="submit">
                                                <i class="fas fa-star text-[10px]"></i>
                                                {{ $activity->is_featured ? 'Biasa' : 'Highlight' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.activities.destroy', $activity) }}"
                                            data-confirm="Hapus aktivitas &quot;{{ $activity->title }}&quot;? Ini juga akan menghapus fasilitas booking terkait.">
                                            @csrf
                                            @method('DELETE')
                                            <x-ui.button size="sm" variant="danger" type="submit">
                                                <i class="fas fa-trash text-[10px]"></i>
                                                Hapus
                                            </x-ui.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-ui.empty-state icon="fa-mountain" title="Belum ada aktivitas"
                                        text="Tambahkan aktivitas pertama untuk ditampilkan di halaman publik."
                                        class="py-8" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-6 py-4">
                {{ $activities->links('vendor.pagination.admin') }}
            </div>
        </x-ui.card>
    </div>
@endsection
