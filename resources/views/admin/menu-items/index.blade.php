@extends('layouts.admin')

@section('title', 'Menu Kafe')
@section('page-title', 'Kelola Menu Kafe')
@section('page-subtitle', 'Atur hidangan dan minuman yang tampil di halaman menu')

@section('content')
    <div class="space-y-5">
        <x-ui.card title="Daftar Menu" subtitle="Kelompokkan berdasarkan section untuk tampil rapi di halaman publik.">
            @slot('actions')
                <form method="GET" class="w-full lg:w-56">
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari menu"
                            class="input input-bordered input-sm w-full pl-9">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-base-content/40"></i>
                    </div>
                </form>
                <form method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <select name="section" onchange="this.form.submit()"
                        class="select select-bordered select-sm">
                        <option value="">Semua Section</option>
                        @foreach ($sectionOptions as $section)
                            <option value="{{ $section }}" @selected(request('section') === $section)>
                                {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $section)) }}
                            </option>
                        @endforeach
                    </select>
                    <select name="type" onchange="this.form.submit()"
                        class="select select-bordered select-sm">
                        <option value="">Semua Tipe</option>
                        @foreach ($typeOptions as $type)
                            <option value="{{ $type }}" @selected(request('type') === $type)>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <x-ui.button :href="route('admin.menu-items.create')">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Menu
                </x-ui.button>
            @endslot

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                        <tr>
                            <th class="text-left">Menu</th>
                            <th class="text-left">Section</th>
                            <th class="text-left">Harga</th>
                            <th class="text-left">Badge</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td class="align-top">
                                    <p class="font-semibold text-slate-900">{{ $item->name }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-400">slug: {{ $item->slug }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->short_description }}</p>
                                </td>
                                <td class="align-top">
                                    {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $item->section)) }}
                                </td>
                                <td class="align-top font-semibold">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="align-top">
                                    @if ($item->badge)
                                        <x-ui.badge tone="warning">{{ $item->badge }}</x-ui.badge>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="align-top">
                                    <x-ui.badge :tone="$item->is_available ? 'success' : 'neutral'" dot>
                                        {{ $item->is_available ? 'Tersedia' : 'Habis' }}
                                    </x-ui.badge>
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <x-ui.button size="sm" variant="soft-blue" :href="route('admin.menu-items.edit', $item)">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            Edit
                                        </x-ui.button>
                                        <form method="POST" action="{{ route('admin.menu-items.toggle', $item) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="secondary" type="submit">
                                                <i class="fas fa-toggle-on text-[10px]"></i>
                                                {{ $item->is_available ? 'Sembunyikan' : 'Tersedia' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.menu-items.destroy', $item) }}"
                                            data-confirm="Hapus menu &quot;{{ $item->name }}&quot;?">
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
                                    <x-ui.empty-state icon="fa-mug-hot" title="Belum ada menu"
                                        text="Tambahkan menu kafe pertama untuk ditampilkan di halaman publik." class="py-8" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-base-300/60 px-6 py-4">
                {{ $items->links('vendor.pagination.admin') }}
            </div>
        </x-ui.card>
    </div>
@endsection
