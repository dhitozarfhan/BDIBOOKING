{{-- Livewire v3 --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header + Toolbar --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">
                {{ $this->pageTitle() }}
            </h1>
            @if($categorySlug || $year || strlen(trim($this->search)) >= 2)
                <p class="text-sm text-base-content/60 mt-1">
                    @if($categorySlug)
                        {{ __('Category')}}: <span class="font-medium">{{ \Illuminate\Support\Str::headline(substr($categorySlug, 0, strrpos($categorySlug, '-'))) }}</span>
                    @endif
                    @if($year && $month)
                        <span class="me-2">{{ __('Archives')}}: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</span>
                    @endif
                    @if(strlen(trim($this->search)) >= 2)
                        <span class="font-medium">{{ __('Search results for')}}: “{{ $this->search }}”</span>
                    @endif
                </p>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Toggle Grid/List --}}
            <div class="join">
                <input type="radio" name="view" class="join-item btn btn-sm"
                    value="grid" wire:model.live="viewMode" aria-label="Grid" />
                <input type="radio" name="view" class="join-item btn btn-sm"
                    value="list" wire:model.live="viewMode" aria-label="List" />
            </div>

            {{-- Per Page --}}
            <label class="flex items-center gap-2">
                <span class="text-sm">{{ __('Per Page') }}</span>
                <select class="select select-sm select-bordered"
                    wire:model.live="perPage">
                    @foreach([6,9,12,15,18,24] as $n)
                    <option value="{{ $n }}">{{ $n }}</option>
                    @endforeach
                </select>
            </label>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Main list --}}
        <div class="lg:col-span-8">

            {{-- Skeleton loader saat loading --}}
            <div wire:loading.delay class="mb-6">
                @if($view === 'list')
                    <div class="flex flex-col gap-8 animate-pulse">
                        @for($i=0;$i<4;$i++)
                            <div class="flex w-52 flex-col">
                                <div class="flex items-center gap-4">
                                    <div class="skeleton h-16 w-16 shrink-0 rounded-full"></div>
                                    <div class="flex flex-col gap-4">
                                    <div class="skeleton h-4 w-20"></div>
                                    <div class="skeleton h-4 w-28"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                @else
                    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6 animate-pulse">
                        @for($i=0;$i<4;$i++)
                            <div class="flex w-52 flex-col gap-4">
                                <div class="skeleton h-32 w-full"></div>
                                <div class="skeleton h-4 w-28"></div>
                                <div class="skeleton h-4 w-full"></div>
                                <div class="skeleton h-4 w-full"></div>
                            </div>
                        @endfor
                    </div>
                @endif
            </div>

            <div wire:loading.remove>
                @if($items->count() === 0)
                <div class="alert alert-error alert-soft">
                    {{ __('No post is suitable for the current filter.') }}
                </div>
                @else
                @if($view === 'list')
                <div class="flex flex-col divide-base-300 divide-y">
                    @foreach($items as $a)
                    <x-article.item :article="$a" :articleType="$articleType" />
                    @endforeach
                </div>
                @else
                <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($items as $a)
                    <x-article.card :article="$a" :articleType="$articleType" />
                    @endforeach
                </div>
                @endif

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $items->onEachSide(1)->links('components.pagination.daisy') }}
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="lg:col-span-4">
            <x-article.sidebar
                :latest="$latest"
                :popular="$popular"
                :categories="$categories"
                :archives="$archives"
                :articleType="$articleType" />
        </aside>
    </div>
</div>