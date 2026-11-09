@if ($records->hasPages())
<nav class="pagination" aria-label="Pagination">
    @if ($records->previousPageUrl())<a href="{{ $records->previousPageUrl() }}">Previous</a>@endif
    <span>Page {{ $records->currentPage() }} of {{ $records->lastPage() }}</span>
    @if ($records->nextPageUrl())<a href="{{ $records->nextPageUrl() }}">Next</a>@endif
</nav>
@endif
