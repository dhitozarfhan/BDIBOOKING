<div>
    <div class="bg-base-200 min-h-screen">
        <section class="bg-gradient-to-r from-cyan-900 to-cyan-700 text-base-content">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 text-center">
                <h1 class="text-3xl md:text-5xl font-bold mb-4">
                    {{ $title ?? __('competency.industrial_hr_competency') }}
                </h1>
                <p class="max-w-3xl mx-auto text-base md:text-lg text-white/80">
                    {{ __('competency.section_intro_' . $section) }}
                </p>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
            <div wire:loading class="flex justify-center items-center py-20">
                <span class="loading loading-spinner loading-lg text-primary"></span>
                <span class="ml-4 text-lg font-semibold text-base-content/70">{{ __('competency.loading_data') }}</span>
            </div>

            <div wire:loading.remove>
                @if ($error)
                    <div role="alert" class="alert alert-error shadow-lg mb-8">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                        <div>
                            <h3 class="font-bold">{{ __('competency.error_title') }}</h3>
                            <div class="text-sm">{{ $error }}</div>
                        </div>
                    </div>
                @elseif ($rows->isEmpty())
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
                            <input type="search" wire:model.live.debounce.300ms="search" class="input input-bordered input-sm w-full" placeholder="{{ __('pagination.search_placeholder') }}">
                        </div>
                    </div>
                    <div class="card bg-base-100 shadow-xl border border-base-200">
                        <div class="overflow-x-auto">
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
                                                                    <a wire:navigate href="{{ $url }}"
                                                                        class="text-primary font-semibold hover:underline">
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
                                                                    $badgeClasses = match ($tone) {
                                                                        'success' => 'badge-success',
                                                                        'warning' => 'badge-warning',
                                                                        'error' => 'badge-error',
                                                                        'primary' => 'badge-primary',
                                                                        default => 'badge-ghost',
                                                                    };
                                                                @endphp
                                                                <span class="badge {{ $badgeClasses }}">{{ $label }}</span>
                                                            @else
                                                                {{ $value ?? '-' }}
                                                            @endif
                                                        @break

                                                        @case('image')
                                                            @if ($value)
                                                                <img src="{{ $value }}" alt=""
                                                                    class="w-16 h-16 object-contain rounded-lg shadow border border-base-200">
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
                        </div>
                    </div>
                    <div class="mt-8">
                        {{ $rows->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('livewire.competency.partials.quick-menu')
</div>
