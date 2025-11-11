@props(['items' => []])

@if (!empty($items))
    <nav aria-label="breadcrumb">
        <ol class="flex items-center space-x-2 text-sm text-base-content/80">
            @foreach ($items as $item)
                <li>
                    @if ($loop->first)
                        <a wire:navigate href="{{ $item['url'] }}" class="hover:underline hover:text-primary">
                            <i class="bi bi-house-fill"></i>
                        </a>
                    @elseif (!isset($item['url']) || $loop->last)
                        <span class="font-medium text-base-content">
                            {{ $item['label'] }}
                        </span>
                    @else
                        <a wire:navigate href="{{ $item['url'] }}" class="hover:underline hover:text-primary">
                            {{ $item['label'] }}
                        </a>
                    @endif
                </li>
                @if (!$loop->last)
                    <li>
                        <i class="bi bi-chevron-right text-xs"></i>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
