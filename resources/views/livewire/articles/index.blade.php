<div class="container mx-auto p-4 space-y-6">
    @if($articles->isEmpty())
    <div role="alert" class="alert alert-info">
        <i class="bi bi-exclamation-circle"></i>
        <span>{{ __(':article not found.', ['article' => __('Post')]) }}</span>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- MAIN --}}
        <div class="lg:col-span-9 space-y-8">

            {{-- Toolbar: Toggle Grid/List --}}
            <div class="flex justify-end mb-3">
                <div class="btn-group">
                    <button class="btn btn-sm" wire:click="setView('grid')" @class(['btn-active'=> $view==='grid'])>
                        <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M3 3h8v8H3zM13 3h8v8h-8zM3 13h8v8H3zM13 13h8v8h-8z" />
                        </svg>
                        Grid
                    </button>
                    <button class="btn btn-sm" wire:click="setView('list')" @class(['btn-active'=> $view==='list'])>
                        <svg class="w-4 h-4 mr-1" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z" />
                        </svg>
                        List
                    </button>
                </div>
            </div>

            {{-- LIST ARTIKEL TERBARU --}}
            <section>
                <h2 class="text-xl font-semibold mb-3">Artikel Terbaru</h2>

                <div wire:loading class="grid gap-4">
                    <div class="skeleton h-28 w-full"></div>
                    <div class="skeleton h-28 w-full"></div>
                    <div class="skeleton h-28 w-full"></div>
                </div>

                <div wire:loading.remove>
                    @if($view === 'grid')
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($articles as $article)
                        <x-article.card :article="$article" />
                        @endforeach
                    </div>
                    @else
                    <div class="divide-y">
                        @foreach($articles as $article)
                        <x-article.item :article="$article" />
                        @endforeach
                    </div>
                    @endif

                    <div class="mt-4">
                        {{ $articles->onEachSide(1)->links() }}
                    </div>
                </div>
            </section>
        </div>

        {{-- SIDEBAR --}}
        <aside class="lg:col-span-3">
            <x-article.sidebar
                :categories="$categories"
                :archives="$archives"
                :popular="$popular"
                :latest="$latest" />
        </aside>
    </div>

    @endif
</div>