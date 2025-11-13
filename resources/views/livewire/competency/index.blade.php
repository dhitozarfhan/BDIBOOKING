<div>
    <div class="bg-base-200">
        <section class="bg-gradient-to-r from-cyan-900 to-cyan-700 text-base-content">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
                @php
                    $breadcrumbs = [
                        ['label' => __('Beranda'), 'url' => route('home')],
                        ['label' => __('competency.industrial_hr_competency')]
                    ];
                @endphp
                @include('livewire.competency.partials.breadcrumb', ['items' => $breadcrumbs])

                <div class="text-center mt-4">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4">
                        {{ __('competency.industrial_hr_competency') }}
                    </h1>
                </div>
            </div>
        </section>
    
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="card bg-base-100 shadow-xl border border-base-200">
                <div class="card-body prose prose-base sm:prose-lg dark:prose-invert max-w-none space-y-6">
                    {!! __('competency.competency_preamble') !!}
                </div>
            </div>
        </section>
    </div>
    <div class="bg-base-200">
        @php
    $items = [
        [
            'key' => 'skkni',
            'icon' => 'bi-journal-text',
            'label' => __('competency.competency_skkni'),
            'description' => __('competency.quickmenu_skkni_description'),
        ],
        [
            'key' => 'lsp',
            'icon' => 'bi-patch-check',
            'label' => __('competency.competency_lsp'),
            'description' => __('competency.quickmenu_lsp_description'),
        ],
        [
            'key' => 'tuk',
            'icon' => 'bi-clipboard-check',
            'label' => __('competency.competency_tuk'),
            'description' => __('competency.quickmenu_tuk_description'),
        ],
        [
            'key' => 'assessor',
            'icon' => 'bi-person-check',
            'label' => __('competency.competency_assessor'),
            'description' => __('competency.quickmenu_assessor_description'),
        ],
        [
            'key' => 'scheme',
            'icon' => 'bi-diagram-3',
            'label' => __('competency.competency_scheme'),
            'description' => __('competency.quickmenu_scheme_description'),
        ],
    ];
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl md:text-4xl font-extrabold text-center mb-10">
        {{ __('competency.competency_infrastructure') }}
    </h2>
    <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-5">
        @foreach ($items as $item)
            <div
                class="transition-all duration-200 hover:-translate-y-1">
                <a wire:navigate href="{{ route('competency.section', ['section' => $item['key']]) }}"
                    class="card bg-base-100 border border-base-200 shadow-sm hover:shadow-lg h-full">
                    <div class="card-body text-center items-center gap-4">
                        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                            <i class="bi {{ $item['icon'] }} text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-base-content text-center">
                            {{ $item['label'] }}
                        </h3>
                        @if ($item['description'])
                            <p class="text-sm text-base-content/70">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
    </div>
</div>
