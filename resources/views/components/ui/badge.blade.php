@props([
    'tone' => 'neutral',
    'dot' => false,
])

@php
    $tones = [
        'neutral' => 'badge-neutral badge-soft',
        'success' => 'badge-success badge-soft',
        'warning' => 'badge-warning badge-soft',
        'danger' => 'badge-error badge-soft',
        'info' => 'badge-info badge-soft',
    ];
    $toneClass = $tones[$tone] ?? $tones['neutral'];
@endphp

<span {{ $attributes->merge(['class' => "badge badge-sm gap-1.5 font-semibold {$toneClass}"]) }}>
    @if ($dot)
        <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
    @endif
    {{ $slot }}
</span>
