@if ($paginator->hasPages())
    <nav class="flex-center gap-sm mt-4" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="btn btn--ghost btn--sm" style="opacity:.5;">← Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="btn btn--ghost btn--sm">← Prev</a>
        @endif

        <span class="text-muted" style="font-size:.85rem;">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="btn btn--ghost btn--sm">Next →</a>
        @else
            <span class="btn btn--ghost btn--sm" style="opacity:.5;">Next →</span>
        @endif
    </nav>
@endif
