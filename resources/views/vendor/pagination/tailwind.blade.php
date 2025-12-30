@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginacja" class="pagination-nav">
        <div class="pagination-wrapper">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn pagination-btn-disabled">
                    &laquo; Poprzednia
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn">
                    &laquo; Poprzednia
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="pagination-pages">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="pagination-dots">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-page pagination-page-active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-page">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn">
                    Nastepna &raquo;
                </a>
            @else
                <span class="pagination-btn pagination-btn-disabled">
                    Nastepna &raquo;
                </span>
            @endif
        </div>

        <div class="pagination-info">
            Wyniki {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} z {{ $paginator->total() }}
        </div>
    </nav>
@endif
