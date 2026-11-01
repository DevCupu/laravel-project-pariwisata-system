<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dante Pine Enrekang</title>
    <meta name="description"
        content="@yield('meta_description', 'Dante Pine Enrekang — kawasan wisata hutan pinus dengan aktivitas alam, spot foto, kafe, dan paket camping di jalur Poros Enrekang - Tana Toraja.')">
    <meta name="theme-color" content="#065f46">
    <script>
        document.documentElement.classList.add('js');
    </script>
    <link rel="icon" type="image/jpeg" href="{{ asset('assets-beranda/logo.jpg') }}">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,600;1,9..144,700&display=swap">

    @php
        $metaTitle = trim($__env->yieldContent('title')) ?: 'Dante Pine Enrekang';
        $metaDescription = trim($__env->yieldContent('meta_description')) ?: 'Kawasan wisata alam terpadu di jalur Poros Enrekang - Tana Toraja. Hutan pinus, kabut pagi, dan panorama pegunungan untuk pengalaman liburan yang aman dan berkesan.';
        $metaImage = trim($__env->yieldContent('og_image')) ?: asset('assets-beranda/home.jpg');
    @endphp
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Dante Pine Enrekang">
    <meta property="og:locale" content="id_ID">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">

    @stack('preload')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="bg-white text-slate-800 antialiased">

    @php
        $navAboutSections = ($navAboutSections ?? collect())->sortBy('display_order');
        $primaryAboutAnchor = optional($navAboutSections->first())->slug ?? 'tentang-kami';
        $isHome = request()->routeIs('home') || request()->is('/');
        $waNumber = '6282145671234';
        $waText = rawurlencode('Halo Dante Pine Enrekang, saya ingin bertanya tentang kunjungan.');
        $waLink = "https://wa.me/{$waNumber}?text={$waText}";
        $homeAnchor = function (?string $fragment = null) use ($isHome) {
            $slug = ltrim($fragment ?? '', '#');
            if ($slug === '') {
                return route('home');
            }

            return $isHome ? "#{$slug}" : route('home') . "#{$slug}";
        };
    @endphp

    <!-- Navigation -->
    <nav id="pn-nav" class="pn-nav pn-nav--solid">
        <div class="pn-container">
            <div class="flex h-16 items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Dante Pine Enrekang">
                    <img src="{{ asset('assets-beranda/logo.jpg') }}" alt="" class="h-9 w-9 rounded-xl object-cover select-none">
                    <span class="pn-brand">Dante<span class="pn-brand-pine">Pine</span></span>
                </a>

                <div class="hidden items-center gap-1 lg:flex">
                    <a href="{{ route('home') }}" class="pn-nav-link @if ($isHome) is-active @endif">Beranda</a>

                    <div class="pn-group relative">
                        <button class="pn-nav-link @if ($isHome) is-active @endif" aria-haspopup="true">
                            Tentang Kami <i class="fas fa-chevron-down text-[10px]" aria-hidden="true"></i>
                        </button>
                        <div class="pn-dropdown">
                            @forelse ($navAboutSections as $section)
                                <a href="{{ $homeAnchor($section->slug) }}" class="pn-dropdown-item">{{ $section->title }}</a>
                            @empty
                                <span class="pn-dropdown-item text-slate-400">Belum ada konten</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="pn-group relative">
                        <button class="pn-nav-link @if (request()->routeIs('activities.*', 'menu.*')) is-active @endif" aria-haspopup="true">
                            Jelajahi <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="pn-dropdown">
                            <a href="{{ route('activities.index') }}" class="pn-dropdown-item">Aktivitas & Wahana</a>
                            <a href="{{ route('menu.index') }}" class="pn-dropdown-item">Menu Kafe &amp; Harga</a>
                            <a href="{{ $homeAnchor('spot-foto') }}" class="pn-dropdown-item">Spot Foto</a>
                            <a href="{{ $homeAnchor('fasilitas-umum') }}" class="pn-dropdown-item">Fasilitas Umum</a>
                            <a href="{{ $homeAnchor('paket-camping') }}" class="pn-dropdown-item">Paket Camping</a>
                        </div>
                    </div>

                    <a href="{{ route('events.index') }}" class="pn-nav-link @if (request()->routeIs('events.*')) is-active @endif">Promo &amp; Event</a>
                    <a href="{{ $homeAnchor('faq') }}" class="pn-nav-link">FAQ</a>
                    <a href="{{ route('contact.form') }}" class="pn-nav-link @if (request()->routeIs('contact.*')) is-active @endif">Kontak</a>
                    <a href="{{ route('booking.form') }}" class="pn-btn pn-btn-primary ml-2 !px-5 !py-2.5">
                        <i class="fas fa-calendar-check"></i> Booking Sekarang
                    </a>
                </div>

                <button id="pn-mobile-btn" class="pn-nav-link lg:hidden" aria-label="Buka menu" aria-expanded="false"
                    aria-controls="pn-mobile-menu">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="pn-mobile-menu" class="hidden lg:hidden bg-white/95 backdrop-blur-xl border-t border-slate-200 text-slate-900 shadow-2xl">
            <div class="pn-container flex max-h-[calc(100dvh-4rem)] flex-col overflow-y-auto pb-6 pt-2">
                <a href="{{ route('home') }}" class="pn-mobile-link">Beranda</a>

                <details class="group border-b border-slate-200">
                    <summary class="pn-mobile-link cursor-pointer list-none border-0">
                        <span>Tentang Kami</span>
                        <i class="fas fa-chevron-down text-xs transition-transform group-open:rotate-180"></i>
                    </summary>
                    <div class="pb-3 pt-1">
                        @forelse ($navAboutSections as $section)
                            <a href="{{ $homeAnchor($section->slug) }}" class="pn-mobile-sublink">{{ $section->title }}</a>
                        @empty
                            <span class="pn-mobile-sublink text-slate-400">Belum ada konten</span>
                        @endforelse
                    </div>
                </details>

                <details class="group border-b border-slate-200">
                    <summary class="pn-mobile-link cursor-pointer list-none border-0">
                        <span>Jelajahi</span>
                        <i class="fas fa-chevron-down text-xs transition-transform group-open:rotate-180"></i>
                    </summary>
                    <div class="pb-3 pt-1">
                        <a href="{{ route('activities.index') }}" class="pn-mobile-sublink">Aktivitas & Wahana</a>
                        <a href="{{ route('menu.index') }}" class="pn-mobile-sublink">Menu Kafe & Harga</a>
                        <a href="{{ $homeAnchor('spot-foto') }}" class="pn-mobile-sublink">Spot Foto</a>
                        <a href="{{ $homeAnchor('fasilitas-umum') }}" class="pn-mobile-sublink">Fasilitas Umum</a>
                        <a href="{{ $homeAnchor('paket-camping') }}" class="pn-mobile-sublink">Paket Camping</a>
                    </div>
                </details>

                <a href="{{ route('events.index') }}" class="pn-mobile-link">Promo &amp; Event</a>
                <a href="{{ $homeAnchor('faq') }}" class="pn-mobile-link">FAQ</a>
                <a href="{{ route('contact.form') }}" class="pn-mobile-link">Kontak</a>
                <a href="{{ route('booking.form') }}" class="pn-btn pn-btn-primary mt-5 w-full">
                    <i class="fas fa-calendar-check"></i> Booking Sekarang
                </a>
            </div>
        </div>
    </nav>

    @include('partials.public.floating-actions', ['waLink' => $waLink, 'phone' => '0821-4567-1234'])

    <!-- Main Content -->
    <main class="@yield('main-class', 'pt-16')">
        @yield('content')
    </main>

    <footer class="border-t border-slate-800 bg-slate-950 text-slate-400">
        <div class="pn-container py-14">
            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('assets-beranda/logo.jpg') }}" alt="" class="h-10 w-10 rounded-xl object-cover select-none">
                        <span class="pn-brand">Dante<span class="pn-brand-pine">Pine</span></span>
                        <span class="h-4 w-px bg-white/15" aria-hidden="true"></span>
                        <span class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-300">Enrekang</span>
                    </div>
                    <p class="mt-5 text-sm leading-relaxed text-slate-400">
                        Kawasan wisata alam terpadu di jalur Poros Enrekang - Tana Toraja. Hutan pinus, kabut pagi, dan
                        panorama pegunungan untuk pengalaman liburan yang aman dan berkesan.
                    </p>
                    <div class="mt-6 flex gap-3 text-lg">
                        <a href="#" aria-label="Instagram" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/5 text-slate-300 transition hover:bg-emerald-500 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/5 text-slate-300 transition hover:bg-emerald-500 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="YouTube" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/5 text-slate-300 transition hover:bg-emerald-500 hover:text-white"><i class="fab fa-youtube"></i></a>
                        <a href="#" aria-label="TikTok" class="flex h-10 w-10 items-center justify-center rounded-full bg-white/5 text-slate-300 transition hover:bg-emerald-500 hover:text-white"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-white">Jelajahi</h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <li><a href="{{ route('home') }}" class="transition hover:text-emerald-400">Beranda</a></li>
                        <li><a href="{{ $homeAnchor($primaryAboutAnchor) }}" class="transition hover:text-emerald-400">Tentang Kami</a></li>
                        <li><a href="{{ route('activities.index') }}" class="transition hover:text-emerald-400">Aktivitas & Wahana</a></li>
                        <li><a href="{{ route('menu.index') }}" class="transition hover:text-emerald-400">Kafe & Menu</a></li>
                        <li><a href="{{ $homeAnchor('spot-foto') }}" class="transition hover:text-emerald-400">Spot Foto</a></li>
                        <li><a href="{{ route('events.index') }}" class="transition hover:text-emerald-400">Promo & Event</a></li>
                        <li><a href="{{ route('contact.form') }}" class="transition hover:text-emerald-400">Hubungi Kami</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-white">Informasi Pengunjung</h3>
                    <ul class="mt-5 space-y-4 text-sm">
                        <li>
                            <p class="font-semibold text-slate-200">Jam Operasional</p>
                            <p>Senin - Minggu | 08.00 - 22.00 WITA</p>
                        </li>
                        <li>
                            <p class="font-semibold text-slate-200">Lokasi</p>
                            <p>Jalur Poros Enrekang - Tana Toraja</p>
                        </li>
                        <li>
                            <p class="font-semibold text-slate-200">Hotline & Reservasi</p>
                            <p>+62 852-0000-0000</p>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-white">Rencanakan Kunjungan</h3>
                    <p class="mt-5 text-sm leading-relaxed">Tim kami siap membantu itinerary, paket grup, hingga penyewaan perlengkapan camping.</p>
                    <a href="{{ route('booking.form') }}" class="pn-btn pn-btn-primary mt-5 w-full">
                        <i class="fas fa-calendar-check"></i> Booking Sekarang
                    </a>
                    <div class="mt-5 space-y-2 text-sm">
                        <p><i class="fas fa-envelope mr-2 text-emerald-400"></i>info@dantepineenrekang.com</p>
                        <p><i class="fas fa-phone mr-2 text-emerald-400"></i>0821-4567-1234</p>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 text-sm md:flex-row">
                <p>&copy; {{ date('Y') }} Dante Pine Enrekang. Semua hak cipta dilindungi.</p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center gap-1.5 transition hover:text-emerald-400">
                        <i class="fas fa-lock text-[10px]" aria-hidden="true"></i> Masuk Admin
                    </a>
                    <span class="text-slate-700">|</span>
                    <a href="#" class="transition hover:text-emerald-400">Kebijakan Privasi</a>
                    <span class="text-slate-700">|</span>
                    <a href="#" class="transition hover:text-emerald-400">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu
        (function () {
            const btn = document.getElementById('pn-mobile-btn');
            const menu = document.getElementById('pn-mobile-menu');
            const icon = btn ? btn.querySelector('i') : null;
            if (!btn || !menu) return;

            const setOpen = (open) => {
                menu.classList.toggle('hidden', !open);
                btn.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (icon) icon.className = open ? 'fas fa-xmark text-xl' : 'fas fa-bars text-xl';
            };

            btn.addEventListener('click', () => setOpen(menu.classList.contains('hidden')));

            menu.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => setOpen(false));
            });

            menu.querySelectorAll('details').forEach((details) => {
                details.addEventListener('toggle', () => {
                    if (!details.open) return;
                    menu.querySelectorAll('details').forEach((other) => {
                        if (other !== details) other.open = false;
                    });
                });
            });
        })();

        // Scroll-aware nav (home hero overlay)
        (function () {
            const nav = document.getElementById('pn-nav');
            const sentinel = document.getElementById('pn-sentinel');
            if (!nav || !sentinel || !('IntersectionObserver' in window)) return;

            const observer = new IntersectionObserver(
                ([entry]) => {
                    nav.classList.toggle('is-scrolled', !entry.isIntersecting);
                },
                { rootMargin: '-72px 0px 0px 0px', threshold: 0 }
            );

            observer.observe(sentinel);
        })();

        // Scroll reveals
        (function () {
            const items = document.querySelectorAll('.pn-reveal');
            if (!items.length) return;

            if (!('IntersectionObserver' in window)) {
                document.documentElement.classList.remove('js');
                return;
            }

            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

            document.querySelectorAll('.pn-reveal').forEach((el) => io.observe(el));
        })();

        // Animated counters
        (function () {
            const counters = document.querySelectorAll('[data-pn-count]');
            if (!counters.length) return;

            const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            const run = (el) => {
                const target = parseFloat(el.dataset.pnCount) || 0;
                const suffix = el.dataset.pnSuffix || '';
                const duration = 1400;

                if (reduce) {
                    el.textContent = target.toLocaleString('id-ID') + suffix;
                    return;
                }

                const start = performance.now();
                const step = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.round(target * eased).toLocaleString('id-ID') + suffix;
                    if (progress < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };

            if (!('IntersectionObserver' in window)) {
                counters.forEach(run);
                return;
            }

            const io = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    run(entry.target);
                    io.unobserve(entry.target);
                });
            }, { threshold: 0.4 });

            counters.forEach((el) => io.observe(el));
        })();

        // Back to top
        (function () {
            const btn = document.getElementById('pn-totop');
            if (!btn) return;

            const toggle = () => btn.classList.toggle('is-visible', window.scrollY > 480);
            toggle();
            window.addEventListener('scroll', toggle, { passive: true });

            btn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        })();
    </script>
</body>

</html>
