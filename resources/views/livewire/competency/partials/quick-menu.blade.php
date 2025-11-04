@php
    $items = [
        [
            'key' => 'skkni',
            'icon' => 'fa-swatchbook',
            'label' => __('competency.competency_skkni'),
            'description' => __('competency.quickmenu_skkni_description'),
        ],
        [
            'key' => 'lsp',
            'icon' => 'fa-building',
            'label' => __('competency.competency_lsp'),
            'description' => __('competency.quickmenu_lsp_description'),
        ],
        [
            'key' => 'tuk',
            'icon' => 'fa-warehouse',
            'label' => __('competency.competency_tuk'),
            'description' => __('competency.quickmenu_tuk_description'),
        ],
        [
            'key' => 'assessor',
            'icon' => 'fa-user-check',
            'label' => __('competency.competency_assessor'),
            'description' => __('competency.quickmenu_assessor_description'),
        ],
        [
            'key' => 'scheme',
            'icon' => 'fa-flask',
            'label' => __('competency.competency_scheme'),
            'description' => __('competency.quickmenu_scheme_description'),
        ],
    ];
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-3xl md:text-4xl font-extrabold text-center mb-10">
        {{ __('competency.competency_infrastructure') }}
    </h2>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-6">
        @foreach ($items as $item)
            <div
                class="transition-all duration-200 hover:-translate-y-1 {{ $item['key'] === 'skkni' ? 'lg:col-span-2 md:col-span-2' : 'lg:col-span-1' }}">
                <a wire:navigate href="{{ route('competency.section', ['section' => $item['key']]) }}"
                    class="card bg-base-100 border border-base-200 shadow-sm hover:shadow-lg h-full">
                    <div class="card-body text-center items-center gap-4">
                        <div class="w-16 h-16 flex items-center justify-center rounded-full bg-primary/10 text-primary">
                            <i class="fas {{ $item['icon'] }} text-3xl"></i>
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
