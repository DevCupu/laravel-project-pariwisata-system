@props([
    'label',
    'name',
    'hint' => null,
    'accept' => null,
    'required' => false,
])

@php
    $inputError = $errors->first($name);
    $inputId = $attributes->get('id', $name);
    $errorClass = $inputError ? 'file-input-error' : '';
@endphp

<div class="form-control w-full">
    <label for="{{ $inputId }}" class="label py-1">
        <span class="label-text font-semibold text-sm">
            {{ $label }}@if ($required)<span class="text-error"> *</span>@endif
        </span>
    </label>

    <input
        type="file"
        name="{{ $name }}"
        id="{{ $inputId }}"
        @if ($accept !== null) accept="{{ $accept }}" @endif
        @if ($required) required @endif
        {{ $attributes->except('id')->merge(['class' => 'file-input file-input-bordered w-full ' . $errorClass]) }}
    >

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
