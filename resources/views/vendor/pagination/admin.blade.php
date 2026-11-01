@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="space-y-3">
        <div class="flex flex-col gap-2 text-sm sm:flex-row sm:items-center sm:justify-between">
            <p class="text-base-content/70">
                Menampilkan
                <span class="font-semibold text-base-content">
                    {{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }}
                </span>
                dari
                <span class="font-semibold text-base-content">{{ $paginator->total() }}</span>
                data
            </p>
            <div class="join">
                @if ($paginator->onFirstPage())
                    <span class="join-item btn btn-sm btn-disabled">&larr; Sebelumnya</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="join-item btn btn-sm btn-outline">&larr; Sebelumnya</a>
                @endif

                <span class="join-item btn btn-sm btn-ghost no-animation pointer-events-none">
                    Halaman {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                </span>

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="join-item btn btn-sm btn-outline">Berikutnya &rarr;</a>
                @else
                    <span class="join-item btn btn-sm btn-disabled">Berikutnya &rarr;</span>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 text-sm">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1 text-base-content/40">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <a aria-current="page" href="{{ $url }}"
                                class="btn btn-sm btn-primary btn-circle w-9 h-9 p-0 min-h-0">
                                {{ $page }}
                            </a>
                        @else
                            <a href="{{ $url }}"
                                class="btn btn-sm btn-ghost btn-circle w-9 h-9 p-0 min-h-0">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
    </nav>
@endif
