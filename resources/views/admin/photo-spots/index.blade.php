@extends('layouts.admin')

@section('title', 'Spot Foto')
@section('page-title', 'Kelola Spot Foto')
@section('page-subtitle', 'Atur titik swafoto favorit untuk ditampilkan di halaman publik')

@section('content')
    <div class="space-y-5">
        <div class="grid gap-4 sm:grid-cols-3">
            <x-ui.stat-card label="Total Spot" :value="number_format($stats['total'])" icon="fa-camera" tone="blue" meta="Semua titik foto" />
            <x-ui.stat-card label="Aktif" :value="number_format($stats['active'])" icon="fa-circle-check" tone="green" meta="Tampil di publik" />
            <x-ui.stat-card label="Highlight" :value="number_format($stats['featured'])" icon="fa-star" tone="amber" meta="Ditandai unggulan" />
        </div>

        <x-ui.card title="Daftar Spot Foto" subtitle="Gunakan urutan untuk mengatur prioritas tampilan.">
            @slot('actions')
                <form method="GET" class="w-full lg:w-56">
                    <div class="relative">
                        <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Cari spot"
                            class="input input-bordered input-sm w-full pl-9">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-base-content/40"></i>
                    </div>
                </form>
                <form method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="q" value="{{ $searchQuery }}">
                    <select name="status" onchange="this.form.submit()"
                        class="select select-bordered select-sm">
                        <option value="">Semua Status</option>
                        <option value="active" @selected($statusFilter === 'active')>Aktif</option>
                        <option value="inactive" @selected($statusFilter === 'inactive')>Nonaktif</option>
                    </select>
                </form>
                <x-ui.button :href="route('admin.photo-spots.create')">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Spot
                </x-ui.button>
            @endslot

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                        <tr>
                            <th class="text-left">Spot</th>
                            <th class="text-left">Informasi</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($spots as $spot)
                            <tr>
                                <td class="align-top">
                                    <p class="font-semibold text-slate-900">{{ $spot->title }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-400">slug: {{ $spot->slug }}</p>
                                    @if ($spot->location_text)
                                        <p class="text-xs text-slate-500">Lokasi: {{ $spot->location_text }}</p>
                                    @endif
                                </td>
                                <td class="align-top text-sm text-base-content/70">
                                    <p>Waktu terbaik: {{ $spot->best_time ?: '—' }}</p>
                                    <p>Urutan: {{ $spot->sort_order }}</p>
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-col gap-1.5">
                                        <x-ui.badge :tone="$spot->is_active ? 'success' : 'neutral'" dot>
                                            {{ $spot->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </x-ui.badge>
                                        <x-ui.badge :tone="$spot->is_featured ? 'warning' : 'neutral'">
                                            {{ $spot->is_featured ? 'Highlight' : 'Biasa' }}
                                        </x-ui.badge>
                                    </div>
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <x-ui.button size="sm" variant="soft-blue" :href="route('admin.photo-spots.edit', $spot)">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            Edit
                                        </x-ui.button>
                                        <form method="POST" action="{{ route('admin.photo-spots.toggle-status', $spot) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="secondary" type="submit">
                                                <i class="fas fa-toggle-on text-[10px]"></i>
                                                {{ $spot->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.photo-spots.toggle-featured', $spot) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="warning" type="submit">
                                                <i class="fas fa-star text-[10px]"></i>
                                                {{ $spot->is_featured ? 'Hapus Highlight' : 'Highlight' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.photo-spots.destroy', $spot) }}"
                                            data-confirm="Hapus spot &quot;{{ $spot->title }}&quot;?">
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
                                <td colspan="4">
                                    <x-ui.empty-state icon="fa-camera" title="Belum ada spot foto"
                                        text="Tambahkan spot foto pertama untuk galeri publik." class="py-8" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-base-300/60 px-6 py-4">
                {{ $spots->links('vendor.pagination.admin') }}
            </div>
        </x-ui.card>
    </div>
@endsection
