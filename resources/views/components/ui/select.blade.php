@props([
    'label',
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
])

@php
    $inputError = $errors->first($name);
    $inputId = $attributes->get('id', $name);
    $current = old($name, $selected);
    $errorClass = $inputError ? 'select-error' : '';
@endphp

<div class="form-control w-full">
    <label for="{{ $inputId }}" class="label py-1">
        <span class="label-text font-semibold text-sm">
            {{ $label }}@if ($required)<span class="text-error"> *</span>@endif
        </span>
    </label>

    <select
        name="{{ $name }}"
        id="{{ $inputId }}"
        @if ($required) required @endif
        {{ $attributes->except('id')->merge(['class' => 'select select-bordered w-full ' . $errorClass]) }}
    >
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $current === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>

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
