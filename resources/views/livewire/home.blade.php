<div class="container mx-auto p-4 space-y-6">
    @if($articles->isEmpty())
    <div role="alert" class="alert alert-info">
        <i class="bi bi-exclamation-circle"></i>
        <span>{{ __(':article not found.', ['article' => __('Article')]) }}</span>
    </div>
    @else
    <div wire:loading class="space-y-3">
        <div class="skeleton h-24 w-full"></div>
        <div class="skeleton h-24 w-full"></div>
    </div>
    <div wire:loading.remove class="grid lg:grid-cols-4 xs:grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($articles as $article)
        <x-home.card :article="$article" />
        @endforeach
    </div>
    @endif
</div>