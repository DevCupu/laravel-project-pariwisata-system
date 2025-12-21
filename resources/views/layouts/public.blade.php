<!-- Ganti semua hover:text-blue-300 menjadi hover:text-green-400 pada menu navigasi -->
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Dante Pine Enrekang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo-dpe.svg') }}">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-transparent" style="margin:0; padding:0;">

    @php
        $navAboutSections = ($navAboutSections ?? collect())->sortBy('display_order');
        $primaryAboutAnchor = optional($navAboutSections->first())->slug ?? 'tentang-kami';
        $isHome = request()->routeIs('home') || request()->is('/');
        $homeAnchor = function (?string $fragment = null) use ($isHome) {
            $slug = ltrim($fragment ?? '', '#');
            if ($slug === '') {
                return route('home');
            }

            return $isHome ? "#{$slug}" : route('home') . "#{$slug}";
        };
    @endphp

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 {{ $isHome ? 'bg-transparent' : 'bg-gradient-to-r from-green-50 via-white to-green-100 shadow-lg border-b border-green-100' }}">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between py-4 gap-4">
                <div class="flex items-center">
                    <img src="{{ asset('logo-dpe.svg') }}" alt="Dante Pine Enrekang" class="h-8 w-8 mr-2 select-none" />
                    <a href="{{ route('home') }}" class="logo-text font-bold text-gray-900">Dante Pine</a>
                </div>
                <div class="hidden md:flex flex-wrap items-center justify-end gap-2 gap-y-3 xl:gap-4 text-sm lg:text-base">
                    <a href="{{ route('home') }}" class="nav-link">Beranda</a>
                    <div class="group relative">
                        <button class="nav-link flex items-center gap-1" aria-haspopup="true" aria-expanded="false">
                            Tentang Kami <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="dropdown-panel">
                            @forelse ($navAboutSections as $section)
                                <a href="{{ $homeAnchor($section->slug) }}" class="dropdown-item">{{ $section->title }}</a>
                            @empty
                                <span class="dropdown-item text-gray-400">Belum ada konten</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="group relative">
                        <button class="nav-link flex items-center gap-1" aria-haspopup="true" aria-expanded="false">
                            Aktivitas & Fasilitas <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="dropdown-panel">
                            <a href="{{ route('activities.index') }}" class="dropdown-item">Daftar Aktivitas & Wahana</a>
                            <a href="{{ route('menu.index') }}" class="dropdown-item">Menu Kafe & Harga</a>
                            <a href="{{ $homeAnchor('spot-foto') }}" class="dropdown-item">Spot Foto</a>
                            <a href="{{ $homeAnchor('fasilitas-umum') }}" class="dropdown-item">Fasilitas Umum</a>
                            <a href="{{ $homeAnchor('paket-camping') }}" class="dropdown-item">Paket Camping</a>
                        </div>
                    </div>
                    <a href="{{ route('events.index') }}" class="nav-link">Promo & Event</a>
                    <a href="{{ route('contact.form') }}" class="nav-link">Hubungi Kami</a>
                    <a href="{{ route('booking.form') }}" class="booking-btn booking-btn">
                        <i class="fas fa-calendar-check mr-2"></i> Booking Sekarang
                    </a>
                </div>
                <button id="mobile-menu-btn" class="md:hidden text-gray-900">
                        <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
            <div id="mobile-menu" class="md:hidden hidden bg-white/95 text-gray-900 shadow-2xl border-t border-green-200">
            <div class="flex flex-col px-4 pb-4 space-y-1">
                <a href="{{ route('home') }}" class="mobile-link">Beranda</a>

                <a href="{{ $homeAnchor($primaryAboutAnchor) }}" class="mobile-link">Tentang Kami</a>
                <div class="mobile-sublinks">
                    @forelse ($navAboutSections as $section)
                        <a href="{{ $homeAnchor($section->slug) }}" class="mobile-sublink">{{ $section->title }}</a>
                    @empty
                        <span class="mobile-sublink text-gray-400">Belum ada konten</span>
                    @endforelse
                </div>

                <button id="mobile-activities-btn" class="mobile-link flex items-center justify-between">
                    <span>Aktivitas & Fasilitas</span>
                    <i class="fas fa-chevron-down" id="mobile-activities-icon"></i>
                </button>
                <div id="mobile-activities-list" class="mobile-sublinks hidden">
                    <a href="{{ route('activities.index') }}" class="mobile-sublink">Daftar Aktivitas & Wahana</a>
                    <a href="{{ route('menu.index') }}" class="mobile-sublink">Menu Kafe & Harga</a>
                    <a href="{{ $homeAnchor('spot-foto') }}" class="mobile-sublink">Spot Foto</a>
                    <a href="{{ $homeAnchor('fasilitas-umum') }}" class="mobile-sublink">Fasilitas Umum</a>
                    <a href="{{ $homeAnchor('paket-camping') }}" class="mobile-sublink">Paket Camping</a>
                </div>

                <a href="{{ route('events.index') }}" class="mobile-link">Promo & Event</a>
                <a href="{{ route('contact.form') }}" class="mobile-link">Hubungi Kami</a>
                <a href="{{ route('booking.form') }}" class="mobile-booking"><i class="fas fa-calendar-check mr-2"></i> Booking Sekarang</a>
            </div>
        </div>
    </nav>

    <style>
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-16px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        /* Responsive font for logo */
        .logo-text { font-size: clamp(1.2rem, 2vw, 1.6rem); }
        .nav-link {
            color: #0f172a;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.95rem;
        }
        .nav-link:hover { color: #047857; }
        .dropdown-panel {
            display: none;
            position: absolute;
            top: calc(100% + 0.75rem);
            left: 0;
            min-width: 15rem;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 1rem;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
            backdrop-filter: blur(12px);
            padding: 0.75rem;
            animation: fade-in-down 0.25s ease;
            z-index: 30;
        }
        .group:hover .dropdown-panel,
        .group:focus-within .dropdown-panel { display: block; }
        .dropdown-item {
            display: block;
            padding: 0.6rem 0.85rem;
            font-size: 0.92rem;
            color: #0f172a;
            font-weight: 600;
            border-radius: 0.65rem;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .dropdown-item:hover {
            background: rgba(16, 185, 129, 0.08);
            color: #065f46;
        }
        .booking-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1.1rem;
            border-radius: 999px;
            background: #22c55e;
            color: white;
            font-weight: 600;
        }
        .booking-btn:hover { background: #15803d; }
        .booking-btn--light {
            background: #0f766e;
            color: #f0fdfa;
            border: 1px solid rgba(15, 118, 110, 0.2);
        }
        .booking-btn--light:hover {
            background: #115e59;
            color: #ecfdf5;
        }
        .mobile-link {
            width: 100%;
            text-align: left;
            padding: 0.85rem 0;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            font-weight: 600;
            color: #0f172a;
        }
        .mobile-link:hover {
            color: #047857;
        }
        .mobile-sublinks {
            display: flex;
            flex-direction: column;
            padding: 0.35rem 0 0.75rem 0.75rem;
            margin-top: -0.35rem;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }
        .mobile-sublink {
            padding: 0.35rem 0;
            font-size: 0.9rem;
            color: #475569;
        }
        .mobile-sublink:hover {
            color: #047857;
        }
        .mobile-booking {
            margin-top: 0.85rem;
            padding: 0.85rem;
            border-radius: 0.75rem;
            text-align: center;
            background: #16a34a;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.25);
        }

        @media (max-width: 1024px) {
            .nav-link {
                padding: 0.25rem 0.55rem;
                font-size: 0.85rem;
            }
            .booking-btn {
                padding: 0.45rem 1rem;
                font-size: 0.85rem;
            }
        }

        @if($isHome)
        /* Homepage-only transparent nav adjustments */
        nav {
            background: transparent !important;
            box-shadow: none !important;
            border-bottom: none !important;
        }
        nav .logo-text,
        nav .nav-link,
        nav .nav-link i,
        #mobile-menu-btn,
        #mobile-menu-btn i {
            color: #ffffff !important;
        }
        nav .booking-btn--light {
            background: rgba(15, 118, 110, 0.35);
            border-color: rgba(255, 255, 255, 0.45);
            color: #ffffff;
        }
        nav .booking-btn--light:hover {
            background: rgba(15, 94, 89, 0.6);
            color: #ffffff;
        }
        nav .dropdown-panel {
            background: rgba(255, 255, 255, 0.95);
            color: #0f172a;
        }
        nav .dropdown-item {
            color: #0f172a;
        }
        @endif
    </style>

    <!-- Main Content -->
    <main class="pt-16 mb-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 text-gray-900 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('logo-dpe.svg') }}" alt="Logo Dante Pine" class="h-10 w-10 mr-3">
                        <div>
                            <p class="text-sm uppercase tracking-widest text-green-700">Dante Pine</p>
                            <p class="text-xl font-bold">Enrekang</p>
                        </div>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">Eksplor hutan pinus, aktivitas ekstrem, dan malam hangat di Camping Ground. Semua pengalaman alam terbaik ada di Dante Pine Enrekang.</p>
                    <div class="flex space-x-4 mt-5 text-2xl text-gray-500">
                        <a href="#" class="hover:text-green-600"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="hover:text-green-600"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="hover:text-green-600"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="hover:text-green-600"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Navigasi</h3>
                    <ul class="space-y-2 text-gray-600 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-green-700 transition">Beranda</a></li>
                        <li><a href="{{ $homeAnchor('tentang-kami') }}" class="hover:text-green-700 transition">Tentang Kami</a></li>
                        <li><a href="{{ route('activities.index') }}" class="hover:text-green-700 transition">Aktivitas & Wahana</a></li>
                        <li><a href="{{ route('menu.index') }}" class="hover:text-green-700 transition">Kafe & Menu</a></li>
                        <li><a href="{{ $homeAnchor('galeri') }}" class="hover:text-green-700 transition">Galeri</a></li>
                        <li><a href="{{ $homeAnchor('berita') }}" class="hover:text-green-700 transition">Promo & Event</a></li>
                        <li><a href="{{ $homeAnchor('kontak') }}" class="hover:text-green-700 transition">Kontak</a></li>
                        <li><a href="{{ route('booking.form') }}" class="hover:text-green-700 transition">Booking Sekarang</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Informasi Pengunjung</h3>
                    <ul class="space-y-3 text-gray-600 text-sm">
                        <li>
                            <p class="font-semibold">Jam Operasional</p>
                            <p>Senin - Minggu | 08.00 - 22.00 WITA</p>
                        </li>
                        <li>
                            <p class="font-semibold">Lokasi</p>
                            <p>Jalur Poros Enrekang - Tana Toraja</p>
                        </li>
                        <li>
                            <p class="font-semibold">Hotline & Reservasi</p>
                            <p>+62 852-0000-0000</p>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Butuh Bantuan?</h3>
                    <p class="text-gray-600 text-sm mb-4">Tim kami siap membantu itinerary, paket grup, hingga penyewaan perlengkapan camping.</p>
                    <a href="{{ route('booking.form') }}" class="inline-flex items-center justify-center w-full bg-green-600 hover:bg-green-700 transition text-white font-semibold py-3 rounded-xl shadow-lg">
                        <i class="fas fa-comments mr-2"></i> Konsultasi & Booking
                    </a>
                    <div class="mt-4 text-sm text-gray-500 space-y-1">
                        <p><i class="fas fa-envelope mr-2"></i>info@dantepineenrekang.com</p>
                        <p><i class="fas fa-phone mr-2"></i>0821-4567-1234</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 mt-10 pt-6 flex flex-col md:flex-row items-center justify-between text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} Dante Pine Enrekang. Semua hak cipta dilindungi.</p>
                <div class="flex space-x-4 mt-3 md:mt-0">
                    <a href="#" class="hover:text-gray-800">Kebijakan Privasi</a>
                    <span class="text-gray-300">|</span>
                    <a href="#" class="hover:text-gray-800">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => mobileMenu.classList.add('hidden'));
        });

        // Toggle activities sublist
        const mobileActivitiesBtn = document.getElementById('mobile-activities-btn');
        const mobileActivitiesList = document.getElementById('mobile-activities-list');
        const mobileActivitiesIcon = document.getElementById('mobile-activities-icon');
        if (mobileActivitiesBtn && mobileActivitiesList) {
            mobileActivitiesBtn.addEventListener('click', () => {
                mobileActivitiesList.classList.toggle('hidden');
                mobileActivitiesIcon.classList.toggle('transform');
                mobileActivitiesIcon.classList.toggle('rotate-180');
            });
        }

        // Close mobile menu when clicking on any sublink (including activities)
        document.querySelectorAll('#mobile-menu .mobile-sublink').forEach(link => {
            link.addEventListener('click', () => mobileMenu.classList.add('hidden'));
        });

    </script>
</body>

</html>
