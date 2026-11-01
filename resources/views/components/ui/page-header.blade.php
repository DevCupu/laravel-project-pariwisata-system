@props([
    'title' => null,
    'subtitle' => null,
    'backHref' => null,
    'backText' => 'Kembali',
])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between']) }}>
    <div class="min-w-0">
        @if ($title !== null)
            <h2 class="truncate text-lg font-bold tracking-tight">{{ $title }}</h2>
        @endif
        @if ($subtitle !== null)
            <p class="mt-0.5 text-sm text-base-content/60">{{ $subtitle }}</p>
        @endif
        {{ $slot }}
    </div>
    @if ($backHref !== null)
        <a href="{{ $backHref }}" class="btn btn-ghost btn-sm gap-2">
            <i class="fas fa-arrow-left text-xs"></i>
            {{ $backText }}
        </a>
    @endif
</div>
