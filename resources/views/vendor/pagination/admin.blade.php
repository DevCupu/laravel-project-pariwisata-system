@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="space-y-3">
        <div class="flex flex-col gap-2 text-sm text-gray-600 sm:flex-row sm:items-center sm:justify-between">
            <p>
                Menampilkan
                <span class="font-semibold text-gray-900">
                    {{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }}
                </span>
                dari
                <span class="font-semibold text-gray-900">{{ $paginator->total() }}</span>
                data
            </p>
            <div class="inline-flex rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                @if ($paginator->onFirstPage())
                    <span class="px-4 py-2 text-gray-400">&larr; Sebelumnya</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        class="px-4 py-2 text-green-700 font-semibold hover:bg-green-50">&larr; Sebelumnya</a>
                @endif

                <span class="px-4 py-2 text-gray-500 border-l border-gray-100">
                    Halaman {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                </span>

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        class="px-4 py-2 text-green-700 font-semibold border-l border-gray-100 hover:bg-green-50">Berikutnya &rarr;</a>
                @else
                    <span class="px-4 py-2 text-gray-400 border-l border-gray-100">Berikutnya &rarr;</span>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 text-sm">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1 text-gray-400">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="px-3 py-1 rounded-lg bg-green-600 text-white font-semibold">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
    </nav>
@endif
