@props(['title' => 'Area Berbahaya', 'description' => null])

<div {{ $attributes->merge(['class' => 'alert alert-error alert-soft shadow-sm items-start']) }}>
    <div class="w-full">
        <div class="flex items-center gap-2">
            <i class="fas fa-triangle-exclamation text-error"></i>
            @if ($title !== null)
                <h3 class="text-sm font-semibold text-error">{{ $title }}</h3>
            @endif
        </div>
        @if ($description !== null)
            <p class="mt-1 text-sm text-error/80">{{ $description }}</p>
        @endif
        <div class="mt-4">
            {{ $slot }}
        </div>
    </div>
</div>
