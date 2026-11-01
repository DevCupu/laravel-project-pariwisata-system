@props([
    'title' => null,
    'subtitle' => null,
    'padded' => true,
])

<div {{ $attributes->merge(['class' => 'card bg-base-100 shadow-sm border border-base-300/60']) }}>
    @if ($title !== null || isset($actions))
        <div class="flex flex-col gap-3 border-b border-base-300/60 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                @if ($title !== null)
                    <h3 class="text-base font-semibold tracking-tight">{{ $title }}</h3>
                @endif
                @if ($subtitle !== null)
                    <p class="mt-0.5 text-sm text-base-content/60">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex flex-wrap items-center gap-2">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif

    @if ($padded)
        <div class="px-6 py-5">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</div>
