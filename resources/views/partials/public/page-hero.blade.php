@php
    $title = $title ?? 'Dante Pine Enrekang';
    $subtitle = $subtitle ?? null;
    $eyebrow = $eyebrow ?? null;
    $breadcrumb = $breadcrumb ?? [];
    $image = $image ?? null;
    $style = $image ? "background-image:linear-gradient(rgba(4,31,26,.72),rgba(4,31,26,.86)),url('".e($image)."');background-size:cover;background-position:center" : null;
@endphp

<header class="pn-page-hero" @if ($style) style="{{ $style }}" @endif>
    <div class="pn-container relative z-10">
        @if (! empty($breadcrumb))
            <nav class="pn-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                @foreach ($breadcrumb as $crumb)
                    <i class="fas fa-chevron-right text-[9px] opacity-60"></i>
                    @if (! empty($crumb['url']))
                        <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                    @else
                        <span class="text-white">{{ $crumb['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif

        @if ($eyebrow)
            <span class="mt-5 inline-flex items-center gap-2 rounded-full border border-emerald-400/40 bg-emerald-400/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-200">
                {{ $eyebrow }}
            </span>
        @endif

        <h1 class="mt-4 max-w-3xl text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
            {{ $title }}
        </h1>

        @if ($subtitle)
            <p class="mt-4 max-w-2xl text-base leading-relaxed text-emerald-50/80 sm:text-lg">
                {{ $subtitle }}
            </p>
        @endif
    </div>
</header>
