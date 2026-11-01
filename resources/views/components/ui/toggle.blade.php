@props([
    'name',
    'label' => null,
    'checked' => false,
    'value' => '1',
    'helper' => null,
])

@php
    $inputId = $attributes->get('id', $name . '_toggle');
@endphp

<label for="{{ $inputId }}" class="flex cursor-pointer items-center justify-between gap-4">
    <div class="min-w-0">
        @if ($label !== null)
            <p class="text-sm font-medium">{{ $label }}</p>
        @endif
        @if ($helper !== null)
            <p class="text-xs text-base-content/60">{{ $helper }}</p>
        @endif
    </div>
    <input id="{{ $inputId }}" type="checkbox" name="{{ $name }}" value="{{ $value }}"
        class="toggle toggle-primary toggle-md" @checked(old($name, $checked)) {{ $attributes->except(['id', 'checked']) }}>
</label>
