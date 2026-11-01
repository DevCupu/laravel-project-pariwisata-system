@extends('layouts.admin')

@section('title', 'Fasilitas Umum')
@section('page-title', 'Kelola Fasilitas Umum')
@section('page-subtitle', 'Informasi fasilitas umum yang tampil di halaman profil destinasi')

@section('content')
    <div class="space-y-5">
        <div class="grid gap-4 sm:grid-cols-2">
            <x-ui.stat-card label="Total Fasilitas" :value="number_format($stats['total'])" icon="fa-circle-info" tone="blue" meta="Semua informasi" />
            <x-ui.stat-card label="Aktif" :value="number_format($stats['active'])" icon="fa-circle-check" tone="green" meta="Tampil di publik" />
        </div>

        <x-ui.card title="Daftar Fasilitas Umum" subtitle="Gunakan ikon Font Awesome agar konsisten.">
            @slot('actions')
                <form method="GET" class="w-full lg:w-56">
                    <div class="relative">
                        <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Cari fasilitas"
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
                <x-ui.button :href="route('admin.general-facilities.create')">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Fasilitas
                </x-ui.button>
            @endslot

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                        <tr>
                            <th class="text-left">Fasilitas</th>
                            <th class="text-left">Informasi</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facilities as $facility)
                            <tr>
                                <td class="align-top">
                                    <p class="font-semibold text-slate-900">{{ $facility->name }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-400">slug: {{ $facility->slug }}</p>
                                    <p class="text-xs text-slate-500">Ikon: {{ $facility->icon ?: '—' }}</p>
                                </td>
                                <td class="align-top">
                                    <p class="text-sm text-slate-600">{{ $facility->description ?: 'Belum ada deskripsi.' }}</p>
                                    @if ($facility->availability_note)
                                        <p class="mt-0.5 text-xs text-amber-600">Catatan: {{ $facility->availability_note }}</p>
                                    @endif
                                    <p class="text-[11px] text-slate-400">Urutan: {{ $facility->sort_order }}</p>
                                </td>
                                <td class="align-top">
                                    <x-ui.badge :tone="$facility->is_active ? 'success' : 'neutral'" dot>
                                        {{ $facility->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </x-ui.badge>
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <x-ui.button size="sm" variant="soft-blue" :href="route('admin.general-facilities.edit', $facility)">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            Edit
                                        </x-ui.button>
                                        <form method="POST" action="{{ route('admin.general-facilities.toggle-status', $facility) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="secondary" type="submit">
                                                {{ $facility->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.general-facilities.destroy', $facility) }}"
                                            data-confirm="Hapus fasilitas &quot;{{ $facility->name }}&quot;?">
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
                                    <x-ui.empty-state icon="fa-circle-info" title="Belum ada fasilitas umum"
                                        text="Tambahkan informasi fasilitas umum agar pengunjung lebih mudah memahami layanan." class="py-8" />
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
