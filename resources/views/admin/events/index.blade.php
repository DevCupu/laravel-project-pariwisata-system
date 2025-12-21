@extends('layouts.admin')

@section('title', 'Events & Promo')
@section('page-title', 'Kelola Event')
@section('page-subtitle', 'Atur event dan promo yang tampil di halaman publik')

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
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Event</h3>
                    <p class="text-sm text-gray-500">Kelola event, promo, dan kegiatan khusus.</p>
                </div>
                <div class="flex w-full flex-col gap-3 lg:w-auto lg:flex-row lg:items-center">
                    <form method="GET" class="w-full lg:w-64">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari event"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pl-10 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </form>
                    <form method="GET" class="flex items-center gap-2 text-sm">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <select name="status" onchange="this.form.submit()"
                            class="rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="active" @selected($statusFilter === 'active')>Aktif</option>
                            <option value="inactive" @selected($statusFilter === 'inactive')>Nonaktif</option>
                        </select>
                    </form>
                    <a href="{{ route('admin.events.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                        <i class="fas fa-plus text-xs"></i>
                        Tambah Event
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[900px] w-full border-collapse text-sm text-gray-800">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Event</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Tipe</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Tanggal</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Status</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Highlight</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="px-5 py-3 align-top">
                                    <p class="font-semibold text-gray-900">{{ $event->title }}</p>
                                    <p class="text-xs text-gray-500">Slug: {{ $event->slug }}</p>
                                    <p class="text-xs text-gray-500">{{ $event->excerpt }}</p>
                                </td>
                                <td class="px-5 py-3 align-top">
                                    <span class="inline-flex items-center rounded-full px-3 py-0.5 text-xs font-semibold {{ $event->type === 'promo' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">{{ ucfirst($event->type) }}</span>
                                </td>
                                <td class="px-5 py-3 align-top">{{ $event->start_date ? $event->start_date->format('d M Y') : '–' }} @if($event->end_date) - {{ $event->end_date->format('d M Y') }} @endif</td>
                                <td class="px-5 py-3 align-top">
                                    <span class="inline-flex items-center rounded-full px-3 py-0.5 text-xs font-semibold {{ $event->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $event->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 align-top">
                                    <span class="inline-flex items-center rounded-full px-3 py-0.5 text-xs font-semibold {{ $event->is_featured ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $event->is_featured ? 'Ditampilkan' : 'Biasa' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 align-top">
                                    <div class="flex flex-wrap gap-2 text-xs">
                                        <a href="{{ route('admin.events.edit', $event) }}"
                                            class="inline-flex items-center gap-1 rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 font-semibold text-blue-700 hover:bg-blue-100">
                                            <i class="fas fa-pen text-xs"></i>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.events.toggle-status', $event) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-1.5 font-semibold text-gray-700 hover:bg-gray-50">
                                                <i class="fas fa-toggle-on text-sm"></i>
                                                {{ $event->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.events.toggle-featured', $event) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-amber-200 px-3 py-1.5 font-semibold text-amber-700 hover:bg-amber-50">
                                                <i class="fas fa-star text-sm"></i>
                                                {{ $event->is_featured ? 'Biasa' : 'Highlight' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Hapus event ini?');">
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
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada event.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-6 py-4">
                {{ $events->links('vendor.pagination.admin') }}
            </div>
        </div>
    </div>
@endsection
