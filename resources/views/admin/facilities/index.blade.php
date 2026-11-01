@extends('layouts.admin')

@section('title', 'Fasilitas')
@section('page-title', 'Manajemen Fasilitas')
@section('page-subtitle', 'Atur kategori, kapasitas, dan ketersediaan fasilitas untuk booking')

@section('content')
    <div class="space-y-5">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui.stat-card label="Total Fasilitas" :value="number_format($stats['total'])" icon="fa-building" tone="blue" meta="Semua fasilitas" />
            <x-ui.stat-card label="Aktif" :value="number_format($stats['active'])" icon="fa-circle-check" tone="green" meta="Tampil untuk booking" />
            <x-ui.stat-card label="Nonaktif" :value="number_format($stats['inactive'])" icon="fa-circle-xmark" tone="red" meta="Disembunyikan" />
            <x-ui.stat-card label="Kategori" :value="count($categoryOptions)" icon="fa-layer-group" tone="violet" meta="Kategori berbeda" />
        </div>

        <x-ui.card title="Daftar Fasilitas" subtitle="Kelola fasilitas yang tersedia untuk proses booking.">
            @slot('actions')
                <form method="GET" class="w-full lg:w-56">
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama fasilitas"
                            class="input input-bordered input-sm w-full pl-9">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-base-content/40"></i>
                    </div>
                </form>
                <form method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <select name="category" onchange="this.form.submit()"
                        class="select select-bordered select-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($categoryOptions as $category)
                            <option value="{{ $category }}" @selected($categoryFilter === $category)>
                                {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $category)) }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" onchange="this.form.submit()"
                        class="select select-bordered select-sm">
                        <option value="">Semua Status</option>
                        <option value="active" @selected($statusFilter === 'active')>Aktif</option>
                        <option value="inactive" @selected($statusFilter === 'inactive')>Nonaktif</option>
                    </select>
                </form>
                <x-ui.button :href="route('admin.facilities.create')">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Fasilitas
                </x-ui.button>
            @endslot

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                        <tr>
                            <th class="text-left">Fasilitas</th>
                            <th class="text-left">Kategori</th>
                            <th class="text-left">Kapasitas</th>
                            <th class="text-left">Urutan</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Deskripsi</th>
                            <th class="text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facilities as $facility)
                            <tr>
                                <td class="align-top">
                                    <p class="font-semibold text-slate-900">{{ $facility->name }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-400">slug: {{ $facility->slug }}</p>
                                </td>
                                <td class="align-top text-sm text-base-content/70">
                                    {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $facility->category)) }}
                                </td>
                                <td class="align-top font-semibold">
                                    {{ $facility->capacity_per_day ?: '–' }} / hari
                                </td>
                                <td class="align-top text-base-content/70">
                                    {{ $facility->sort_order }}
                                </td>
                                <td class="align-top">
                                    <x-ui.badge :tone="$facility->is_active ? 'success' : 'neutral'" dot>
                                        {{ $facility->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </x-ui.badge>
                                </td>
                                <td class="align-top text-sm text-base-content/70">
                                    {{ $facility->description ? \Illuminate\Support\Str::limit($facility->description, 80) : '–' }}
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <x-ui.button size="sm" variant="soft-blue" :href="route('admin.facilities.edit', $facility)">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            Edit
                                        </x-ui.button>
                                        <form method="POST" action="{{ route('admin.facilities.toggle', $facility) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="q" value="{{ request('q') }}">
                                            <input type="hidden" name="category" value="{{ request('category') }}">
                                            <input type="hidden" name="status" value="{{ request('status') }}">
                                            <x-ui.button size="sm" variant="secondary" type="submit">
                                                <i class="fas fa-toggle-on text-[10px]"></i>
                                                {{ $facility->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}"
                                            data-confirm="Hapus fasilitas &quot;{{ $facility->name }}&quot;? Booking terkait mungkin akan terpengaruh.">
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
                                    <x-ui.empty-state icon="fa-building" title="Belum ada data fasilitas"
                                        text="Tambahkan fasilitas pertama agar bisa dipilih saat booking." class="py-8" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-base-300/60 px-6 py-4">
                {{ $facilities->links('vendor.pagination.admin') }}
            </div>
        </x-ui.card>
    </div>
@endsection
