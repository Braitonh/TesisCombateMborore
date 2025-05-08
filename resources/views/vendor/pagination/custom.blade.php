@if ($paginator->hasPages())
    <nav aria-label="Pagination" class="inline-flex -space-x-px rounded-md shadow-sm dark:bg-gray-100 dark:text-gray-800">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-2 py-2 text-sm font-semibold border rounded-l-md text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"><path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"/></svg>
            </span>
        @else
            <button wire:click="previousPage" class="inline-flex items-center px-2 py-2 text-sm font-semibold border rounded-l-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"><path d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"/></svg>
            </button>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center px-4 py-2 text-sm font-semibold border text-gray-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center px-4 py-2 text-sm font-semibold border dark:bg-orange-500 text-white">{{ $page }}</span>
                    @else
                        <button wire:click="gotoPage({{ $page }})" class="inline-flex items-center px-4 py-2 text-sm font-semibold border hover:bg-gray-100">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" class="inline-flex items-center px-2 py-2 text-sm font-semibold border rounded-r-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
            </button>
        @else
            <span class="inline-flex items-center px-2 py-2 text-sm font-semibold border rounded-r-md text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg>
            </span>
        @endif
    </nav>
@endif
