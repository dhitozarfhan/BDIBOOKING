<div>
    <div class="bg-base-200 min-h-screen">
        <section class="bg-gradient-to-r from-cyan-900 to-cyan-700 text-base-content">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
                @php
                    $breadcrumbs = [
                        ['label' => __('Beranda'), 'url' => route('home')],
                        ['label' => __('competency.industrial_hr_competency'), 'url' => route('competency.index')],
                        ['label' => $title]
                    ];
                @endphp
                @include('livewire.competency.partials.breadcrumb', ['items' => $breadcrumbs])

                <div class="text-center mt-4">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4">
                        {{ $title ?? __('competency.industrial_hr_competency') }}
                    </h1>
                    <p class="max-w-3xl mx-auto text-base md:text-lg text-white/80">
                        {{ __('competency.section_intro_' . $section) }}
                    </p>
                </div>
            </div>
        </section>

        <div x-data="{
                section: @js($section),
                cacheKey() { return 'competency_data_' + this.section; },
                timestampKey() { return 'competency_timestamp_' + this.section; },
                init() {
                    const storedData = localStorage.getItem(this.cacheKey());
                    const storedTimestamp = localStorage.getItem(this.timestampKey());

                    if (storedData && storedTimestamp) {
                        const isFresh = (new Date().getTime() - parseInt(storedTimestamp)) < 300 * 1000; // 5 minute cache
                        if (isFresh) {
                            console.log('Loading competency data from client-side cache.');
                            this.$wire.loadFromCache(JSON.parse(storedData));
                            return;
                        }
                    }

                    console.log('Fetching fresh competency data from API.');
                    this.$wire.loadData();
                }
            }"
             @data-loaded.window="
                console.log('Saving fresh competency data to client-side cache for section: ' + section);
                localStorage.setItem(cacheKey(), JSON.stringify($event.detail.data));
                localStorage.setItem(timestampKey(), new Date().getTime());
            "
             class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">

            @if (!$isDataLoaded)
                <div class="flex justify-center items-center py-20">
                    <span class="loading loading-spinner loading-lg text-primary"></span>
                    <span class="ml-4 text-lg font-semibold text-base-content/70">{{ __('competency.loading_data') }}</span>
                </div>
            @else
                <div>
                    @if ($error)
                        <div role="alert" class="alert alert-error shadow-lg mb-8">
                            <i class="fas fa-exclamation-triangle text-xl"></i>
                            <div>
                                <h3 class="font-bold">{{ __('competency.error_title') }}</h3>
                                <div class="text-sm">{{ $error }}</div>
                            </div>
                        </div>
                    @elseif ($rows->isEmpty() && empty($search))
                        <div role="alert" class="alert alert-info shadow mb-8">
                            <i class="fas fa-info-circle text-xl"></i>
                            <div>
                                <h3 class="font-bold">{{ __('competency.empty_title') }}</h3>
                                <div class="text-sm">{{ __('competency.empty_description_' . $section) }}</div>
                            </div>
                        </div>
                    @else
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center">
                                <span class="mr-2 text-sm text-base-content/70">{{ __('pagination.per_page') }}</span>
                                <select wire:model.live="perPage" class="select select-bordered select-sm">
                                    <option value="15">15</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="w-full max-w-xs">
                                <input type="search" wire:model.live.debounce.500ms="search" class="input input-bordered input-sm w-full" placeholder="{{ __('pagination.search_placeholder') }}">
                            </div>
                        </div>
                        <div class="card bg-base-100 shadow-xl border border-base-200" wire:loading.class.delay="opacity-50" wire:target="search, perPage">
                            <div class="overflow-x-auto">
                                @if ($rows->isEmpty() && !empty($search))
                                    <div class="p-8 text-center text-base-content/70">
                                        {{ __('competency.search_no_results_for', ['term' => $search]) }}
                                    </div>
                                @else
                                    <table class="table table-zebra w-full">
                                        <thead>
                                            <tr>
                                                @foreach ($columns as $column)
                                                    <th class="text-sm font-semibold uppercase tracking-wide text-base-content/70">
                                                        {{ $column['label'] }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rows as $row)
                                                <tr class="hover align-top">
                                                    @foreach ($columns as $column)
                                                        @php
                                                            $value = $row[$column['key']] ?? null;
                                                            $type = $column['type'] ?? 'text';
                                                        @endphp
                                                        <td class="text-sm text-base-content/90">
                                                            @switch($type)
                                                                @case('link')
                                                                    @if (is_array($value))
                                                                        @php
                                                                            $text = $value['text'] ?? '-';
                                                                            $url = $value['url'] ?? null;
                                                                        @endphp
                                                                        @if ($url)
                                                                            <a href="{{ $url }}"
                                                                                class="text-blue-600 font-semibold hover:underline">
                                                                                {{ $text }}
                                                                            </a>
                                                                        @else
                                                                            {{ $text }}
                                                                        @endif
                                                                    @else
                                                                        {{ $value ?? '-' }}
                                                                    @endif
                                                                    @break

                                                                @case('badge')
                                                                    @if (is_array($value))
                                                                        @php
                                                                            $label = $value['label'] ?? '-';
                                                                            $tone = $value['tone'] ?? 'neutral';
                                                                            $url = $value['url'] ?? null;
                                                                            $notes = $value['notes'] ?? null;
                                                                            $badgeClasses = match ($tone) {
                                                                                'success' => 'badge-success',
                                                                                'warning' => 'badge-warning',
                                                                                'error' => 'badge-error',
                                                                                'primary' => 'badge-primary',
                                                                                default => 'badge-ghost',
                                                                            };
                                                                        @endphp
                                                                        @if ($url)
                                                                            <a href="{{ $url }}" class="btn btn-xs {{ $badgeClasses }}" target="_blank">{{ $label }}</a>
                                                                        @elseif ($notes)
                                                                            <div class="tooltip" data-tip="{{ $notes }}">
                                                                                <span class="badge {{ $badgeClasses }}">{{ $label }}</span>
                                                                            </div>
                                                                        @else
                                                                            <span class="badge {{ $badgeClasses }}">{{ $label }}</span>
                                                                        @endif
                                                                    @else
                                                                        {{ $value ?? '-' }}
                                                                    @endif
                                                                    @break

                                                                @case('button')
                                                                    @if (is_array($value))
                                                                        @php
                                                                            $label = $value['label'] ?? '-';
                                                                            $url = $value['url'] ?? null;
                                                                            $tone = $value['tone'] ?? 'primary';
                                                                            $buttonClasses = match ($tone) {
                                                                                'success' => 'bg-green-500 border-green-500 text-white',
                                                                                'warning' => 'btn-warning',
                                                                                'error' => 'bg-red-500 border-red-500 text-white',
                                                                                'primary' => 'btn-primary',
                                                                                default => 'btn-ghost',
                                                                            };
                                                                        @endphp
                                                                        @if ($url)
                                                                            <a href="{{ $url }}" class="btn btn-sm {{ $buttonClasses }}">
                                                                                {{ $label }}
                                                                            </a>
                                                                        @else
                                                                            <span class="btn btn-sm {{ $buttonClasses }}">{{ $label }}</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="btn btn-sm btn-disabled">{{ $value ?? '-' }}</span>
                                                                    @endif
                                                                    @break

                                                                @case('image')
                                                                    @if ($value)
                                                                        <img src="{{ $value }}" alt=""
                                                                            class="w-16 h-16 object-contain rounded-lg shadow border border-base-200">
                                                                    @else
                                                                        <div class="w-16 h-16 flex flex-col items-center justify-center bg-gray-100 rounded-lg text-gray-400 text-xs">
                                                                            <i class="bi bi-image text-2xl"></i>
                                                                            <span>{{ __('competency.no_logo') }}</span>
                                                                        </div>
                                                                    @endif
                                                                    @break

                                                                @case('gender')
                                                                    @if (is_array($value))
                                                                        @php
                                                                            $gender = strtoupper((string) ($value['value'] ?? ''));
                                                                            $icon = $gender === 'L' ? 'mars' : ($gender === 'P' ? 'venus' : 'user');
                                                                            $toneClass = $gender === 'L' ? 'text-primary' : ($gender === 'P' ? 'text-secondary' : 'text-base-content/60');
                                                                        @endphp
                                                                        <div class="inline-flex items-center gap-2">
                                                                            <i class="fas fa-{{ $icon }} {{ $toneClass }}"></i>
                                                                            <span>{{ $value['label'] ?? '-' }}</span>
                                                                        </div>
                                                                    @else
                                                                        {{ $value ?? '-' }}
                                                                    @endif
                                                                    @break

                                                                @default
                                                                    {{ $value ?? '-' }}
                                                            @endswitch
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                        @if ($rows->hasPages())
                        <div class="mt-8">
                            {{ $rows->links() }}
                        </div>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </div>

    @include('livewire.competency.partials.quick-menu')
</div>
