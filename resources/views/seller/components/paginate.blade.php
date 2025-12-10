@if ($paginator->count() > 0)
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        {{-- Pagination Info --}}
        <div class="text-xs sm:text-sm text-base-content/70 text-center sm:text-left">
            Menampilkan
            <span class="font-semibold">{{ $paginator->firstItem() }}</span>
            sampai
            <span class="font-semibold">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-semibold">{{ $paginator->total() }}</span>
            hasil
        </div>

        {{-- Pagination Buttons --}}
        <div class="w-full sm:w-auto">
            <div class="join flex flex-wrap justify-center sm:flex-nowrap">

                {{-- First Page --}}
                @if ($paginator->onFirstPage())
                    <button class="join-item btn btn-sm sm:btn-sm btn-disabled" disabled>&laquo;&laquo;</button>
                @else
                    <a href="{{ $paginator->url(1) }}" class="join-item btn btn-sm sm:btn-sm">&laquo;&laquo;</a>
                @endif

                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <button class="join-item btn btn-sm sm:btn-sm btn-disabled" disabled>&lsaquo;</button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="join-item btn btn-sm sm:btn-sm">&lsaquo;</a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <button class="join-item btn btn-sm sm:btn-sm btn-disabled">{{ $element }}</button>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <button
                                    class="join-item btn btn-sm sm:btn-sm btn-active btn-primary">{{ $page }}</button>
                            @else
                                <a href="{{ $url }}"
                                    class="join-item btn btn-sm sm:btn-sm">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="join-item btn btn-sm sm:btn-sm">&rsaquo;</a>
                @else
                    <button class="join-item btn btn-sm sm:btn-sm btn-disabled">&rsaquo;</button>
                @endif

                {{-- Last Page --}}
                @if ($paginator->currentPage() == $paginator->lastPage())
                    <button class="join-item btn btn-sm sm:btn-sm btn-disabled">&raquo;&raquo;</button>
                @else
                    <a href="{{ $paginator->url($paginator->lastPage()) }}"
                        class="join-item btn btn-sm sm:btn-sm">&raquo;&raquo;</a>
                @endif

            </div>
        </div>
    </div>
@endif
