@extends('layouts.public')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Aktivitas & Wahana')

@section('content')
    <section class="relative overflow-hidden bg-gradient-to-br from-green-900 via-emerald-800 to-slate-900 text-white">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1600&q=60')] bg-cover bg-center opacity-20"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-24 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.4em] text-emerald-200">Aktivitas Resmi Dante Pine</p>
            <h1 class="mt-4 text-4xl font-bold md:text-5xl">Pilih pengalaman alam terbaik untuk itinerary kamu</h1>
            <p class="mt-4 text-lg text-emerald-100">Setiap aktivitas dikurasi dengan standar keamanan dan storytelling lokal. Booking langsung atau gabungkan dengan paket camping.</p>
        </div>
    </section>

    @if ($featuredActivities->isNotEmpty())
        <section class="mx-auto max-w-6xl px-4 py-16">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.4em] text-emerald-500">Rekomendasi Tim</p>
                    <h2 class="text-3xl font-semibold text-gray-900">Highlight Minggu Ini</h2>
                </div>
                <a href="#kategori" class="text-sm font-semibold text-emerald-600 hover:text-emerald-800">Lihat semua kategori →</a>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($featuredActivities as $activity)
                    <article class="card-hover relative overflow-hidden rounded-3xl border border-emerald-100 bg-white shadow-lg">
                        @if ($activity->hero_image_path)
                            <img src="{{ Storage::url($activity->hero_image_path) }}" alt="{{ $activity->title }}"
                                class="h-48 w-full object-cover">
                        @else
                            <div class="h-48 w-full bg-gradient-to-br from-emerald-100 via-white to-white"></div>
                        @endif
                        <div class="space-y-3 p-5">
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $activity->category)) }}</span>
                            <h3 class="text-xl font-semibold text-gray-900">{{ $activity->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $activity->short_description }}</p>
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span><i class="fas fa-clock mr-2 text-emerald-500"></i>{{ $activity->duration_minutes ? $activity->duration_minutes . ' menit' : 'Fleksibel' }}</span>
                                <span><i class="fas fa-ticket mr-2 text-emerald-500"></i>{{ $activity->price_from ? 'Mulai Rp ' . number_format($activity->price_from, 0, ',', '.') : 'Hubungi kami' }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section id="kategori" class="bg-gradient-to-b from-white to-emerald-50 py-16">
        <div class="mx-auto max-w-6xl px-4">
            @forelse ($groupedActivities as $category => $activities)
                <div class="mb-12 rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="mb-6 flex flex-wrap items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.4em] text-emerald-500">Kategori</p>
                            <h3 class="text-2xl font-semibold text-gray-900">{{ \Illuminate\Support\Str::title(str_replace('-', ' ', $category)) }}</h3>
                        </div>
                        <span class="text-sm text-gray-500">{{ $activities->count() }} aktivitas</span>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach ($activities as $activity)
                            <article class="card-hover flex flex-col justify-between rounded-2xl border border-gray-100 p-5 shadow-sm">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-3 text-sm text-gray-500">
                                        <span class="inline-flex rounded-full bg-gray-100 px-3 py-0.5 text-xs font-semibold">
                                            {{ $activity->duration_minutes ? $activity->duration_minutes . ' menit' : 'Durasi fleksibel' }}
                                        </span>
                                        @if ($activity->min_age)
                                            <span><i class="fas fa-user text-emerald-500 mr-1"></i>Min {{ $activity->min_age }} th</span>
                                        @endif
                                    </div>
                                    <h4 class="text-xl font-semibold text-gray-900">{{ $activity->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $activity->short_description ?? \Illuminate\Support\Str::limit($activity->description, 120) }}</p>
                                </div>
                                <div class="mt-4 flex items-center justify-between text-sm font-semibold text-emerald-600">
                                    <span>{{ $activity->price_from ? 'Mulai Rp ' . number_format($activity->price_from, 0, ',', '.') : 'Harga custom' }}</span>
                                    <a href="{{ route('booking.form', ['facility_category' => $activity->category]) }}"
                                        class="text-emerald-600 hover:text-emerald-800">Booking →</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-gray-200 bg-white p-12 text-center text-gray-500">
                    Belum ada aktivitas yang dipublikasikan.
                </div>
            @endforelse
        </div>
    </section>
@endsection
