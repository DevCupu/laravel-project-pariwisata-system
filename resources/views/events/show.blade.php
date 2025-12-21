@extends('layouts.public')

@section('title', $event->title)

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-12">
        <article class="rounded-2xl overflow-hidden border border-gray-200 bg-white shadow-sm">
            @if($event->image_url)
                <div class="h-72 bg-gray-100">
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="p-6">
                <h1 class="text-2xl font-bold">{{ $event->title }}</h1>
                <p class="text-sm text-gray-500 mt-2">{{ $event->start_date ? $event->start_date->format('d M Y') : '' }}@if($event->end_date) - {{ $event->end_date->format('d M Y') }}@endif</p>

                <div class="prose mt-6 text-gray-700">{!! $event->content !!}</div>

                <div class="mt-6">
                    <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke daftar
                    </a>
                </div>
            </div>
        </article>
    </div>
@endsection
