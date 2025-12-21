@extends('layouts.public')

@section('title', 'Beranda')

@section('content')

    <!-- Hero Section -->
    <section class="relative h-screen -mt-16 flex items-center justify-center"
        style="background-image: url('{{ asset('assets-beranda/home.jpg') }}'); background-size: cover; background-position: center;"
        aria-label="Hero Section">
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white px-4">
            <div class="max-w-3xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">Dante Pine Enrekang</h1>
                <p class="text-lg md:text-xl mb-8 text-gray-200 font-medium drop-shadow">Eksplor hutan pinus, kabut pagi, dan panorama pegunungan Enrekang yang memukau</p>
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                                    <a href="#"
                                        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-full text-lg font-semibold shadow-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-green-300">
                                        Mulai Jelajahi
                                    </a>
                    <a href="#"
                        class="bg-white bg-opacity-20 hover:bg-opacity-40 text-white hover:text-green-700 px-8 py-3 rounded-full text-lg font-semibold shadow-lg border border-white transition duration-300 focus:outline-none focus:ring-2 focus:ring-white">
                        <i class="fas fa-play-circle mr-2"></i>Lihat Video
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if ($aboutSections->isNotEmpty())
        <section class="relative overflow-hidden bg-gradient-to-b from-white to-emerald-50/40 py-20" id="tentang-kami">
            <div class="absolute inset-x-0 top-10 mx-auto h-40 w-40 rounded-full bg-emerald-100 blur-[140px]"></div>
            <div class="relative mx-auto flex max-w-6xl flex-col gap-14 px-4">
                <header class="text-center space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-emerald-600">Tentang Kami</p>
                    <h2 class="text-3xl md:text-4xl font-semibold text-gray-900">Kenali nilai & cerita Dante Pine</h2>
                    <p class="text-gray-600 max-w-3xl mx-auto">Setiap blok menggambarkan pilar penting layanan kami, lengkap dengan gambaran visual untuk membantu pengunjung memahami konteks secara cepat.</p>
                </header>

                @foreach ($aboutSections as $index => $section)
                    <article id="{{ $section->slug }}"
                        class="grid items-center gap-8 rounded-[32px] bg-white/90 p-6 shadow-lg ring-1 ring-gray-100 lg:grid-cols-{{ $section->image_path ? '2' : '1' }} lg:p-10">
                        <div class="space-y-4 {{ $section->image_path && $index % 2 === 1 ? 'lg:order-last' : '' }}">
                            <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-widest text-emerald-600">
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-emerald-700">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>Blok {{ $index + 1 }}
                                </span>
                                <span class="text-gray-400">#{{ $section->slug }}</span>
                            </div>
                            <h3 class="text-3xl font-semibold text-gray-900">{{ $section->title }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $section->content }}</p>
                            <div class="flex flex-wrap gap-2 text-xs text-gray-500">
                                <span class="rounded-full bg-gray-100 px-3 py-1">Urutan tampil: {{ $section->display_order }}</span>
                                <span class="rounded-full bg-gray-100 px-3 py-1">Status: {{ $section->is_active ? 'Aktif' : 'Disembunyikan' }}</span>
                            </div>
                        </div>

                        @if ($section->image_path)
                            <div class="relative overflow-hidden rounded-3xl border border-gray-100 shadow-lg {{ $index % 2 === 1 ? 'lg:order-first' : '' }}">
                                <img src="{{ $section->image_url ?? Storage::url($section->image_path) }}" alt="{{ $section->title }}"
                                    class="h-full w-full max-h-[360px] object-cover">
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-4 text-sm text-white">
                                    <p class="font-semibold">{{ $section->title }}</p>
                                    <p class="text-white/80">Visual pendukung</p>
                                </div>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    @if(isset($photoSpots) && $photoSpots->isNotEmpty())
        <section id="spot-foto" class="bg-gray-900 py-20 text-white">
            <div class="max-w-6xl mx-auto px-4 space-y-10">
                <header class="text-center space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-green-300">Spot Foto</p>
                    <h2 class="text-3xl md:text-4xl font-semibold">Area favorit pemburu konten</h2>
                    <p class="text-gray-300 max-w-3xl mx-auto">Semua spot ini tidak perlu reservasi. Tinggal datang, jaga etika lokasi, dan nikmati view terbaik.</p>
                </header>

                <div class="grid gap-8 md:grid-cols-2">
                    @foreach ($photoSpots as $spot)
                        <article class="rounded-3xl overflow-hidden bg-gray-800 border border-white/10 shadow-2xl">
                            <div class="relative h-64">
                                <img src="{{ $spot->image_url ?? ($spot->image_path ? Storage::url($spot->image_path) : asset('assets-beranda/home.jpg')) }}" alt="{{ $spot->title }}" class="h-full w-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="flex flex-wrap items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] text-green-200">
                                        <span>{{ $spot->location_text ?: 'Lokasi tersedia' }}</span>
                                        @if($spot->best_time)
                                            <span class="text-white/70">{{ $spot->best_time }}</span>
                                        @endif
                                    </div>
                                    <h3 class="text-2xl font-bold">{{ $spot->title }}</h3>
                                </div>
                            </div>
                            <div class="p-6 space-y-4">
                                <p class="text-gray-200 leading-relaxed">{{ $spot->description }}</p>
                                @if($spot->tips)
                                    <div class="rounded-2xl bg-gray-900/60 border border-white/10 p-4 text-sm text-gray-300">
                                        <p class="font-semibold text-white mb-1">Tips Fotografer</p>
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

    @if(isset($generalFacilities) && $generalFacilities->isNotEmpty())
        <section id="fasilitas-umum" class="py-20 bg-gradient-to-b from-white via-green-50/40 to-white">
            <div class="max-w-6xl mx-auto px-4 space-y-12">
                <header class="text-center space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-emerald-600">Fasilitas Umum</p>
                    <h2 class="text-3xl md:text-4xl font-semibold text-gray-900">Semua kebutuhan dasar sudah kami siapkan</h2>
                    <p class="text-gray-600 max-w-3xl mx-auto">Area yang kami sebut di bawah gratis untuk semua pengunjung, tanpa booking.</p>
                </header>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($generalFacilities as $facility)
                        <article class="rounded-3xl bg-white shadow-lg border border-emerald-50 p-6 flex flex-col gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl">
                                <i class="{{ $facility->icon }}"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">{{ $facility->name }}</h3>
                                <p class="text-gray-600 text-sm mt-2">{{ $facility->description }}</p>
                            </div>
                            @if($facility->availability_note)
                                <p class="text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-full px-3 py-2">{{ $facility->availability_note }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(isset($campingPackages) && $campingPackages->isNotEmpty())
        <section id="paket-camping" class="py-20 bg-slate-900">
            <div class="max-w-6xl mx-auto px-4 space-y-12 text-white">
                <header class="text-center space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.5em] text-lime-300">Paket Camping</p>
                    <h2 class="text-3xl md:text-4xl font-semibold">Pilih paket sesuai gaya liburanmu</h2>
                    <p class="text-slate-300 max-w-3xl mx-auto">Semua paket bisa dikustom sesuai kebutuhan. Harga sudah termasuk pajak lokasi.</p>
                </header>

                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($campingPackages as $package)
                        <article class="rounded-3xl bg-slate-800 border border-white/10 overflow-hidden shadow-2xl">
                            <div class="relative h-52 bg-gray-700">
                                <img src="{{ $package->image_url ?? ($package->image_path ? Storage::url($package->image_path) : asset('assets-beranda/home.jpg')) }}" alt="{{ $package->name }}" class="h-full w-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute left-4 bottom-4 text-white">
                                    <p class="text-sm uppercase tracking-[0.3em] text-lime-200">{{ $package->tagline ?: 'Paket Camping' }}</p>
                                    <h3 class="text-2xl font-semibold">{{ $package->name }}</h3>
                                </div>
                            </div>

                            <div class="p-6 flex flex-col gap-4">
                                <div class="flex items-baseline gap-2">
                                    @if($package->price)
                                        <p class="text-3xl font-bold text-white">Rp{{ number_format($package->price, 0, ',', '.') }}</p>
                                    @endif
                                    <span class="text-sm text-slate-300">{{ $package->nights }} malam · {{ $package->capacity }} orang</span>
                                </div>

                                <p class="text-slate-200 flex-1">{{ $package->description }}</p>
                                @php($includes = $package->includes ?? [])
                                @if(!empty($includes))
                                    <ul class="space-y-2 text-sm text-slate-200">
                                        @foreach ($includes as $item)
                                            <li class="flex items-start gap-2">
                                                <i class="fas fa-check text-lime-300 mt-1"></i>
                                                <span>{{ $item }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                <div>
                                    <a href="{{ route('booking.form') }}" class="inline-flex items-center justify-center w-full rounded-2xl bg-lime-400/90 text-slate-900 font-semibold py-3 hover:bg-lime-300 transition">
                                        Booking paket ini
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
