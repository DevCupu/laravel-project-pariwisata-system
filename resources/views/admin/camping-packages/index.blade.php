@extends('layouts.admin')

@section('title', 'Paket Camping')
@section('page-title', 'Kelola Paket Camping')
@section('page-subtitle', 'Manajemen kuota dan fasilitas paket glamping/camping')

@section('content')
    <div class="space-y-5">
        <div class="grid gap-4 sm:grid-cols-3">
            <x-ui.stat-card label="Total Paket" :value="number_format($stats['total'])" icon="fa-tent" tone="blue" meta="Semua paket" />
            <x-ui.stat-card label="Aktif" :value="number_format($stats['active'])" icon="fa-circle-check" tone="green" meta="Tampil di publik" />
            <x-ui.stat-card label="Highlight" :value="number_format($stats['featured'])" icon="fa-star" tone="amber" meta="Ditandai unggulan" />
        </div>

        <x-ui.card title="Daftar Paket" subtitle="Sinkron otomatis dengan fasilitas booking.">
            @slot('actions')
                <form method="GET" class="w-full lg:w-56">
                    <div class="relative">
                        <input type="text" name="q" value="{{ $filters['search'] }}" placeholder="Cari paket"
                            class="input input-bordered input-sm w-full pl-9">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-base-content/40"></i>
                    </div>
                </form>
                <form method="GET" class="flex flex-wrap items-center gap-2">
                    <input type="hidden" name="q" value="{{ $filters['search'] }}">
                    <select name="status" onchange="this.form.submit()"
                        class="select select-bordered select-sm">
                        <option value="">Semua Status</option>
                        <option value="active" @selected($filters['status'] === 'active')>Aktif</option>
                        <option value="inactive" @selected($filters['status'] === 'inactive')>Nonaktif</option>
                    </select>
                    <label class="inline-flex items-center gap-1.5 rounded-btn border border-warning/30 bg-warning/10 px-3 py-2 text-xs font-semibold text-warning">
                        <input type="checkbox" name="featured_only" value="1"
                            @checked($filters['featured_only']) onchange="this.form.submit()"
                            class="h-3.5 w-3.5 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                        Highlight saja
                    </label>
                </form>
                <x-ui.button :href="route('admin.camping-packages.create')">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Paket
                </x-ui.button>
            @endslot

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                        <tr>
                            <th class="text-left">Paket</th>
                            <th class="text-left">Harga & Durasi</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packages as $package)
                            <tr>
                                <td class="align-top">
                                    <p class="font-semibold text-slate-900">{{ $package->name }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-400">slug: {{ $package->slug }}</p>
                                    <p class="text-xs text-slate-500">Tagline: {{ $package->tagline ?: '—' }}</p>
                                    @if ($package->includes)
                                        <ul class="mt-2 list-inside list-disc text-xs text-slate-500">
                                            @foreach ($package->includes as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <p class="font-semibold text-slate-900">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                                    <p class="text-xs text-slate-500">Durasi: {{ $package->nights ?? 1 }} malam</p>
                                    <p class="text-xs text-slate-500">Kapasitas: {{ $package->capacity ?? 0 }} orang</p>
                                    <p class="text-xs text-slate-500">Urutan: {{ $package->sort_order }}</p>
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-col gap-1.5">
                                        <x-ui.badge :tone="$package->is_active ? 'success' : 'neutral'" dot>
                                            {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </x-ui.badge>
                                        <x-ui.badge :tone="$package->is_featured ? 'warning' : 'neutral'">
                                            {{ $package->is_featured ? 'Highlight' : 'Biasa' }}
                                        </x-ui.badge>
                                    </div>
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <x-ui.button size="sm" variant="soft-blue" :href="route('admin.camping-packages.edit', $package)">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            Edit
                                        </x-ui.button>
                                        <form method="POST" action="{{ route('admin.camping-packages.toggle-status', $package) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="secondary" type="submit">
                                                <i class="fas fa-toggle-on text-[10px]"></i>
                                                {{ $package->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.camping-packages.toggle-featured', $package) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="warning" type="submit">
                                                <i class="fas fa-star text-[10px]"></i>
                                                {{ $package->is_featured ? 'Hapus Highlight' : 'Highlight' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.camping-packages.destroy', $package) }}"
                                            data-confirm="Hapus paket &quot;{{ $package->name }}&quot;? Ini juga akan memengaruhi slot booking terkait.">
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
                                    <x-ui.empty-state icon="fa-tent" title="Belum ada paket camping"
                                        text="Tambahkan paket pertama untuk ditawarkan kepada pengunjung." class="py-8" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-base-300/60 px-6 py-4">
                {{ $packages->links('vendor.pagination.admin') }}
            </div>
        </x-ui.card>
    </div>
@endsection
