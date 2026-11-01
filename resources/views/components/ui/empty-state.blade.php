@props(['title' => 'Belum ada data', 'text' => null, 'icon' => 'fa-inbox'])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center px-6 py-12 text-center']) }}>
    <span class="inline-flex h-14 w-14 items-center justify-center rounded-box bg-base-200 text-base-content/40">
        <i class="fas {{ $icon }} text-xl"></i>
    </span>
    <p class="mt-4 text-sm font-semibold">{{ $title }}</p>
    @if ($text !== null)
        <p class="mt-1 max-w-sm text-sm text-base-content/60">{{ $text }}</p>
    @endif
    @isset($actions)
        <div class="mt-4">
            {{ $actions }}
        </div>
    @endisset
</div>
