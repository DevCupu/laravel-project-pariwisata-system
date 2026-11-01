@props(['variant' => 'info', 'title' => null])

@php
    $variants = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
        'info' => 'border-sky-200 bg-sky-50 text-sky-800',
        'error' => 'border-rose-200 bg-rose-50 text-rose-800',
    ];
    $icons = [
        'success' => 'm4.5 12.75 6 6 9-13.5',
        'info' => 'M12 9v3.75m0 3.75h.008M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        'error' => 'M12 9v3.75m0 3.75h.008M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-xl border px-4 py-3 text-sm ' . ($variants[$variant] ?? $variants['info'])]) }} role="alert">
    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$variant] ?? $icons['info'] }}" />
    </svg>

    <div class="leading-relaxed">
        @if ($title)
            <p class="font-semibold">{{ $title }}</p>
        @endif
        <div @class(['mt-0.5' => $title])>{{ $slot }}</div>
    </div>
</div>
