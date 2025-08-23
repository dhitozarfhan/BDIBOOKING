@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between mt-4">
        {{-- Mobile: Prev/Next saja --}}
        <div class="flex-1 flex justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="btn btn-disabled">{{ __('Previous') }}</span>
            @else
                <a wire:navigate href="{{ $paginator->previousPageUrl() }}" class="btn" rel="prev">{{ __('Previous') }}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a wire:navigate href="{{ $paginator->nextPageUrl() }}" class="btn" rel="next">{{ __('Next') }}</a>
            @else
                <span class="btn btn-disabled">{{ __('Next') }}</span>
            @endif
        </div>

        {{-- Desktop: full numbers --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div class="text-sm opacity-70">
                {!! __('Showing :from - :to from :total', [
                    'from' => '<span class="font-medium">' . $paginator->firstItem() . '</span>',
                    'to' => '<span class="font-medium">' . $paginator->lastItem() . '</span>',
                    'total' => '<span class="font-medium">' . $paginator->total() . '</span>',
                ]) !!}
            </div>

            <div class="join">
                {{-- Prev --}}
                @if ($paginator->onFirstPage())
                    <span class="join-item btn btn-disabled">«</span>
                @else
                    <a wire:navigate class="join-item btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">«</a>
                @endif

                {{-- Numbers --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="join-item btn btn-disabled">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="join-item btn btn-primary">{{ $page }}</span>
                            @else
                                <a wire:navigate class="join-item btn" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a wire:navigate class="join-item btn" href="{{ $paginator->nextPageUrl() }}" rel="next">»</a>
                @else
                    <span class="join-item btn btn-disabled">»</span>
                @endif
            </div>
        </div>
    </nav>
@endif
