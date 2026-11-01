@extends('layouts.public')

@section('title', $event->title)
@section('meta_description', Str::limit(strip_tags($event->excerpt ?? $event->content), 150))
@if ($event->image_url)
    @section('og_image', $event->image_url)
@endif

@section('content')
    @include('partials.public.page-hero', [
        'title' => $event->title,
        'subtitle' => $event->start_date ? ($event->start_date->format('d M Y') . ($event->end_date ? ' - ' . $event->end_date->format('d M Y') : '')) : null,
        'eyebrow' => $event->type === 'promo' ? 'Promo' : 'Event',
        'image' => $event->image_url,
        'breadcrumb' => [
            ['label' => 'Promo & Event', 'url' => route('events.index')],
            ['label' => $event->title],
        ],
    ])

    <section class="pn-section pn-section--tint">
        <div class="pn-container">
            <article class="pn-card mx-auto max-w-3xl p-6 sm:p-10">
                <div class="prose max-w-none text-slate-700">{!! $event->content !!}</div>

                <div class="mt-10 border-t border-slate-100 pt-6">
                    <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition hover:text-emerald-800">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Kembali ke daftar
                    </a>
                </div>
            </article>
        </div>
    </section>
@endsection
