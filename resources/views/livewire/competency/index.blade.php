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
        @include('livewire.competency.partials.quick-menu')
    </div>
</div>
