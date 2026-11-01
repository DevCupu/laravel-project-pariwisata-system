@extends('layouts.admin')

@section('title', 'Testimoni')
@section('page-title', 'Kelola Testimoni')
@section('page-subtitle', 'Atur ulasan pengunjung yang tampil di halaman beranda')

@section('content')
    <div class="space-y-5">
        <div class="grid gap-4 sm:grid-cols-3">
            <x-ui.stat-card label="Total Testimoni" :value="number_format($stats['total'])" icon="fa-comment-dots" tone="blue" meta="Semua ulasan" />
            <x-ui.stat-card label="Aktif" :value="number_format($stats['active'])" icon="fa-circle-check" tone="green" meta="Tampil di publik" />
            <x-ui.stat-card label="Highlight" :value="number_format($stats['featured'])" icon="fa-star" tone="amber" meta="Ditandai unggulan" />
        </div>

        <x-ui.card title="Daftar Testimoni" subtitle="Gunakan urutan untuk mengatur prioritas tampilan.">
            @slot('actions')
                <form method="GET" class="w-full lg:w-56">
                    <div class="relative">
                        <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Cari testimoni"
                            class="input input-bordered input-sm w-full pl-9">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-base-content/40"></i>
                    </div>
                </form>
                <form method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="q" value="{{ $searchQuery }}">
                    <select name="status" onchange="this.form.submit()" class="select select-bordered select-sm">
                        <option value="">Semua Status</option>
                        <option value="active" @selected($statusFilter === 'active')>Aktif</option>
                        <option value="inactive" @selected($statusFilter === 'inactive')>Nonaktif</option>
                    </select>
                </form>
                <x-ui.button :href="route('admin.testimonials.create')">
                    <i class="fas fa-plus text-xs"></i>
                    Tambah Testimoni
                </x-ui.button>
            @endslot

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full text-sm">
                    <thead class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                        <tr>
                            <th class="text-left">Pengunjung</th>
                            <th class="text-left">Kutipan</th>
                            <th class="text-left">Rating</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testimonials as $testimonial)
                            <tr>
                                <td class="align-top">
                                    <div class="flex items-center gap-3">
                                        @if ($testimonial->avatar_path)
                                            <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}"
                                                class="h-10 w-10 flex-shrink-0 rounded-full object-cover">
                                        @else
                                            <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-bold text-primary">
                                                {{ $testimonial->initials }}
                                            </span>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $testimonial->name }}</p>
                                            <p class="text-xs text-slate-500">
                                                {{ collect([$testimonial->role, $testimonial->location])->filter()->implode(' • ') ?: '—' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-top text-sm text-base-content/70">
                                    <p class="max-w-xs">{{ \Illuminate\Support\Str::limit($testimonial->quote, 90) }}</p>
                                    <p class="mt-0.5 text-[11px] text-slate-400">Urutan: {{ $testimonial->sort_order }}</p>
                                </td>
                                <td class="align-top">
                                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-amber-500">
                                        <i class="fas fa-star text-xs"></i>{{ $testimonial->rating }}/5
                                    </span>
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-col gap-1.5">
                                        <x-ui.badge :tone="$testimonial->is_active ? 'success' : 'neutral'" dot>
                                            {{ $testimonial->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </x-ui.badge>
                                        <x-ui.badge :tone="$testimonial->is_featured ? 'warning' : 'neutral'">
                                            {{ $testimonial->is_featured ? 'Highlight' : 'Biasa' }}
                                        </x-ui.badge>
                                    </div>
                                </td>
                                <td class="align-top">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <x-ui.button size="sm" variant="soft-blue" :href="route('admin.testimonials.edit', $testimonial)">
                                            <i class="fas fa-pen text-[10px]"></i>
                                            Edit
                                        </x-ui.button>
                                        <form method="POST" action="{{ route('admin.testimonials.toggle-status', $testimonial) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="secondary" type="submit">
                                                <i class="fas fa-toggle-on text-[10px]"></i>
                                                {{ $testimonial->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.testimonials.toggle-featured', $testimonial) }}">
                                            @csrf
                                            @method('PATCH')
                                            <x-ui.button size="sm" variant="warning" type="submit">
                                                <i class="fas fa-star text-[10px]"></i>
                                                {{ $testimonial->is_featured ? 'Hapus Highlight' : 'Highlight' }}
                                            </x-ui.button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}">
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
                                    <x-ui.empty-state title="Belum ada testimoni" icon="fa-comment-dots"
                                        text="Tambahkan ulasan pengunjung untuk ditampilkan di beranda." />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-base-300/60 px-6 py-4">
                {{ $testimonials->links('vendor.pagination.admin') }}
            </div>
        </x-ui.card>
    </div>
@endsection
