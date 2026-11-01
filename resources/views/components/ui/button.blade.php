@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'iconOnly' => false,
])

@php
    $variants = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-outline btn-neutral',
        'ghost' => 'btn-ghost',
        'soft-blue' => 'btn-info btn-outline',
        'success' => 'btn-success btn-outline',
        'warning' => 'btn-warning btn-outline',
        'danger' => 'btn-error btn-outline',
    ];
    $sizes = [
        'xs' => 'btn-xs',
        'sm' => 'btn-sm',
        'md' => '',
        'lg' => 'btn-lg',
    ];
    $base = 'btn gap-2 font-semibold';
    $class = trim($base . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary']));
    if ($iconOnly) {
        $class = trim($class . ' btn-circle px-0');
    }
@endphp

@if ($href !== null)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $class]) }}>{{ $slot }}</button>
@endif
