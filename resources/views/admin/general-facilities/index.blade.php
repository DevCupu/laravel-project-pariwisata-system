@extends('layouts.admin')

@section('title', 'Fasilitas Umum')
@section('page-title', 'Kelola Fasilitas Umum')
@section('page-subtitle', 'Informasi fasilitas umum yang tampil di halaman profil destinasi')

@section('content')
    <div class="space-y-5">
        @if (session('admin_success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('admin_success') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-gray-100 bg-white p-4">
                <p class="text-xs font-semibold uppercase text-gray-500">Total Fasilitas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4">
                <p class="text-xs font-semibold uppercase text-gray-500">Aktif</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Fasilitas</h3>
                    <p class="text-sm text-gray-500">Gunakan ikon Font Awesome agar konsisten.</p>
                </div>
                <div class="flex w-full flex-col gap-3 lg:w-auto lg:flex-row lg:items-center">
                    <form method="GET" class="w-full lg:w-64">
                        <div class="relative">
                            <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Cari fasilitas"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pl-10 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </form>
                    <form method="GET" class="flex items-center gap-2 text-sm">
                        <input type="hidden" name="q" value="{{ $searchQuery }}">
                        <select name="status" onchange="this.form.submit()"
                            class="rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Status</option>
                            <option value="active" @selected($statusFilter === 'active')>Aktif</option>
                            <option value="inactive" @selected($statusFilter === 'inactive')>Nonaktif</option>
                        </select>
                    </form>
                    <a href="{{ route('admin.general-facilities.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-700">
                        <i class="fas fa-plus text-xs"></i>
                        Tambah Fasilitas
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[800px] w-full border-collapse text-sm text-gray-800">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Fasilitas</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Informasi</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Status</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facilities as $facility)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="px-5 py-4 align-top">
                                    <p class="font-semibold text-gray-900">{{ $facility->name }}</p>
                                    <p class="text-xs text-gray-500">Slug: {{ $facility->slug }}</p>
                                    <p class="text-xs text-gray-500">Ikon: {{ $facility->icon ?: '—' }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <p class="text-sm text-gray-600">{{ $facility->description ?: 'Belum ada deskripsi.' }}</p>
                                    @if ($facility->availability_note)
                                        <p class="text-xs text-amber-600">Catatan: {{ $facility->availability_note }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500">Urutan: {{ $facility->sort_order }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <span class="inline-flex items-center rounded-full px-3 py-0.5 text-xs font-semibold {{ $facility->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $facility->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="flex flex-wrap gap-2 text-xs">
                                        <a href="{{ route('admin.general-facilities.edit', $facility) }}"
                                            class="inline-flex items-center gap-1 rounded-lg border border-green-200 bg-green-50 px-3 py-1.5 font-semibold text-green-700 hover:bg-green-100">
                                            <i class="fas fa-pen text-xs"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.general-facilities.toggle-status', $facility) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-1.5 font-semibold text-gray-700 hover:bg-gray-50">
                                                {{ $facility->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.general-facilities.destroy', $facility) }}"
                                            onsubmit="return confirm('Hapus fasilitas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 font-semibold text-red-700 hover:bg-red-100">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada fasilitas umum.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-100 px-6 py-4">
                {{ $facilities->links('vendor.pagination.admin') }}
            </div>
        </div>
    </div>
@endsection
