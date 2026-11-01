@props([
    'label',
    'name',
    'value' => null,
    'rows' => 4,
    'hint' => null,
    'required' => false,
    'placeholder' => null,
])

@php
    $inputError = $errors->first($name);
    $inputId = $attributes->get('id', $name);
    $errorClass = $inputError ? 'textarea-error' : '';
@endphp

<div class="form-control w-full">
    <label for="{{ $inputId }}" class="label py-1">
        <span class="label-text font-semibold text-sm">
            {{ $label }}@if ($required)<span class="text-error"> *</span>@endif
        </span>
    </label>

    <textarea
        name="{{ $name }}"
        id="{{ $inputId }}"
        rows="{{ $rows }}"
        @if ($required) required @endif
        @if ($placeholder !== null) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->except('id')->merge(['class' => 'textarea textarea-bordered w-full ' . $errorClass]) }}
    >{{ old($name, $value) }}</textarea>

    @if ($inputError)
        <label class="label py-1">
            <span class="label-text-alt text-error font-medium">{{ $inputError }}</span>
        </label>
    @elseif ($hint !== null)
        <label class="label py-1">
            <span class="label-text-alt text-base-content/50">{{ $hint }}</span>
        </label>
    @endif
</div>
