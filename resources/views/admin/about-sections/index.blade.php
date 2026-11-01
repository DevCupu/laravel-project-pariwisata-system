@extends('layouts.admin')
@php use Illuminate\Support\Str; @endphp

@section('title', 'Tentang Kami')
@section('page-title', 'Kelola Konten Tentang Kami')
@section('page-subtitle', 'Atur informasi Sejarah dan Lokasi yang muncul di halaman publik')

@section('content')
    <div class="space-y-5">
        <x-ui.card title="Daftar Konten" subtitle="Gunakan urutan tampil untuk mengatur posisi dropdown.">
            @slot('actions')
                <x-ui.button :href="route('admin.about-sections.create')">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Konten
                </x-ui.button>
            @endslot

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                        <tr>
                            <th class="text-left">Judul</th>
                            <th class="text-left">Slug</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Urutan</th>
                            <th class="border-b border-slate-200 px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sections as $section)
                            <tr>
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-slate-900">{{ $section->title }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ Str::limit($section->content, 80) }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 font-mono text-xs font-semibold text-slate-700">
                                        #{{ $section->slug }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <x-ui.badge :tone="$section->is_active ? 'success' : 'neutral'" dot>
                                        {{ $section->is_active ? 'Aktif' : 'Disembunyikan' }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-5 py-3.5 font-semibold">{{ $section->display_order }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex flex-wrap items-center justify-end gap-2 text-xs">
                                        <x-ui.button size="sm" variant="soft-blue" :href="route('admin.about-sections.edit', $section)">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            Edit
                                        </x-ui.button>
                                        <form method="POST" action="{{ route('admin.about-sections.destroy', $section) }}"
                                            data-confirm="Hapus konten &quot;{{ $section->title }}&quot;?">
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
                                <td colspan="5">
                                    <x-ui.empty-state icon="fa-book-open" title="Belum ada konten Tentang Kami"
                                        text="Tambahkan blok konten sejarah atau lokasi." class="py-8" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-base-300/60 px-6 py-4">
                {{ $sections->links('vendor.pagination.admin') }}
            </div>
        </x-ui.card>
    </div>
@endsection
