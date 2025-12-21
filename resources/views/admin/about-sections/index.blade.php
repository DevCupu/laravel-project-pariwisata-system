@extends('layouts.admin')
@php use Illuminate\Support\Str; @endphp

@section('title', 'Tentang Kami')
@section('page-title', 'Kelola Konten Tentang Kami')
@section('page-subtitle', 'Atur informasi Sejarah dan Lokasi yang muncul di halaman publik')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <p class="text-sm text-gray-500">Gunakan urutan tampil untuk mengatur posisi dropdown.</p>
            <a href="{{ route('admin.about-sections.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-800">
                <i class="fas fa-plus text-xs"></i>
                Tambah Konten
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-6 py-3">Judul</th>
                            <th class="px-6 py-3">Slug</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Urutan</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse ($sections as $section)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-900">{{ $section->title }}</p>
                                    <p class="text-xs text-gray-500 line-clamp-1">{{ Str::limit($section->content, 80) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                                        #{{ $section->slug }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $section->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-600' }}">
                                        <span class="h-2 w-2 rounded-full {{ $section->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                        {{ $section->is_active ? 'Aktif' : 'Disembunyikan' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold">{{ $section->display_order }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.about-sections.edit', $section) }}"
                                            class="inline-flex items-center gap-1 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                            <i class="fas fa-pen"></i>
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.about-sections.destroy', $section) }}" method="POST"
                                            onsubmit="return confirm('Hapus konten ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">
                                                <i class="fas fa-trash"></i>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-sm text-gray-500">
                                    Belum ada konten Tentang Kami.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                 {{ $sections->links('vendor.pagination.admin') }}
            </div>
        </div>
    </div>
@endsection
