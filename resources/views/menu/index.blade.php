@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Menu Kafe')
@section('meta_description', 'Signature coffee, comfort food, dan camilan hangat di Cafe Dante Pine Enrekang. Lihat menu lengkap beserta harga.')

@section('content')
    @include('partials.public.page-hero', [
        'title' => 'Signature coffee, comfort food, dan suasana pinus',
        'subtitle' => 'Nikmati hidangan hangat untuk menemani udara sejuk pegunungan. Pesan langsung di kafe atau via WhatsApp.',
        'eyebrow' => 'Cafe Dante Pine',
        'breadcrumb' => [['label' => 'Menu Kafe']],
    ])

    <section class="pn-section pn-section--tint">
        <div class="pn-container">
            @php
                $q = request('q');
                $selectedSection = request('section');
                $availability = request('availability');
                $sort = request('sort');
                $allSections = $menuSections->keys();
            @endphp
            <form method="GET" class="pn-card mb-10 grid gap-3 p-5 lg:grid-cols-12">
                <div class="relative lg:col-span-4">
                    <input type="text" name="q" value="{{ old('q', $q) }}" placeholder="Cari menu atau deskripsi"
                        class="pn-field pl-10" aria-label="Cari menu">
                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400" aria-hidden="true"></i>
                </div>

                <select name="section" class="pn-field lg:col-span-4" aria-label="Filter section">
                    <option value="">Semua Section</option>
                    @foreach ($allSections as $sec)
                        <option value="{{ $sec }}" @selected($selectedSection === $sec)>{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $sec)) }}</option>
                    @endforeach
                </select>

                <select name="availability" class="pn-field lg:col-span-2" aria-label="Filter ketersediaan">
                    <option value="all" @selected($availability === null || $availability === 'all')>Semua</option>
                    <option value="available" @selected($availability === 'available')>Tersedia</option>
                    <option value="unavailable" @selected($availability === 'unavailable')>Habis</option>
                </select>

                <select name="sort" class="pn-field lg:col-span-2" aria-label="Urutkan">
                    <option value="">Urutkan</option>
                    <option value="price_asc" @selected($sort === 'price_asc')>Harga: Terendah</option>
                    <option value="price_desc" @selected($sort === 'price_desc')>Harga: Tertinggi</option>
                </select>

                <div class="flex items-center justify-between gap-3 lg:col-span-12 lg:justify-end">
                    <p class="text-sm text-slate-600">Menampilkan <strong>{{ $menuSections->sum(fn ($c) => $c->count()) }}</strong> item</p>
                    <button type="submit" class="pn-btn pn-btn-primary !py-2.5">
                        <i class="fas fa-magnifying-glass" aria-hidden="true"></i> Cari
                    </button>
                </div>
            </form>

            @forelse ($menuSections as $section => $items)
                @php
                    if ($selectedSection && $selectedSection !== $section) {
                        continue;
                    }

                    $filtered = $items->filter(function ($item) use ($q, $availability) {
                        $matchQ = true;
                        if ($q) {
                            $needle = mb_strtolower($q);
                            $hay = mb_strtolower($item->name . ' ' . ($item->short_description ?? $item->description ?? ''));
                            $matchQ = str_contains($hay, $needle);
                        }

                        $matchAvailability = true;
                        if ($availability === 'available') {
                            $matchAvailability = (bool) $item->is_available;
                        } elseif ($availability === 'unavailable') {
                            $matchAvailability = ! (bool) $item->is_available;
                        }

                        return $matchQ && $matchAvailability;
                    });

                    if ($sort === 'price_asc') {
                        $filtered = $filtered->sortBy('price');
                    } elseif ($sort === 'price_desc') {
                        $filtered = $filtered->sortByDesc('price');
                    }
                @endphp
                <div class="pn-reveal mb-12">
                    <div class="mb-6 flex flex-wrap items-end justify-between gap-3 border-b border-slate-200 pb-4">
                        <div>
                            <span class="pn-eyebrow text-emerald-600">Section</span>
                            <h2 class="pn-heading mt-3 text-2xl sm:text-3xl">{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $section)) }}</h2>
                        </div>
                        <span class="text-sm font-semibold text-slate-500">{{ $items->count() }} item</span>
                    </div>

                    @if ($filtered->isEmpty())
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-center text-slate-500">Tidak ada item sesuai filter.</div>
                    @else
                        <div class="grid gap-4 md:grid-cols-2">
                            @foreach ($filtered as $item)
                                <article class="pn-card pn-card-hover flex gap-4 p-4">
                                    <div class="h-28 w-28 flex-shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                        <img src="{{ $item->image_url ?? ($item->image_path ? Storage::url($item->image_path) : asset('assets-beranda/home.jpg')) }}" alt="{{ $item->name }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
                                    </div>
                                    <div class="flex flex-1 flex-col">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <h3 class="text-lg font-bold text-slate-900">{{ $item->name }}</h3>
                                                <p class="mt-1 text-sm text-slate-600">{{ $item->short_description ?? \Illuminate\Support\Str::limit($item->description, 120) }}</p>
                                                <p class="mt-2 text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-400">{{ strtoupper($item->type) }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xl font-bold text-emerald-700">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                                <p class="text-xs {{ $item->is_available ? 'text-emerald-600' : 'text-rose-500' }}">{{ $item->is_available ? 'Tersedia' : 'Habis' }}</p>
                                            </div>
                                        </div>

                                        <div class="mt-auto flex flex-wrap items-center gap-2 pt-3">
                                            @if ($item->badge)
                                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $item->badge }}</span>
                                            @endif
                                            <a href="{{ route('menu.index') }}?section={{ $section }}" class="text-xs font-medium text-slate-500 hover:text-emerald-700">Lihat section ini</a>
                                            <a href="https://wa.me/6282145671234?text={{ urlencode('Halo, saya ingin pesan: ' . $item->name . ' (Rp ' . number_format($item->price, 0, ',', '.') . ')') }}" target="_blank" rel="noopener"
                                                class="ml-auto inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-3 py-1.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                                <i class="fab fa-whatsapp" aria-hidden="true"></i> Pesan
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500">
                    Menu belum tersedia.
                </div>
            @endforelse
        </div>
    </section>
@endsection
