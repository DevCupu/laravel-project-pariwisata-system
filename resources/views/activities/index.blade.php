@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Aktivitas & Wahana')
@section('meta_description', 'Jelajahi aktivitas dan wahana resmi di Dante Pine Enrekang: trekking, camping, offroad, dan pengalaman alam untuk seluruh keluarga.')

@section('content')
    @include('partials.public.page-hero', [
        'title' => 'Pilih pengalaman alam terbaik untuk itinerary kamu',
        'subtitle' => 'Setiap aktivitas dikurasi dengan standar keamanan dan storytelling lokal. Booking langsung atau gabungkan dengan paket camping.',
        'eyebrow' => 'Aktivitas Resmi Dante Pine',
        'breadcrumb' => [['label' => 'Aktivitas & Wahana']],
    ])

    @if ($featuredActivities->isNotEmpty())
        <section class="pn-section bg-white">
            <div class="pn-container">
                <header class="pn-reveal flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span class="pn-eyebrow text-emerald-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Rekomendasi Tim
                        </span>
                        <h2 class="pn-heading mt-4 text-3xl sm:text-4xl">Highlight Minggu Ini</h2>
                    </div>
                    <a href="#kategori" class="group inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition hover:text-emerald-800">
                        Lihat semua kategori
                        <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1" aria-hidden="true"></i>
                    </a>
                </header>

                <div class="mt-12 grid gap-6 md:grid-cols-3">
                    @foreach ($featuredActivities as $activity)
                        <article class="pn-card pn-card-hover flex flex-col">
                            @if ($activity->hero_image_path)
                                <img src="{{ Storage::url($activity->hero_image_path) }}" alt="{{ $activity->title }}" loading="lazy" decoding="async"
                                    class="h-48 w-full object-cover">
                            @else
                                <div class="flex h-48 w-full items-center justify-center bg-gradient-to-br from-emerald-600 to-slate-800">
                                    <i class="fas fa-mountain-sun text-5xl text-white/70" aria-hidden="true"></i>
                                </div>
                            @endif
                            <div class="flex flex-1 flex-col p-6">
                                <span class="inline-flex w-fit items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    {{ \Illuminate\Support\Str::title(str_replace('-', ' ', $activity->category)) }}
                                </span>
                                <h3 class="mt-3 text-xl font-bold text-slate-900">{{ $activity->title }}</h3>
                                <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">{{ $activity->short_description }}</p>
                                <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 border-t border-slate-100 pt-4 text-xs font-medium text-slate-500">
                                    <span><i class="fas fa-clock mr-1.5 text-emerald-500" aria-hidden="true"></i>{{ $activity->duration_minutes ? $activity->duration_minutes . ' menit' : 'Fleksibel' }}</span>
                                    <span><i class="fas fa-ticket mr-1.5 text-emerald-500" aria-hidden="true"></i>{{ $activity->price_from ? 'Mulai Rp ' . number_format($activity->price_from, 0, ',', '.') : 'Hubungi kami' }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="kategori" class="pn-section pn-section--tint scroll-mt-24">
        <div class="pn-container">
            @forelse ($groupedActivities as $category => $activities)
                <div class="pn-reveal mb-12">
                    <div class="mb-6 flex flex-wrap items-end justify-between gap-3">
                        <div>
                            <span class="pn-eyebrow text-emerald-600">Kategori</span>
                            <h3 class="pn-heading mt-3 text-2xl sm:text-3xl">{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $category)) }}</h3>
                        </div>
                        <span class="rounded-full bg-white px-4 py-1.5 text-sm font-semibold text-slate-600 ring-1 ring-slate-200">{{ $activities->count() }} aktivitas</span>
                    </div>
                    <div class="grid gap-5 md:grid-cols-2">
                        @foreach ($activities as $activity)
                            <article class="pn-card pn-card-hover flex flex-col justify-between p-6">
                                <div>
                                    <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500">
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-0.5 text-xs font-semibold text-slate-600">
                                            {{ $activity->duration_minutes ? $activity->duration_minutes . ' menit' : 'Durasi fleksibel' }}
                                        </span>
                                        @if ($activity->min_age)
                                            <span><i class="fas fa-user mr-1 text-emerald-500" aria-hidden="true"></i>Min {{ $activity->min_age }} th</span>
                                        @endif
                                    </div>
                                    <h4 class="mt-4 text-xl font-bold text-slate-900">{{ $activity->title }}</h4>
                                    <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $activity->short_description ?? \Illuminate\Support\Str::limit($activity->description, 120) }}</p>
                                </div>
                                <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
                                    <span class="text-sm font-semibold text-slate-900">{{ $activity->price_from ? 'Mulai Rp ' . number_format($activity->price_from, 0, ',', '.') : 'Harga custom' }}</span>
                                    <a href="{{ route('booking.form', ['facility' => 'activity-' . $activity->slug]) }}"
                                        class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 transition hover:text-emerald-800">
                                        Booking <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500">
                    Belum ada aktivitas yang dipublikasikan.
                </div>
            @endforelse
        </div>
    </section>
@endsection
