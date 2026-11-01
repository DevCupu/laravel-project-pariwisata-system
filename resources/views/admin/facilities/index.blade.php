@extends('layouts.admin')

@section('title', 'Fasilitas')
@section('page-title', 'Manajemen Fasilitas')
@section('page-subtitle', 'Atur fasilitas dan pantau kapasitas booking per tanggal')

@section('content')
    <div class="space-y-5">
        <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            <x-ui.stat-card label="Total Fasilitas" :value="number_format($stats['total'])" icon="fa-building" tone="blue" meta="Semua fasilitas" />
            <x-ui.stat-card label="Aktif" :value="number_format($stats['active'])" icon="fa-circle-check" tone="green" meta="Tampil untuk booking" />
            <x-ui.stat-card label="Nonaktif" :value="number_format($stats['inactive'])" icon="fa-circle-xmark" tone="red" meta="Disembunyikan" />
            <x-ui.stat-card label="Kategori" :value="count($categoryOptions)" icon="fa-layer-group" tone="violet" meta="Kategori berbeda" />
        </div>

        <x-ui.card title="Daftar Fasilitas" :subtitle="'Pemakaian kapasitas untuk tanggal ' . \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('d M Y') . '. Booking menunggu dan dikonfirmasi dihitung.'">
            <div class="border-b border-base-300/60 px-4 py-4 sm:px-6">
                <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
                    <form method="GET" action="{{ route('admin.facilities.index') }}"
                        class="grid flex-1 grid-cols-1 gap-2 sm:grid-cols-2 xl:flex xl:items-center">
                        <div class="relative sm:col-span-2 xl:col-span-1">
                            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari fasilitas"
                                aria-label="Cari fasilitas berdasarkan nama atau deskripsi"
                                class="input input-bordered input-sm w-full pl-9 xl:w-52">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-base-content/40"></i>
                        </div>
                        <select name="category" aria-label="Filter kategori" class="select select-bordered select-sm w-full xl:w-40">
                        <option value="">Semua Kategori</option>
                        @foreach ($categoryOptions as $category)
                            <option value="{{ $category }}" @selected($categoryFilter === $category)>
                                {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $category)) }}
                            </option>
                        @endforeach
                        </select>
                        <select name="status" aria-label="Filter status" class="select select-bordered select-sm w-full xl:w-32">
                        <option value="">Semua Status</option>
                        <option value="active" @selected($statusFilter === 'active')>Aktif</option>
                        <option value="inactive" @selected($statusFilter === 'inactive')>Nonaktif</option>
                        </select>
                        <input type="date" name="date" value="{{ $selectedDate }}" aria-label="Tanggal pemantauan kapasitas"
                            class="input input-bordered input-sm w-full xl:w-40">
                        <div class="flex items-center gap-2 sm:col-span-2 xl:col-span-1">
                            <x-ui.button size="sm" variant="secondary" type="submit">Terapkan</x-ui.button>
                            @if (filled(request('q')) || filled(request('category')) || filled(request('status')) || filled(request('date')))
                                <x-ui.button size="sm" variant="ghost" :href="route('admin.facilities.index')">Reset</x-ui.button>
                            @endif
                        </div>
                    </form>
                    <x-ui.button :href="route('admin.facilities.create')" class="w-full justify-center sm:w-auto">
                        <i class="fas fa-plus text-xs"></i>
                        Tambah Fasilitas
                    </x-ui.button>
                </div>
            </div>

            <div class="hidden overflow-x-auto xl:block">
                <table class="table table-zebra w-full text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                        <tr>
                            <th class="text-left">Fasilitas</th>
                            <th class="text-left">Kategori</th>
                            <th class="text-left">Kapasitas tamu</th>
                            <th class="text-left">Urutan tampil</th>
                            <th class="text-left">Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facilities as $facility)
                            @php
                                $capacity = (int) $facility->capacity_per_day;
                                $bookedGuests = (int) ($facility->booked_guests ?? 0);
                                $remainingCapacity = $capacity > 0 ? max($capacity - $bookedGuests, 0) : null;
                            @endphp
                            <tr>
                                <td class="align-top xl:min-w-72">
                                    <p class="font-semibold text-base-content">{{ $facility->name }}</p>
                                    <p class="mt-1 max-w-md text-xs leading-relaxed text-base-content/60">
                                        {{ $facility->description ? \Illuminate\Support\Str::limit($facility->description, 100) : 'Belum ada deskripsi.' }}
                                    </p>
                                    <p class="mt-1 font-mono text-[10px] text-base-content/40">Slug: {{ $facility->slug }}</p>
                                </td>
                                <td class="align-top text-sm text-base-content/70">
                                    {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $facility->category)) }}
                                </td>
                                <td class="align-top">
                                    @if ($capacity > 0)
                                        <p class="font-semibold">Terpakai {{ number_format($bookedGuests) }} / {{ number_format($capacity) }} tamu</p>
                                        <x-ui.badge :tone="$remainingCapacity === 0 ? 'error' : 'success'" class="mt-1">
                                            Sisa {{ number_format($remainingCapacity) }} tamu
                                        </x-ui.badge>
                                    @else
                                        <p class="font-semibold">Tidak dibatasi</p>
                                        <p class="mt-1 text-xs text-base-content/60">Terpakai {{ number_format($bookedGuests) }} tamu</p>
                                    @endif
                                    <span class="mt-1 block text-xs text-base-content/50">
                                        {{ number_format((int) $facility->confirmed_booking_count) }} dikonfirmasi ·
                                        {{ number_format((int) $facility->pending_booking_count) }} menunggu
                                    </span>
                                </td>
                                <td class="align-top text-base-content/70">
                                    {{ $facility->sort_order }}
                                </td>
                                <td class="align-top">
                                    <x-ui.badge :tone="$facility->is_active ? 'success' : 'neutral'" dot>
                                        {{ $facility->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </x-ui.badge>
                                </td>
                                <td class="align-top">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-ui.button size="sm" variant="soft-blue" :href="route('admin.facilities.edit', $facility)">
                                            <i class="fas fa-pen text-[10px]"></i> Edit
                                        </x-ui.button>
                                        <form method="POST" action="{{ route('admin.facilities.toggle', $facility) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="q" value="{{ request('q') }}">
                                            <input type="hidden" name="category" value="{{ request('category') }}">
                                            <input type="hidden" name="status" value="{{ request('status') }}">
                                            <input type="hidden" name="date" value="{{ $selectedDate }}">
                                            <x-ui.button size="sm" variant="secondary" type="submit">
                                                {{ $facility->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}"
                                            data-confirm="Hapus fasilitas &quot;{{ $facility->name }}&quot;? Booking lama tetap tersimpan, tetapi tidak lagi terhubung ke fasilitas ini.">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="q" value="{{ request('q') }}">
                                            <input type="hidden" name="category" value="{{ request('category') }}">
                                            <input type="hidden" name="status" value="{{ request('status') }}">
                                            <input type="hidden" name="date" value="{{ $selectedDate }}">
                                            <button type="submit" class="btn btn-ghost btn-sm btn-square text-error"
                                                title="Hapus {{ $facility->name }}" aria-label="Hapus {{ $facility->name }}">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <x-ui.empty-state icon="fa-building" title="Belum ada data fasilitas"
                                        text="Tambahkan fasilitas pertama agar bisa dipilih saat booking." class="py-8" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-base-200 xl:hidden">
                @forelse ($facilities as $facility)
                    @php
                        $capacity = (int) $facility->capacity_per_day;
                        $bookedGuests = (int) ($facility->booked_guests ?? 0);
                        $remainingCapacity = $capacity > 0 ? max($capacity - $bookedGuests, 0) : null;
                    @endphp
                    <article class="space-y-3 p-4 sm:p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h4 class="font-semibold text-base-content">{{ $facility->name }}</h4>
                                <p class="mt-0.5 text-xs text-base-content/60">
                                    {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $facility->category)) }}
                                    <span class="mx-1 text-base-content/30">·</span>
                                    Urutan {{ $facility->sort_order }}
                                </p>
                            </div>
                            <x-ui.badge :tone="$facility->is_active ? 'success' : 'neutral'" dot class="shrink-0">
                                {{ $facility->is_active ? 'Aktif' : 'Nonaktif' }}
                            </x-ui.badge>
                        </div>

                        <p class="text-sm leading-relaxed text-base-content/70">
                            {{ $facility->description ?: 'Belum ada deskripsi.' }}
                        </p>

                        <dl class="grid grid-cols-2 gap-3 rounded-xl bg-base-200/50 p-3 text-sm">
                            <div>
                                <dt class="text-xs text-base-content/50">Pemakaian pada {{ \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('d M') }}</dt>
                                <dd class="mt-0.5 font-semibold">
                                    @if ($capacity > 0)
                                        Sisa {{ number_format($remainingCapacity) }} dari {{ number_format($capacity) }} tamu
                                    @else
                                        Tidak dibatasi
                                    @endif
                                </dd>
                                <p class="mt-0.5 text-xs text-base-content/60">
                                    {{ number_format((int) $facility->confirmed_booking_count) }} dikonfirmasi ·
                                    {{ number_format((int) $facility->pending_booking_count) }} menunggu
                                </p>
                            </div>
                            <div>
                                <dt class="text-xs text-base-content/50">Slug</dt>
                                <dd class="mt-0.5 break-all font-mono text-xs">{{ $facility->slug }}</dd>
                            </div>
                        </dl>

                        <div class="flex flex-wrap items-center gap-2 border-t border-base-200 pt-3">
                            <x-ui.button size="sm" variant="soft-blue" :href="route('admin.facilities.edit', $facility)">
                                <i class="fas fa-pen text-[10px]"></i> Edit
                            </x-ui.button>
                            <form method="POST" action="{{ route('admin.facilities.toggle', $facility) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="q" value="{{ request('q') }}">
                                <input type="hidden" name="category" value="{{ request('category') }}">
                                <input type="hidden" name="status" value="{{ request('status') }}">
                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                                <x-ui.button size="sm" variant="secondary" type="submit">
                                    {{ $facility->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </x-ui.button>
                            </form>
                            <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}"
                                data-confirm="Hapus fasilitas &quot;{{ $facility->name }}&quot;? Booking lama tetap tersimpan, tetapi tidak lagi terhubung ke fasilitas ini.">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="q" value="{{ request('q') }}">
                                <input type="hidden" name="category" value="{{ request('category') }}">
                                <input type="hidden" name="status" value="{{ request('status') }}">
                                <input type="hidden" name="date" value="{{ $selectedDate }}">
                                <x-ui.button size="sm" variant="danger" type="submit">
                                    <i class="fas fa-trash text-[10px]"></i> Hapus
                                </x-ui.button>
                            </form>
                        </div>
                    </article>
                @empty
                    <x-ui.empty-state icon="fa-building" title="Belum ada data fasilitas"
                        text="Tambahkan fasilitas pertama agar bisa dipilih saat booking." class="py-8" />
                @endforelse
            </div>

            <div class="border-t border-base-300/60 px-4 py-4 sm:px-6">
                {{ $facilities->links('vendor.pagination.admin') }}
            </div>
        </x-ui.card>
    </div>
@endsection
