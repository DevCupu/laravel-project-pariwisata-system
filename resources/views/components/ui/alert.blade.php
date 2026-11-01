@props([
    'tone' => 'info',
    'dismissible' => false,
])

@php
    $tones = [
        'info' => 'alert-info',
        'success' => 'alert-success',
        'warning' => 'alert-warning',
        'danger' => 'alert-error',
    ];
    $toneClass = $tones[$tone] ?? $tones['info'];
@endphp

<div {{ $attributes->merge(['class' => 'alert shadow-sm ' . $toneClass]) }} role="alert">
    {{ $slot }}
    @if ($dismissible)
        <button type="button" class="btn btn-ghost btn-sm btn-circle" aria-label="Tutup"
            onclick="this.closest('.alert')?.remove()">
            <i class="fas fa-xmark"></i>
        </button>
    @endif
</div>
