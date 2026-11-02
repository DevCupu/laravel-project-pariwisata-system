@extends('layouts.public')

@section('title', 'Beranda')
@section('main-class', 'pt-16')

@push('preload')
    <link rel="preload" as="image" href="{{ asset('assets-beranda/dante-pine-gateway.webp') }}" fetchpriority="high">
@endpush

@push('head')
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "TouristAttraction",
            "name": "Dante Pine Enrekang",
            "description": "Kawasan wisata alam terpadu dengan hutan pinus, camping, aktivitas outdoor, dan kafe di jalur Poros Enrekang - Tana Toraja.",
            "address": {
                "@type": "PostalAddress",
                "addressLocality": "Enrekang",
                "addressRegion": "Sulawesi Selatan",
                "addressCountry": "ID"
            },
            "openingHours": "Mo-Su 08:00-22:00",
            "image": "{{ asset('assets-beranda/dante-pine-gateway.webp') }}",
            "url": "{{ route('home') }}"
        }
    </script>
@endpush

@section('content')

    {{-- Hero --}}
    <section class="relative flex min-h-[calc(100dvh-4rem)] items-center overflow-hidden"
        x-data="{
            slides: [
                { src: '{{ asset('assets-beranda/dante-pine-gateway.webp') }}', alt: 'Pemandangan pintu masuk Dante Pine dengan latar pegunungan Enrekang' },
                { src: '{{ asset('assets-beranda/dante-pine-cafe.webp') }}', alt: 'Suasana kafe Dante Pine dengan pemandangan lembah Enrekang' },
                { src: '{{ asset('assets-beranda/dante-pine-viewpoint.webp') }}', alt: 'Area pandang Dante Pine di antara hutan pinus dan pegunungan' },
            ],
            current: 0,
            timer: null,
            init() {
                if (! window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    this.timer = setInterval(() => this.next(), 6000);
                }
            },
            next() {
                this.current = (this.current + 1) % this.slides.length;
            },
            go(index) {
                this.current = index;
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            },
        }" aria-label="Selamat datang di Dante Pine Enrekang">
        <img :src="slides[0].src" :alt="slides[0].alt" src="{{ asset('assets-beranda/dante-pine-gateway.webp') }}"
            alt="Pemandangan pintu masuk Dante Pine dengan latar pegunungan Enrekang"
            class="absolute inset-0 h-full w-full object-cover object-center transition-opacity duration-1000 motion-reduce:transition-none"
            :class="current === 0 ? 'opacity-100' : 'opacity-0'" fetchpriority="high" decoding="async">
        <template x-for="(slide, index) in slides.slice(1)" :key="index">
            <img :src="slide.src" :alt="slide.alt" loading="lazy" decoding="async"
                class="absolute inset-0 h-full w-full object-cover object-center transition-opacity duration-1000 motion-reduce:transition-none"
                :class="current === index + 1 ? 'opacity-100' : 'opacity-0'">
        </template>
        <div class="absolute inset-0 bg-gradient-to-r from-[#0a1914]/85 via-[#10251c]/55 to-[#10251c]/15"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#0a1914]/75 via-transparent to-[#0a1914]/15"></div>

        <div class="pn-container relative z-10 grid w-full items-center gap-12 pt-28 pb-32 sm:pt-36 lg:grid-cols-[minmax(0,1.1fr)_minmax(18rem,.62fr)] lg:gap-20">
            <div class="max-w-3xl">
                <p class="inline-flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-200">
                    <span class="h-px w-8 bg-emerald-300"></span>
                    Enrekang &middot; Sulawesi Selatan
                </p>
                <h1 class="mt-6 max-w-3xl font-[Fraunces] text-5xl font-semibold leading-[1.04] tracking-[-0.035em] text-white drop-shadow-sm sm:text-6xl lg:text-7xl">
                    Jeda sejenak, dekat dengan alam.
                </h1>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-white/85 sm:text-lg">
                    Temukan udara sejuk, hutan pinus, dan panorama pegunungan di Dante Pine, jalur Poros Enrekang–Tana Toraja.
                </p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('booking.form') }}" class="pn-btn pn-btn-primary">
                        <i class="fas fa-calendar-check" aria-hidden="true"></i> Rencanakan kunjungan
                    </a>
                    <a href="{{ route('activities.index') }}" class="pn-btn pn-btn-ghost">
                        <i class="fas fa-compass" aria-hidden="true"></i> Jelajahi pengalaman
                    </a>
                </div>
            </div>

            <aside class="max-w-md rounded-2xl border border-white/15 bg-[#102c24]/90 p-5 text-white shadow-2xl shadow-black/20 sm:p-6 lg:justify-self-end" aria-label="Informasi kunjungan">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-200">Rencanakan kunjungan</p>
                <dl class="mt-5 divide-y divide-white/10">
                    <div class="flex gap-4 py-4 first:pt-0">
                        <i class="fas fa-location-dot mt-1 w-4 text-emerald-300" aria-hidden="true"></i>
                        <div>
                            <dt class="text-xs text-white/60">Lokasi</dt>
                            <dd class="mt-1 text-sm font-medium leading-relaxed">Poros Enrekang–Tana Toraja</dd>
                        </div>
                    </div>
                    <div class="flex gap-4 py-4">
                        <i class="fas fa-clock mt-1 w-4 text-emerald-300" aria-hidden="true"></i>
                        <div>
                            <dt class="text-xs text-white/60">Jam operasional</dt>
                            <dd class="mt-1 text-sm font-medium">Setiap hari, 08.00–22.00 WITA</dd>
                        </div>
                    </div>
                    <div class="flex gap-4 pt-4">
                        <i class="fas fa-tree mt-1 w-4 text-emerald-300" aria-hidden="true"></i>
                        <div>
                            <dt class="text-xs text-white/60">Pengalaman</dt>
                            <dd class="mt-1 text-sm font-medium leading-relaxed">Camping, aktivitas alam, spot foto, dan kafe</dd>
                        </div>
                    </div>
                </dl>
            </aside>
        </div>

        <div class="absolute bottom-8 right-6 z-10 flex items-center gap-2 sm:right-10" role="group" aria-label="Pilih foto beranda">
            <template x-for="(slide, index) in slides" :key="index">
                <button type="button" class="h-2 rounded-full transition-all duration-300"
                    :class="current === index ? 'w-7 bg-white' : 'w-2 bg-white/40 hover:bg-white/70'"
                    :aria-label="'Foto ' + (index + 1)" :aria-current="current === index ? 'true' : 'false'"
                    @click="go(index)"></button>
            </template>
        </div>
    </section>

    <div id="pn-sentinel" class="h-px w-full" aria-hidden="true"></div>

    {{-- Statistik singkat --}}
    <section id="statistik" class="border-b border-slate-100 bg-white">
        <div class="pn-container">
            <dl class="grid grid-cols-2 gap-y-8 py-12 sm:grid-cols-4">
                @foreach ([
                    ['label' => 'Aktivitas & Wahana', 'value' => $counts['activities']],
                    ['label' => 'Paket Camping', 'value' => $counts['packages']],
                    ['label' => 'Spot Foto', 'value' => $counts['spots']],
                    ['label' => 'Fasilitas Umum', 'value' => $counts['facilities']],
                ] as $stat)
                    <div class="flex flex-col items-center gap-2 px-4 text-center">
                        <dd class="text-4xl font-bold tracking-tight text-emerald-700" data-pn-count="{{ $stat['value'] }}" data-pn-suffix="+">0</dd>
                        <dt class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $stat['label'] }}</dt>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    {{-- Tentang Kami --}}
    @if ($aboutSections->isNotEmpty())
        <section class="bg-white py-20 sm:py-28">
            <div class="pn-container">
                <header class="pn-reveal mx-auto max-w-2xl text-center">
                    <span class="pn-eyebrow justify-center text-emerald-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Tentang Kami
                    </span>
                    <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        Bukan sekadar tempat singgah, ini pengalaman alam yang kami rawat
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-slate-600">
                        Kami mengelola kawasan wisata alam terpadu dengan standar pelayanan resmi dan rasa aman untuk setiap pengunjung.
                    </p>
                </header>

                <div class="mt-16 space-y-16 sm:space-y-24">
                    @foreach ($aboutSections as $index => $section)
                        @php
                            $reversed = $index % 2 === 1;
                            $fallbackImage = $index === 0
                                ? asset('assets-beranda/dante-pine-gateway.webp')
                                : asset('assets-beranda/dante-pine-viewpoint.webp');
                        @endphp
                        <article id="{{ $section->slug }}"
                            class="scroll-mt-24 grid items-center gap-8 lg:grid-cols-2 lg:gap-14">
                            <div class="{{ $reversed ? 'lg:order-last' : '' }}">
                                <span class="text-xs font-bold uppercase tracking-[0.3em] text-emerald-600">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                                <h3 class="mt-3 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                                    {{ $section->title }}
                                </h3>
                                <p class="mt-4 text-base leading-relaxed text-slate-600">{{ $section->content }}</p>
                            </div>

                            <div class="relative overflow-hidden rounded-3xl {{ $reversed ? 'lg:order-first' : '' }}">
                                <img src="{{ $section->image_path ? $section->image_url : $fallbackImage }}" alt="{{ $section->title }}" loading="lazy" decoding="async"
                                    class="aspect-[4/3] w-full object-cover">
                                <div class="absolute inset-0 rounded-3xl ring-1 ring-inset ring-slate-900/10"></div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Aktivitas Unggulan --}}
    @if ($featuredActivities->isNotEmpty())
        @php
            $categoryMeta = [
                'wahana' => ['label' => 'Wahana', 'icon' => 'fas fa-cable-car', 'gradient' => 'from-emerald-500 to-teal-600'],
                'culture' => ['label' => 'Budaya', 'icon' => 'fas fa-masks-theater', 'gradient' => 'from-teal-600 to-emerald-800'],
                'nature' => ['label' => 'Alam', 'icon' => 'fas fa-mountain-sun', 'gradient' => 'from-green-600 to-emerald-800'],
                'experience' => ['label' => 'Pengalaman', 'icon' => 'fas fa-mug-hot', 'gradient' => 'from-emerald-600 to-slate-800'],
            ];
        @endphp
        <section id="aktivitas" class="scroll-mt-24 bg-slate-50 py-20 sm:py-28">
            <div class="pn-container">
                <header class="pn-reveal flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                    <div class="max-w-xl">
                        <span class="pn-eyebrow text-emerald-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Aktivitas Unggulan
                        </span>
                        <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                            Ada kegiatan untuk setiap tipe penjelajah
                        </h2>
                    </div>
                    <a href="{{ route('activities.index') }}" class="group inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition hover:text-emerald-800">
                        Lihat semua aktivitas
                        <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                    </a>
                </header>

                <div class="mt-12 grid gap-6 md:grid-cols-3">
                    @foreach ($featuredActivities as $activity)
                        @php
                            $meta = $categoryMeta[$activity->category] ?? ['label' => ucfirst((string) $activity->category), 'icon' => 'fas fa-star', 'gradient' => 'from-emerald-600 to-slate-800'];
                            $mins = (int) $activity->duration_minutes;
                            $duration = $mins >= 60
                                ? intdiv($mins, 60) . ' jam' . ($mins % 60 ? ' ' . ($mins % 60) . ' mnt' : '')
                                : ($mins ? $mins . ' menit' : null);
                        @endphp
                        <article class="pn-card pn-card-hover flex flex-col">
                            <div class="relative flex h-40 items-center justify-center bg-gradient-to-br {{ $meta['gradient'] }}">
                                <i class="{{ $meta['icon'] }} text-5xl text-white/80" aria-hidden="true"></i>
                                <span class="absolute left-4 top-4 rounded-full bg-white/20 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.15em] text-white backdrop-blur-sm">
                                    {{ $meta['label'] }}
                                </span>
                            </div>
                            <div class="flex flex-1 flex-col p-6">
                                <h3 class="text-xl font-bold text-slate-900">{{ $activity->title }}</h3>
                                <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">{{ $activity->short_description }}</p>

                                <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs font-medium text-slate-500">
                                    @if ($duration)
                                        <span><i class="fas fa-clock mr-1.5 text-emerald-500" aria-hidden="true"></i>{{ $duration }}</span>
                                    @endif
                                    @if ($activity->min_age)
                                        <span><i class="fas fa-user-check mr-1.5 text-emerald-500" aria-hidden="true"></i>Usia {{ $activity->min_age }}+</span>
                                    @endif
                                </div>

                                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
                                    @if ($activity->price_from)
                                        <p class="text-sm text-slate-500">
                                            Mulai <span class="text-lg font-bold text-slate-900">Rp{{ number_format($activity->price_from, 0, ',', '.') }}</span>
                                        </p>
                                    @else
                                        <span></span>
                                    @endif
                                    <a href="{{ route('booking.form', ['facility' => 'activity-' . $activity->slug]) }}"
                                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 transition hover:text-emerald-800">
                                        Booking <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Spot Foto --}}
    @if ($photoSpots->isNotEmpty())
        <section id="spot-foto" class="scroll-mt-24 bg-slate-950 py-20 text-white sm:py-28">
            <div class="pn-container">
                <header class="pn-reveal mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Area favorit pemburu konten</h2>
                    <p class="mt-4 text-base leading-relaxed text-slate-400">
                        Tidak perlu reservasi. Cukup datang, jaga etika lokasi, dan dapatkan sudut terbaiknya.
                    </p>
                </header>

                <div class="mt-14 grid gap-6 lg:grid-cols-3">
                    @foreach ($photoSpots as $spot)
                        <article class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                @if ($spot->image_path)
                                    <img src="{{ $spot->image_url }}" alt="{{ $spot->title }}" loading="lazy" decoding="async"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-900 to-slate-900">
                                        <i class="fas fa-camera-retro text-5xl text-white/30" aria-hidden="true"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/30 to-transparent"></div>
                                <div class="absolute inset-x-5 bottom-5">
                                    @if ($spot->location_text)
                                        <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
                                            <i class="fas fa-location-dot" aria-hidden="true"></i>{{ $spot->location_text }}
                                        </p>
                                    @endif
                                    <h3 class="mt-1.5 text-xl font-bold text-white">{{ $spot->title }}</h3>
                                </div>
                            </div>
                            <div class="space-y-4 p-6">
                                <p class="text-sm leading-relaxed text-slate-300">{{ $spot->description }}</p>
                                <div class="flex flex-wrap gap-3 text-xs">
                                    @if ($spot->best_time)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/5 px-3 py-1.5 text-slate-300">
                                            <i class="fas fa-clock text-emerald-400" aria-hidden="true"></i>{{ $spot->best_time }}
                                        </span>
                                    @endif
                                </div>
                                @if ($spot->tips)
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                                        <p class="mb-1 font-semibold text-white"><i class="fas fa-lightbulb mr-1.5 text-emerald-400" aria-hidden="true"></i>Tips fotografer</p>
                                        <p>{{ $spot->tips }}</p>
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Paket Camping --}}
    @if ($campingPackages->isNotEmpty())
        <section id="paket-camping" class="scroll-mt-24 bg-white py-20 sm:py-28">
            <div class="pn-container">
                <header class="pn-reveal mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Pilih paket sesuai gaya liburanmu</h2>
                    <p class="mt-4 text-base leading-relaxed text-slate-600">
                        Semua paket fleksibel dan bisa disesuaikan. Harga sudah termasuk akses area.
                    </p>
                </header>

                <div class="mt-14 grid gap-6 md:grid-cols-3">
                    @foreach ($campingPackages as $package)
                        @php $includes = $package->includes ?? []; @endphp
                        <article class="pn-card pn-card-hover flex flex-col {{ $package->is_featured ? 'ring-2 ring-emerald-500' : '' }}">
                            <div class="relative">
                                @if ($package->image_path)
                                    <img src="{{ $package->image_url }}" alt="{{ $package->name }}" loading="lazy" decoding="async"
                                        class="h-44 w-full object-cover">
                                @else
                                    <div class="flex h-40 items-center justify-center bg-gradient-to-br from-emerald-600 to-slate-800">
                                        <i class="fas fa-campground text-5xl text-white/70" aria-hidden="true"></i>
                                    </div>
                                @endif
                                @if ($package->is_featured)
                                    <span class="absolute right-4 top-4 rounded-full bg-emerald-500 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.15em] text-white shadow-lg">
                                        Favorit
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-6">
                                @if ($package->tagline)
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-600">{{ $package->tagline }}</p>
                                @endif
                                <h3 class="mt-2 text-xl font-bold text-slate-900">{{ $package->name }}</h3>

                                <div class="mt-4 flex items-baseline gap-2">
                                    @if ($package->price)
                                        <span class="text-3xl font-bold tracking-tight text-slate-900">Rp{{ number_format($package->price, 0, ',', '.') }}</span>
                                    @endif
                                    <span class="text-sm text-slate-500">
                                        {{ $package->nights }} malam &middot; {{ $package->capacity }} orang
                                    </span>
                                </div>

                                <p class="mt-4 text-sm leading-relaxed text-slate-600">{{ $package->description }}</p>

                                @if (! empty($includes))
                                    <ul class="mt-5 space-y-2.5 border-t border-slate-100 pt-5 text-sm text-slate-700">
                                        @foreach ($includes as $item)
                                            <li class="flex items-start gap-2.5">
                                                <i class="fas fa-check mt-1 text-xs text-emerald-600" aria-hidden="true"></i>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                <a href="{{ route('booking.form', ['facility' => 'camp-' . $package->slug]) }}"
                                    class="pn-btn pn-btn-primary mt-8 w-full">
                                    Booking Paket Ini
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Fasilitas Umum --}}
    @if ($generalFacilities->isNotEmpty())
        <section id="fasilitas-umum" class="scroll-mt-24 bg-slate-50 py-20 sm:py-28">
            <div class="pn-container">
                <header class="pn-reveal mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Kenyamanan dasar sudah kami siapkan</h2>
                    <p class="mt-4 text-base leading-relaxed text-slate-600">
                        Fasilitas berikut tersedia untuk semua pengunjung tanpa biaya tambahan.
                    </p>
                </header>

                <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($generalFacilities as $facility)
                        <article class="pn-card flex flex-col p-6">
                            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-2xl text-emerald-700">
                                <i class="{{ $facility->icon }}" aria-hidden="true"></i>
                            </div>
                            <h3 class="mt-5 text-lg font-bold text-slate-900">{{ $facility->name }}</h3>
                            <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">{{ $facility->description }}</p>
                            @if ($facility->availability_note)
                                <p class="mt-4 flex items-start gap-2 border-t border-slate-100 pt-4 text-xs font-medium text-emerald-700">
                                    <i class="fas fa-circle-info mt-0.5" aria-hidden="true"></i><span>{{ $facility->availability_note }}</span>
                                </p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Menu Kafe --}}
    @if ($menuHighlights->isNotEmpty())
        <section id="menu-kafe" class="scroll-mt-24 bg-white py-20 sm:py-28">
            <div class="pn-container">
                <header class="pn-reveal flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                    <div class="max-w-xl">
                        <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Hangat dari kafe kami</h2>
                        <p class="mt-4 text-base leading-relaxed text-slate-600">
                            Kopi single origin, camilan hangat, dan menu favorit untuk menemani udara pinus.
                        </p>
                    </div>
                    <a href="{{ route('menu.index') }}" class="group inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition hover:text-emerald-800">
                        Lihat menu lengkap
                        <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                    </a>
                </header>

                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($menuHighlights as $item)
                        <article class="pn-card pn-card-hover flex flex-col">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" loading="lazy" decoding="async"
                                    class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                                @if ($item->badge)
                                    <span class="absolute left-3 top-3 rounded-full bg-emerald-600 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-white">
                                        {{ $item->badge }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-600">
                                    {{ \Illuminate\Support\Str::headline($item->section) }}
                                </p>
                                <h3 class="mt-2 text-lg font-bold text-slate-900">{{ $item->name }}</h3>
                                <p class="mt-1.5 flex-1 text-sm leading-relaxed text-slate-600">{{ $item->short_description }}</p>
                                <p class="mt-4 border-t border-slate-100 pt-4 text-lg font-bold text-slate-900">
                                    Rp{{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Promo & Event --}}
    @if ($events->isNotEmpty())
        @php
            $bulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $formatDate = function ($date) use ($bulan) {
                return $date->day . ' ' . $bulan[$date->month] . ' ' . $date->year;
            };
        @endphp
        <section id="promo-event" class="scroll-mt-24 bg-slate-950 py-20 text-white sm:py-28">
            <div class="pn-container">
                <header class="pn-reveal flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                    <div class="max-w-xl">
                        <span class="pn-eyebrow text-emerald-400">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Promo & Event
                        </span>
                        <h2 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            Ada yang baru di Dante Pine
                        </h2>
                    </div>
                    <a href="{{ route('events.index') }}" class="group inline-flex items-center gap-2 text-sm font-semibold text-emerald-400 transition hover:text-emerald-300">
                        Lihat semua agenda
                        <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                    </a>
                </header>

                <div class="mt-14 grid gap-6 md:grid-cols-3">
                    @foreach ($events as $event)
                        @php $isPromo = $event->type === 'promo'; @endphp
                        <article class="group relative overflow-hidden rounded-3xl border border-white/10 bg-white/5">
                            <div class="relative aspect-[16/10] overflow-hidden">
                                @if ($event->image_path)
                                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" loading="lazy" decoding="async"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-900 to-slate-900">
                                        <i class="fas {{ $isPromo ? 'fa-tags' : 'fa-calendar-star' }} text-5xl text-white/30" aria-hidden="true"></i>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent"></div>
                                <span class="absolute left-5 top-5 rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.15em] {{ $isPromo ? 'bg-emerald-500 text-white' : 'bg-white/15 text-white backdrop-blur-sm' }}">
                                    {{ $isPromo ? 'Promo' : 'Event' }}
                                </span>
                            </div>
                            <div class="p-6">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-300">
                                    @if ($event->end_date && $event->end_date->ne($event->start_date))
                                        {{ $formatDate($event->start_date) }} &ndash; {{ $formatDate($event->end_date) }}
                                    @else
                                        {{ $formatDate($event->start_date) }}
                                    @endif
                                </p>
                                <h3 class="mt-3 text-xl font-bold text-white">{{ $event->title }}</h3>
                                <p class="mt-3 text-sm leading-relaxed text-slate-300">{{ $event->excerpt }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Testimoni --}}
    @if ($testimonials->isNotEmpty())
        <section id="testimoni" class="scroll-mt-24 bg-slate-50 py-20 sm:py-28">
            <div class="pn-container">
                <header class="pn-reveal mx-auto max-w-2xl text-center">
                    <span class="pn-eyebrow justify-center text-emerald-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Testimoni Pengunjung
                    </span>
                    <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        Cerita dari mereka yang sudah berkunjung
                    </h2>
                    <p class="mt-4 text-base leading-relaxed text-slate-600">
                        Pengalaman nyata pengunjung Dante Pine Enrekang.
                    </p>
                </header>

                <div class="mt-14 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <figure class="pn-card pn-card-hover flex flex-col p-6">
                            <div class="flex items-center gap-1 text-amber-400" aria-label="Rating {{ $testimonial->rating }} dari 5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-sm {{ $i <= $testimonial->rating ? 'text-amber-400' : 'text-slate-200' }}" aria-hidden="true"></i>
                                @endfor
                            </div>
                            <blockquote class="mt-4 flex-1 text-sm leading-relaxed text-slate-600">
                                &ldquo;{{ $testimonial->quote }}&rdquo;
                            </blockquote>
                            <figcaption class="mt-6 flex items-center gap-3 border-t border-slate-100 pt-5">
                                @if ($testimonial->avatar_path)
                                    <img src="{{ $testimonial->avatar_url }}" alt="{{ $testimonial->name }}" loading="lazy" decoding="async"
                                        class="h-11 w-11 flex-shrink-0 rounded-full object-cover">
                                @else
                                    <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                                        {{ $testimonial->initials }}
                                    </span>
                                @endif
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-bold text-slate-900">{{ $testimonial->name }}</p>
                                    <p class="truncate text-xs text-slate-500">
                                        {{ collect([$testimonial->role, $testimonial->location])->filter()->implode(' • ') ?: 'Pengunjung' }}
                                    </p>
                                </div>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Lokasi & Akses --}}
    <section id="lokasi" class="scroll-mt-24 bg-white py-20 sm:py-28">
        <div class="pn-container">
            <header class="pn-reveal mx-auto max-w-2xl text-center">
                <span class="pn-eyebrow justify-center text-emerald-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Lokasi & Akses
                </span>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Mudah dijangkau di jalur Poros Enrekang - Toraja
                </h2>
                <p class="mt-4 text-base leading-relaxed text-slate-600">
                    Kawasan kami berada tepat di jalur utama dengan akses kendaraan roda dua maupun empat.
                </p>
            </header>

            <div class="mt-14 grid gap-8 lg:grid-cols-5">
                <div class="lg:col-span-2">
                    <div class="space-y-5">
                        <div class="pn-card flex items-start gap-4 p-5">
                            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                                <i class="fas fa-map-location-dot" aria-hidden="true"></i>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-slate-900">Alamat</p>
                                <p class="mt-1 text-sm leading-relaxed text-slate-600">Jalur Poros Enrekang - Tana Toraja, Kabupaten Enrekang, Sulawesi Selatan</p>
                            </div>
                        </div>
                        <div class="pn-card flex items-start gap-4 p-5">
                            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                                <i class="fas fa-clock" aria-hidden="true"></i>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-slate-900">Jam Operasional</p>
                                <p class="mt-1 text-sm leading-relaxed text-slate-600">Setiap hari, 08.00 - 22.00 WITA</p>
                            </div>
                        </div>
                        <div class="pn-card flex items-start gap-4 p-5">
                            <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                                <i class="fas fa-car" aria-hidden="true"></i>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-slate-900">Transportasi</p>
                                <p class="mt-1 text-sm leading-relaxed text-slate-600">Kendaraan pribadi, travel, atau sewa. Area parkir luas tersedia di lokasi.</p>
                            </div>
                        </div>
                    </div>
                    <a href="https://www.google.com/maps?q=Dante%20Pine%20Enrekang&output=embed" target="_blank" rel="noopener" class="pn-btn pn-btn-primary mt-6 w-full">
                        <i class="fas fa-diamond-turn-right" aria-hidden="true"></i> Buka Rute di Google Maps
                    </a>
                </div>

                <div class="pn-card overflow-hidden lg:col-span-3">
                    <iframe
                        src="https://www.google.com/maps?q=Enrekang%2C%20Sulawesi%20Selatan&output=embed"
                        class="h-72 w-full lg:h-full lg:min-h-[24rem]" style="border:0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        title="Peta lokasi Dante Pine Enrekang" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    @php
        $faqs = [
            ['q' => 'Apakah perlu booking terlebih dahulu?', 'a' => 'Untuk kunjungan harian tanpa menginap tidak perlu booking. Namun untuk paket camping, wahana tertentu, dan rombongan, kami sangat menyarankan booking lebih awal.'],
            ['q' => 'Jam berapa waktu terbaik berkunjung?', 'a' => 'Pagi hari (06.00 - 09.00 WITA) adalah waktu terbaik untuk menikmati kabut dan suasana sejuk, sedangkan sore hingga malam cocok untuk camping dan api unggun.'],
            ['q' => 'Apakah tersedia fasilitas untuk keluarga dan anak-anak?', 'a' => 'Ya. Kami menyediakan area aman, toilet, musala, area parkir, dan beberapa aktivitas ramah anak. Setiap wahana memiliki ketentuan usia minimum.'],
            ['q' => 'Bagaimana cara pembayaran dan pembatalan?', 'a' => 'Pembayaran dapat dilakukan saat konfirmasi booking. Untuk perubahan atau pembatalan, silakan hubungi tim kami melalui WhatsApp minimal 1x24 jam sebelum kunjungan.'],
        ];
    @endphp
    <section id="faq" class="scroll-mt-24 bg-slate-50 py-20 sm:py-28">
        <div class="pn-container">
            <header class="pn-reveal mx-auto max-w-2xl text-center">
                <span class="pn-eyebrow justify-center text-emerald-600">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Pertanyaan Umum
                </span>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                    Hal yang sering ditanyakan pengunjung
                </h2>
            </header>

            <div class="mx-auto mt-12 max-w-3xl space-y-3">
                @foreach ($faqs as $faq)
                    <details class="pn-faq pn-card p-0">
                        <summary class="flex items-center justify-between gap-4 p-5">
                            <span class="text-base font-semibold text-slate-900">{{ $faq['q'] }}</span>
                            <i class="pn-faq-icon fas fa-plus flex-shrink-0 text-emerald-600" aria-hidden="true"></i>
                        </summary>
                        <div class="px-5 pb-5 text-sm leading-relaxed text-slate-600">
                            {{ $faq['a'] }}
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA penutup --}}
    <section class="relative overflow-hidden bg-emerald-900 py-20 text-white sm:py-24">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-800 via-emerald-900 to-slate-950"></div>
        <div class="pn-container relative text-center">
            <h2 class="mx-auto max-w-2xl text-3xl font-bold tracking-tight sm:text-4xl">
                Siap merencanakan kunjungan ke Dante Pine?
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-emerald-100/80">
                Tim kami siap membantu itinerary, paket grup, hingga penyewaan perlengkapan camping. Hubungi kami atau langsung booking.
            </p>
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ route('booking.form') }}" class="pn-btn pn-btn-primary">
                    <i class="fas fa-calendar-check" aria-hidden="true"></i> Booking Sekarang
                </a>
                <a href="{{ route('contact.form') }}" class="pn-btn pn-btn-ghost">
                    <i class="fas fa-comments" aria-hidden="true"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </section>

@endsection
