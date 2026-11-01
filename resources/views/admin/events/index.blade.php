@extends('layouts.admin')

@section('title', 'Events & Promo')
@section('page-title', 'Kelola Event')
@section('page-subtitle', 'Atur event dan promo yang tampil di halaman publik')

@section('content')
    <div class="space-y-5">
        <x-ui.card title="Daftar Event" subtitle="Kelola event, promo, dan kegiatan khusus.">
            @slot('actions')
                <form method="GET" class="w-full lg:w-56">
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari event"
                            class="input input-bordered input-sm w-full pl-9">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-base-content/40"></i>
                    </div>
                </form>
                <form method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <select name="status" onchange="this.form.submit()"
                        class="select select-bordered select-sm">
                        <option value="">Semua Status</option>
                        <option value="active" @selected($statusFilter === 'active')>Aktif</option>
                        <option value="inactive" @selected($statusFilter === 'inactive')>Nonaktif</option>
                    </select>
                </form>
                <x-ui.button :href="route('admin.events.create')">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Event
                </x-ui.button>
            @endslot

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                        <tr>
                            <th class="text-left">Event</th>
                            <th class="text-left">Tipe</th>
                            <th class="text-left">Tanggal</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Highlight</th>
                            <th class="text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                            <tr>
                                <td class="align-top">
                                    <p class="font-semibold text-slate-900">{{ $event->title }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-400">slug: {{ $event->slug }}</p>
                                    <p class="text-xs text-slate-500">{{ $event->excerpt }}</p>
                                </td>
                                <td class="align-top">
                                    <x-ui.badge :tone="$event->type === 'promo' ? 'success' : 'neutral'">
                                        {{ ucfirst($event->type) }}
                                    </x-ui.badge>
                                </td>
                                <td class="align-top">
                                    {{ $event->start_date ? $event->start_date->format('d M Y') : '–' }}
                                    @if ($event->end_date) – {{ $event->end_date->format('d M Y') }} @endif
                                </td>
                                <td class="align-top">
                                    <x-ui.badge :tone="$event->is_active ? 'success' : 'neutral'" dot>
                                        {{ $event->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </x-ui.badge>
                                </td>
                                <td class="align-top">
                                    <x-ui.badge :tone="$event->is_featured ? 'warning' : 'neutral'">
                                        {{ $event->is_featured ? 'Ditampilkan' : 'Biasa' }}
                                    </x-ui.badge>
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <x-ui.button size="sm" variant="soft-blue" :href="route('admin.events.edit', $event)">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            Edit
                                        </x-ui.button>
                                        <form method="POST" action="{{ route('admin.events.toggle-status', $event) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="secondary" type="submit">
                                                <i class="fas fa-toggle-on text-[10px]"></i>
                                                {{ $event->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.events.toggle-featured', $event) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="warning" type="submit">
                                                <i class="fas fa-star text-[10px]"></i>
                                                {{ $event->is_featured ? 'Biasa' : 'Highlight' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                                            data-confirm="Hapus event &quot;{{ $event->title }}&quot;?">
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
                                <td colspan="6">
                                    <x-ui.empty-state icon="fa-calendar-days" title="Belum ada event"
                                        text="Tambahkan event atau promo pertama untuk ditampilkan di situs." class="py-8" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-base-300/60 px-6 py-4">
                {{ $events->links('vendor.pagination.admin') }}
            </div>
        </x-ui.card>
    </div>
@endsection
