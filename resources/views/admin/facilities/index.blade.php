@extends('layouts.admin')

@section('title', 'Fasilitas')
@section('page-title', 'Manajemen Fasilitas')
@section('page-subtitle', 'Atur kategori, kapasitas, dan ketersediaan fasilitas untuk booking')

@section('content')
    <div class="space-y-5">
        @if (session('admin_success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('admin_success') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4">
                <p class="text-xs uppercase tracking-wide text-gray-500">Total Fasilitas</p>
                <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($stats['total']) }}</p>
            </div>
            <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                <p class="text-xs uppercase tracking-wide text-green-600">Aktif</p>
                <p class="mt-2 text-2xl font-semibold text-green-700">{{ number_format($stats['active']) }}</p>
            </div>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                <p class="text-xs uppercase tracking-wide text-amber-600">Nonaktif</p>
                <p class="mt-2 text-2xl font-semibold text-amber-700">{{ number_format($stats['inactive']) }}</p>
            </div>
            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                <p class="text-xs uppercase tracking-wide text-blue-600">Kategori</p>
                <p class="mt-2 text-2xl font-semibold text-blue-700">{{ count($categoryOptions) }}</p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-gray-100 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Fasilitas</h3>
                    <p class="text-sm text-gray-500">Kelola fasilitas yang tersedia untuk proses booking.</p>
                </div>
                <div class="flex w-full flex-col gap-3 lg:w-auto lg:flex-row lg:items-center">
                    <form method="GET" class="w-full lg:w-64">
                        <div class="relative">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama fasilitas"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pl-10 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </form>
                    <form method="GET" class="flex items-center gap-2 text-sm">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <select name="category" onchange="this.form.submit()"
                            class="rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($categoryOptions as $category)
                                <option value="{{ $category }}" @selected($categoryFilter === $category)>
                                    {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $category)) }}
                                </option>
                            @endforeach
                        </select>
                        <select name="status" onchange="this.form.submit()"
                            class="rounded-xl border border-gray-200 px-4 py-2.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <option value="active" @selected($statusFilter === 'active')>Aktif</option>
                            <option value="inactive" @selected($statusFilter === 'inactive')>Nonaktif</option>
                        </select>
                    </form>
                    <a href="{{ route('admin.facilities.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                        <i class="fas fa-plus text-xs"></i>
                        Tambah Fasilitas
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[900px] w-full border-collapse text-sm text-gray-800">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Fasilitas</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Kategori</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Kapasitas</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Urutan</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Status</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Deskripsi</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($facilities as $facility)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="px-5 py-3 align-top">
                                    <p class="font-semibold text-gray-900">{{ $facility->name }}</p>
                                    <p class="text-xs text-gray-500">Slug: {{ $facility->slug }}</p>
                                </td>
                                <td class="px-5 py-3 align-top text-sm text-gray-700">
                                    {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $facility->category)) }}
                                </td>
                                <td class="px-5 py-3 align-top font-semibold text-gray-900">
                                    {{ $facility->capacity_per_day ?: '–' }} / hari
                                </td>
                                <td class="px-5 py-3 align-top text-gray-700">
                                    {{ $facility->sort_order }}
                                </td>
                                <td class="px-5 py-3 align-top">
                                    @if ($facility->is_active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-0.5 text-xs font-semibold text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-0.5 text-xs font-semibold text-gray-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 align-top text-sm text-gray-600">
                                    {{ $facility->description ? \Illuminate\Support\Str::limit($facility->description, 80) : '–' }}
                                </td>
                                <td class="px-5 py-3 align-top">
                                    <div class="flex flex-wrap gap-2 text-xs">
                                    <a href="{{ route('admin.facilities.edit', $facility) }}"
                                        class="inline-flex items-center gap-1 rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 font-semibold text-blue-700 hover:bg-blue-100">
                                        <i class="fas fa-pen text-xs"></i>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.facilities.toggle', $facility) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="q" value="{{ request('q') }}">
                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                        <button type="submit"
                                            class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-1.5 font-semibold text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-toggle-on text-sm"></i>
                                            {{ $facility->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}"
                                        onsubmit="return confirm('Hapus fasilitas ini?');">
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
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">Belum ada data fasilitas.</td>
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
