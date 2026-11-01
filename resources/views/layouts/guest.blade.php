@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#065f46">
    <meta name="robots" content="noindex, nofollow">

    <title>{{ $title ? $title . ' — ' . config('app.name', 'Dante Pine Enrekang') : config('app.name', 'Dante Pine Enrekang') }}</title>

    <link rel="icon" type="image/jpeg" href="{{ asset('assets-beranda/logo.jpg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,600;1,9..144,700&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="font-sans text-slate-800 antialiased">
    <div class="min-h-screen bg-slate-50 lg:grid lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)]">

        {{-- Brand panel --}}
        <aside class="relative hidden overflow-hidden bg-[#041f1a] lg:flex lg:flex-col lg:justify-between">
            <img src="{{ asset('assets-beranda/hero-1.jpg') }}" alt=""
                class="absolute inset-0 h-full w-full object-cover opacity-40" fetchpriority="high" decoding="async">
            <div class="absolute inset-0 bg-gradient-to-br from-[#052e26]/95 via-[#04251f]/90 to-[#041f1a]/95"></div>
            <div class="absolute -left-24 top-1/3 h-80 w-80 rounded-full bg-emerald-400/20 blur-3xl"></div>
            <div class="absolute -right-16 bottom-10 h-64 w-64 rounded-full bg-emerald-500/10 blur-3xl"></div>

            <div class="relative flex flex-col gap-12 p-12 xl:p-16">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 self-start"
                    aria-label="Dante Pine Enrekang">
                    <img src="{{ asset('assets-beranda/logo.jpg') }}" alt=""
                        class="h-11 w-11 rounded-2xl object-cover ring-1 ring-white/25">
                    <span class="pn-display text-2xl text-white">Dante<span
                            class="ml-1 font-semibold italic text-emerald-400">Pine</span></span>
                </a>

                <div class="max-w-md">
                    <p class="pn-eyebrow text-emerald-300/80">Dante Pine Enrekang</p>
                    <h1 class="pn-display mt-4 text-4xl leading-[1.15] text-white xl:text-[2.75rem]">
                        Kelola pengalaman wisata, dari balik layar.
                    </h1>
                    <p class="mt-5 text-sm leading-relaxed text-emerald-50/70">
                        Satu dasbor untuk mengatur booking, konten, promo, dan data pengunjung kawasan wisata Dante
                        Pine Enrekang.
                    </p>
                </div>
            </div>

            <div class="relative p-12 xl:p-16">
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <span
                            class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-400/15 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </span>
                        <span class="text-emerald-50/75"><strong class="font-semibold text-white">Booking
                                terkontrol</strong> — pantau status dan jadwal kunjungan.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span
                            class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-400/15 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </span>
                        <span class="text-emerald-50/75"><strong class="font-semibold text-white">Konten &
                                promo</strong> — perbarui aktivitas, menu, dan event.</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span
                            class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-400/15 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </span>
                        <span class="text-emerald-50/75"><strong class="font-semibold text-white">Data
                                pengunjung</strong> — simpan dan tindak lanjuti setiap pesanan.</span>
                    </li>
                </ul>

                <p class="mt-10 text-xs text-emerald-100/40">&copy; {{ date('Y') }} Dante Pine Enrekang. Semua hak
                    dilindungi.</p>
            </div>
        </aside>

        {{-- Form panel --}}
        <main class="flex min-h-screen flex-col justify-center px-5 py-10 sm:px-8 lg:px-12">
            <div class="mx-auto w-full max-w-md">
                <div class="mb-8 flex items-center justify-between lg:hidden">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="Dante Pine Enrekang">
                        <img src="{{ asset('assets-beranda/logo.jpg') }}" alt=""
                            class="h-10 w-10 rounded-xl object-cover">
                        <span class="pn-display text-xl text-slate-900">Dante<span
                                class="ml-1 font-semibold italic text-emerald-600">Pine</span></span>
                    </a>
                    <a href="{{ route('home') }}"
                        class="text-sm font-semibold text-emerald-700 transition hover:text-emerald-800">Beranda</a>
                </div>

                <div class="pn-card p-6 sm:p-8">
                    {{ $slot }}
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-1.5 font-semibold text-slate-600 transition hover:text-emerald-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        Kembali ke beranda
                    </a>
                    <span>&copy; {{ date('Y') }} Dante Pine Enrekang</span>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toggle tampil/sembunyi kata sandi
        (function() {
            document.querySelectorAll('[data-password-toggle]').forEach(function(button) {
                var input = document.getElementById(button.getAttribute('data-password-toggle'));
                if (!input) return;

                var eyeOpen = button.querySelector('[data-eye-open]');
                var eyeClosed = button.querySelector('[data-eye-closed]');

                button.addEventListener('click', function() {
                    var show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    button.setAttribute('aria-pressed', show ? 'true' : 'false');
                    button.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' :
                        'Tampilkan kata sandi');
                    if (eyeOpen) eyeOpen.classList.toggle('hidden', show);
                    if (eyeClosed) eyeClosed.classList.toggle('hidden', !show);
                    input.focus({ preventScroll: true });
                });
            });
        })();
    </script>
    @stack('scripts')
</body>

</html>
