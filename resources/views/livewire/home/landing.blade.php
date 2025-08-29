<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
    {{-- =================== HERO SLIDESHOW =================== --}}
    @if($slides->isNotEmpty())
    <section class="mb-8 md:mb-10">
        <div class="carousel w-full rounded-2xl overflow-hidden bg-base-200">
            @foreach($slides as $i => $s)
                @php $anchor = 'slide-'.$s->id; @endphp
                <div id="{{ $anchor }}" class="carousel-item relative w-full">
                    <img
                        src="{{ Storage::url($s->image) }}"
                        alt="{{ $s->title }}"
                        class="w-full h-[220px] sm:h-[300px] md:h-[420px] object-cover" />

                    {{-- Overlay content --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6 text-white backdrop-blur">
                        @if($s->title)
                            <h2 class="text-xl md:text-3xl font-bold drop-shadow-sm line-clamp-2">{{ $s->title }}</h2>
                        @endif
                        @if($s->description)
                            <p class="text-sm md:text-base opacity-90 mt-1 line-clamp-2">{{ $s->description }}</p>
                        @endif
                        @if($s->link_type_id != App\Enums\LinkType::Empty->value)
                            <a
                                wire:navigate href="{{ ($s->link) }}"
                                class="btn btn-primary btn-xs mt-3"
                            >
                                {{ __(App\Enums\LinkType::Article->value ? 'Read More' : 'View') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        {{-- Dots --}}
        <div class="flex justify-center gap-2 mt-3">
            @foreach($slides as $s)
                <a href="#{{ 'slide-'.$s->id }}" class="btn btn-xs">{{ $loop->iteration }}</a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- =================== LISTING =================== --}}
    <section>
        @if($latest->isEmpty())
            <div role="alert" class="alert alert-info">
                <i class="bi bi-exclamation-circle"></i>
                <span>{{ __(':article not found.', ['article' => __('Article')]) }}</span>
            </div>
        @else
            <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-6">
                @foreach($latest as $a)
                    <x-home.card :article="$a" :articleType="$a->articleType->slug" />
                @endforeach
            </div>
        @endif
    </section>
</div>