@props([
    'label',
    'value',
    'meta' => null,
    'tone' => 'blue',
    'icon' => null,
    'href' => null,
    'active' => false,
])

@php
    $tones = [
        'blue' => 'bg-primary/10 text-primary',
        'green' => 'bg-success/10 text-success',
        'amber' => 'bg-warning/10 text-warning',
        'red' => 'bg-error/10 text-error',
        'slate' => 'bg-slate-100 text-slate-600',
        'violet' => 'bg-accent/10 text-accent',
    ];
    $toneClass = $tones[$tone] ?? $tones['blue'];

    $stateClass = $active
        ? 'border-emerald-300 bg-emerald-50/50 ring-2 ring-emerald-500/20'
        : 'border-base-300/60 bg-base-100 hover:border-emerald-200 hover:shadow-md';

    $classes = 'group flex flex-col gap-3 rounded-box border p-4 shadow-sm transition ' . $stateClass;
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if ($active) aria-current="true" @endif>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
@endif

    <div class="flex items-start justify-between gap-3">
        <p class="text-[11px] font-semibold uppercase tracking-wide text-base-content/50">{{ $label }}</p>

        @if ($icon)
            <span
                class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl transition group-hover:scale-105 {{ $toneClass }}">
                <i class="fas {{ $icon }} text-sm"></i>
            </span>
        @endif
    </div>

    <div>
        <p class="text-2xl font-bold leading-none tracking-tight">{{ $value }}</p>

        @if ($meta)
            <p class="mt-1.5 text-xs text-base-content/50">{{ $meta }}</p>
        @endif
    </div>

    {{ $slot }}

@if ($href)
    </a>
@else
    </div>
@endif
