@if ($paginator->hasPages())
@php
    $current = $paginator->currentPage();
    $last    = $paginator->lastPage();
@endphp
<nav aria-label="Page navigation">

    {{-- ── Mobile (<768px): sliding window (prev,current,next) + last 2 always visible ── --}}
    <ul class="pagination justify-content-center d-flex d-md-none flex-wrap gap-1 mb-0">
        @php
            // 3-page window centred on current page
            $window   = collect(range(max(1, $current - 1), min($last, $current + 1)));
            $endPages = collect(range(max(1, $last - 1), $last));
            $mItems   = collect();

            $windowOverlapsEnd = $window->last() >= $endPages->first();

            if ($windowOverlapsEnd) {
                // Window has reached the last pages — merge them and show a prefix instead
                $merged      = $window->merge($endPages)->unique()->sort()->values();
                $prefixCount = max(0, 5 - $merged->count());
                $prefixEnd   = $merged->first() - 2; // must leave at least a 1-page gap

                if ($prefixCount > 0 && $prefixEnd >= 1) {
                    $actualCount = min($prefixCount, $prefixEnd);
                    foreach (range(1, $actualCount) as $p) $mItems->push($p);
                    if ($mItems->last() < $merged->first() - 1) $mItems->push('...');
                } elseif ($merged->first() > 1) {
                    $mItems->push('...');
                }

                foreach ($merged as $p) $mItems->push($p);
            } else {
                // Normal case: window and last-2 are separate
                if ($window->first() > 1)                            $mItems->push('...');
                foreach ($window as $p)                              $mItems->push($p);
                if ($window->last() < $endPages->first() - 1)       $mItems->push('...');
                foreach ($endPages as $p)                            $mItems->push($p);
            }
        @endphp

        {{-- Prev --}}
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->onFirstPage() ? '#' : $paginator->previousPageUrl() }}">&laquo;</a>
        </li>

        @foreach ($mItems as $item)
            @if ($item === '...')
                <li class="page-item disabled"><span class="page-link px-2">…</span></li>
            @else
                <li class="page-item {{ $item == $current ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($item) }}">{{ $item }}</a>
                </li>
            @endif
        @endforeach

        {{-- Next --}}
        <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}">&raquo;</a>
        </li>
    </ul>

    {{-- ── Tablet / Desktop (≥768px): first 5 + … + last 3 ── --}}
    <ul class="pagination justify-content-center d-none d-md-flex flex-wrap gap-1 mb-0">
        @php
            $dFirst = collect(range(1, min(5, $last)));
            $dLast  = collect(range(max(1, $last - 2), $last));
            $dGap   = $dFirst->last() < $dLast->first() - 1;
        @endphp

        {{-- Prev --}}
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->onFirstPage() ? '#' : $paginator->previousPageUrl() }}">Previous</a>
        </li>

        @foreach ($dFirst as $page)
            <li class="page-item {{ $page == $current ? 'active' : '' }}">
                <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
            </li>
        @endforeach

        @if ($dGap)
            <li class="page-item disabled"><span class="page-link px-2">…</span></li>
        @endif

        @foreach ($dLast as $page)
            @if (!$dFirst->contains($page))
                <li class="page-item {{ $page == $current ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                </li>
            @endif
        @endforeach

        {{-- Next --}}
        <li class="page-item {{ !$paginator->hasMorePages() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}">Next</a>
        </li>
    </ul>

</nav>
@endif
