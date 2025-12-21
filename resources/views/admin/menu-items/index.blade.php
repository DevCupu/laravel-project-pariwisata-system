@extends('layouts.admin')

@section('title', 'Menu Kafe')
@section('page-title', 'Kelola Menu Kafe')
@section('page-subtitle', 'Atur hidangan dan minuman yang tampil di halaman menu')

@section('content')
    <div class="space-y-5">
        @if (session('admin_success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('admin_success') }}
            </div>
        @endif

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Menu</h3>
                    <p class="text-sm text-gray-500">Kelompokkan berdasarkan section untuk tampil rapi di halaman publik.</p>
                </div>
                <div class="flex w-full flex-col gap-3 lg:w-auto lg:flex-row lg:items-center">
                    <form method="GET" class="w-full lg:w-64">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari menu"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pl-10 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </form>
                    <form method="GET" class="flex items-center gap-2 text-sm">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <select name="section" onchange="this.form.submit()"
                            class="rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Section</option>
                            @foreach ($sectionOptions as $section)
                                <option value="{{ $section }}" @selected(request('section') === $section)>
                                    {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $section)) }}
                                </option>
                            @endforeach
                        </select>
                        <select name="type" onchange="this.form.submit()"
                            class="rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Tipe</option>
                            @foreach ($typeOptions as $type)
                                <option value="{{ $type }}" @selected(request('type') === $type)>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <a href="{{ route('admin.menu-items.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-700">
                        <i class="fas fa-plus text-xs"></i>
                        Tambah Menu
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[900px] w-full border-collapse text-sm text-gray-800">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Menu</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Section</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Harga</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Badge</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Status</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="px-5 py-3 align-top">
                                    <p class="font-semibold text-gray-900">{{ $item->name }}</p>
                                    <p class="text-xs text-gray-500">Slug: {{ $item->slug }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->short_description }}</p>
                                </td>
                                <td class="px-5 py-3 align-top">{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $item->section)) }}</td>
                                <td class="px-5 py-3 align-top font-semibold text-gray-900">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 align-top">
                                    @if ($item->badge)
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-0.5 text-xs font-semibold text-amber-700">{{ $item->badge }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 align-top">
                                    <span class="inline-flex items-center rounded-full px-3 py-0.5 text-xs font-semibold {{ $item->is_available ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $item->is_available ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 align-top">
                                    <div class="flex flex-wrap gap-2 text-xs">
                                        <a href="{{ route('admin.menu-items.edit', $item) }}"
                                            class="inline-flex items-center gap-1 rounded-lg border border-green-200 bg-green-50 px-3 py-1.5 font-semibold text-green-700 hover:bg-green-100">
                                            <i class="fas fa-pen text-xs"></i>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.menu-items.toggle', $item) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-1.5 font-semibold text-gray-700 hover:bg-gray-50">
                                                <i class="fas fa-toggle-on text-sm"></i>
                                                {{ $item->is_available ? 'Sembunyikan' : 'Tersedia' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.menu-items.destroy', $item) }}"
                                            onsubmit="return confirm('Hapus menu ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 font-semibold text-red-700 hover:bg-red-100">
                                                <i class="fas fa-trash text-xs"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada menu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-6 py-4">
                {{ $items->links('vendor.pagination.admin') }}
            </div>
        </div>
    </div>
@endsection
