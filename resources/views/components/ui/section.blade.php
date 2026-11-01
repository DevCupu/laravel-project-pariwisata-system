@props([
    'title' => null,
    'subtitle' => null,
])

<section {{ $attributes->merge(['class' => 'rounded-box bg-base-100/60 border border-base-300/60 p-6']) }}>
    @if ($title !== null || $subtitle !== null)
        <header class="mb-5 border-b border-base-300/60 pb-3">
            @if ($title !== null)
                <h3 class="text-sm font-semibold">{{ $title }}</h3>
            @endif
            @if ($subtitle !== null)
                <p class="mt-0.5 text-xs text-base-content/60">{{ $subtitle }}</p>
            @endif
        </header>
    @endif
    {{ $slot }}
</section>
