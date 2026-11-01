@extends('layouts.public')

@section('title', 'Promo & Event')
@section('meta_description', 'Ikuti promo dan event terbaru di Dante Pine Enrekang: festival, diskon paket, dan kegiatan komunitas.')

@section('content')
    @include('partials.public.page-hero', [
        'title' => 'Promo & Event',
        'subtitle' => 'Daftar event, promo, dan kegiatan yang sedang atau akan berlangsung di Dante Pine Enrekang.',
        'eyebrow' => 'Agenda Dante Pine',
        'breadcrumb' => [['label' => 'Promo & Event']],
    ])

    <section class="pn-section pn-section--tint">
        <div class="pn-container">
            <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('events.index') }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request('type') === null ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50' }}">Semua</a>
                    <a href="{{ route('events.index', array_merge(request()->except('page'), ['type' => 'event'])) }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request('type') === 'event' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50' }}">Event</a>
                    <a href="{{ route('events.index', array_merge(request()->except('page'), ['type' => 'promo'])) }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request('type') === 'promo' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-700 ring-1 ring-slate-200 hover:bg-slate-50' }}">Promo</a>
                </div>

                <form method="GET" class="w-full lg:w-72">
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari event"
                            class="pn-field pl-10" aria-label="Cari event">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400" aria-hidden="true"></i>
                    </div>
                </form>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($events as $event)
                    <article class="pn-card pn-card-hover flex flex-col">
                        <a href="{{ route('events.show', $event->slug) }}" class="flex flex-1 flex-col">
                            <div class="h-44 overflow-hidden bg-slate-100">
                                <img src="{{ $event->image_url ?? asset('assets-beranda/home.jpg') }}" alt="{{ $event->title }}" loading="lazy" decoding="async" class="h-full w-full object-cover">
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <span class="inline-flex w-fit items-center rounded-full bg-emerald-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.15em] text-emerald-700">
                                    {{ ucfirst($event->type) }}
                                </span>
                                <h3 class="mt-3 text-lg font-bold text-slate-900">{{ $event->title }}</h3>
                                <p class="mt-1 text-xs font-medium text-slate-500">
                                    <i class="far fa-calendar mr-1 text-emerald-500" aria-hidden="true"></i>{{ $event->start_date ? $event->start_date->format('d M Y') : '' }}@if ($event->end_date) &ndash; {{ $event->end_date->format('d M Y') }}@endif
                                </p>
                                <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">{{ Str::limit($event->excerpt ?? strip_tags($event->content), 120) }}</p>
                            </div>
                        </article>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-500">Belum ada event yang tersedia.</div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $events->links() }}
            </div>
        </div>
    </section>
@endsection
