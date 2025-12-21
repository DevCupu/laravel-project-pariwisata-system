@extends('layouts.public')

@section('title', 'Menu Kafe')

@section('content')
    <section class="relative overflow-hidden bg-gradient-to-br from-amber-900 via-slate-900 to-black text-white">
        <div
            class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=1600&q=60')] bg-cover bg-center opacity-30">
        </div>
        <div class="relative mx-auto max-w-5xl px-4 py-24 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.4em] text-amber-200">Cafe Dante Pine</p>
            <h1 class="mt-4 text-4xl font-bold md:text-5xl">Signature coffee, comfort food, dan api unggun hangat</h1>
            <p class="mt-4 text-lg text-amber-100">Menu dibagi per section agar mudah dipilih sesuai mood camping kamu.</p>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-4 py-16">
        @php
            $q = request('q');
            $selectedSection = request('section');
            $availability = request('availability'); // 'all' | 'available' | 'unavailable'
            $sort = request('sort'); // 'price_asc' | 'price_desc'
            $allSections = $menuSections->keys();
        @endphp

        <div class="mb-8 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <form method="GET" class="flex w-full gap-3 md:w-2/3" aria-label="Filter menu">
                <input type="text" name="q" value="{{ old('q', $q) }}" placeholder="Cari menu atau deskripsi"
                    class="w-full rounded-xl border border-gray-200 px-4 py-2.5 focus:border-green-500 focus:ring-2 focus:ring-green-500 text-sm"
                    aria-label="Cari menu">

                <select name="section" class="rounded-xl border border-gray-200 px-3 py-2 text-sm">
                    <option value="">Semua Section</option>
                    @foreach ($allSections as $sec)
                        <option value="{{ $sec }}" @selected($selectedSection === $sec)>{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $sec)) }}</option>
                    @endforeach
                </select>

                <select name="availability" class="rounded-xl border border-gray-200 px-3 py-2 text-sm">
                    <option value="all" @selected($availability === null || $availability === 'all')>Semua</option>
                    <option value="available" @selected($availability === 'available')>Tersedia</option>
                    <option value="unavailable" @selected($availability === 'unavailable')>Habis</option>
                </select>

                <select name="sort" class="rounded-xl border border-gray-200 px-3 py-2 text-sm">
                    <option value="">Urutkan</option>
                    <option value="price_asc" @selected($sort === 'price_asc')>Harga: Terendah</option>
                    <option value="price_desc" @selected($sort === 'price_desc')>Harga: Tertinggi</option>
                </select>

                <button type="submit" class="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white">Cari</button>
            </form>

            <div class="mt-2 md:mt-0 text-sm text-gray-600">Menampilkan <strong>{{ $menuSections->sum(fn($c) => $c->count()) }}</strong> item</div>
        </div>
        @forelse ($menuSections as $section => $items)
            @php
                if ($selectedSection && $selectedSection !== $section) {
                    continue;
                }

                // filter items locally for search / availability
                $filtered = $items->filter(function($item) use ($q, $availability) {
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
            <div class="mb-10 rounded-3xl border border-amber-100 bg-white/90 p-6 shadow">
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.4em] text-amber-500">Section</p>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-3xl font-semibold text-gray-900">
                            {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $section)) }}</h2>
                        <span class="text-sm text-gray-500">{{ $items->count() }} item</span>
                    </div>
                </div>
                @if($filtered->isEmpty())
                    <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-6 text-center text-gray-500">Tidak ada item sesuai filter.</div>
                @else
                    <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
                        @foreach ($filtered as $item)
                            <article class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm flex gap-4">
                                <div class="w-28 h-28 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
                                    <img src="{{ $item->image_url ?? ($item->image_path ? Storage::url($item->image_path) : asset('assets-beranda/home.jpg')) }}" alt="{{ $item->name }}" class="h-full w-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ $item->short_description ?? \Illuminate\Support\Str::limit($item->description, 120) }}</p>
                                            <div class="mt-2 text-xs uppercase tracking-[0.4em] text-gray-400">{{ strtoupper($item->type) }}</div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xl font-semibold text-amber-700">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500">{{ $item->is_available ? 'Tersedia' : 'Habis' }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @if($item->badge)
                                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ $item->badge }}</span>
                                        @endif
                                        <a href="{{ route('menu.index') }}?section={{ $section }}" class="text-xs text-gray-500">Lihat semua di section</a>
                                        <a href="https://wa.me/6282145671234?text={{ urlencode('Halo, saya ingin pesan: ' . $item->name . ' (Rp ' . number_format($item->price,0,',','.') . ')') }}" target="_blank" rel="noopener" class="ml-auto inline-flex items-center gap-2 rounded-xl bg-green-600 px-3 py-1 text-sm font-semibold text-white hover:bg-green-700">Pesan via WhatsApp</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="rounded-3xl border border-dashed border-amber-200 bg-white p-12 text-center text-gray-500">
                Menu belum tersedia.
            </div>
        @endforelse
    </section>
@endsection
