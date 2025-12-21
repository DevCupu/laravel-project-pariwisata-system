@extends('layouts.public')

@section('title', 'Promo & Event')

@section('content')
    <div class="max-w-7xl mx-auto px-4">
        <div class="py-12">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Promo & Event</h1>
                    <p class="text-gray-600 mt-1">Daftar event, promo, dan kegiatan yang sedang atau akan berlangsung.</p>
                </div>
                <div class="flex items-center gap-3">
                    <form method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        <a href="{{ route('events.index') }}" class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-semibold {{ request('type') === null ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700' }}">Semua</a>
                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['type' => 'event'])) }}" class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-semibold {{ request('type') === 'event' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700' }}">Event</a>
                        <a href="{{ route('events.index', array_merge(request()->except('page'), ['type' => 'promo'])) }}" class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-semibold {{ request('type') === 'promo' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700' }}">Promo</a>
                    </form>
                    <form method="GET" class="w-60">
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari event"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 pl-10 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </form>
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($events as $event)
                    <article class="rounded-2xl overflow-hidden border border-gray-200 bg-white shadow-sm">
                        <a href="{{ route('events.show', $event->slug) }}">
                            <div class="h-44 bg-gray-100">
                                <img src="{{ $event->image_url ?? asset('assets-beranda/home.jpg') }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $event->title }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $event->start_date ? $event->start_date->format('d M Y') : '' }}@if($event->end_date) - {{ $event->end_date->format('d M Y') }}@endif</p>
                                <p class="text-sm text-gray-600 mt-3">{{ Str::limit($event->excerpt ?? strip_tags($event->content), 120) }}</p>
                            </div>
                        </a>
                    </article>
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-500">Belum ada event yang tersedia.</div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $events->links() }}
            </div>
        </div>
    </div>
@endsection
