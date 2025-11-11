<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $breadcrumbs = [
                ['label' => __('Beranda'), 'url' => route('home')],
                ['label' => __('Gratification Reporting'), 'url' => route('gratification')],
                ['label' => __('Statistical Report')]
            ];
        @endphp
        @include('livewire.gratification.partials.breadcrumb', ['items' => $breadcrumbs])
        <h2 class="text-2xl font-bold text-base-content mt-4">
            {{ __('Gratification Statistics') }}
        </h2>

        <div class="mt-6">
            <label for="tahun" class="label">
                <span class="label-text">{{ __('Year') }}</span>
            </label>
            <select id="tahun" class="select select-bordered w-full" wire:model="selectedYear" wire:change="updateReport">
                @for ($year = date('Y'); $year >= 2020; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endfor
            </select>
        </div>

        <!-- Grafik Jumlah Laporan per Bulan -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-base-content">{{ __('Number of Reports per Month') }} ({{ $selectedYear }})</h3>
            <div class="mt-4 card bg-base-200 shadow-xl">
                <div class="card-body">
                    @if($reportCountData)
                        <div class="space-y-2">
                            @foreach($reportCountData as $month => $count)
                                <div class="flex items-center">
                                    <div class="w-24 text-sm">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</div>
                                    <div class="flex-1 ml-4">
                                        <progress class="progress progress-primary w-full" value="{{ $count }}" max="{{ max($reportCountData) }}"></progress>
                                        <div class="text-xs text-base-content mt-1">{{ $count }} {{ __('report(s)') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-base-content">{{ __('No data available') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grafik Waktu Rata-rata Penyelesaian -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-base-content">{{ __('Average Completion Time per Month') }} ({{ $selectedYear }})</h3>
            <div class="mt-4 card bg-base-200 shadow-xl">
                <div class="card-body">
                    @if($timeToAnswerData)
                        <div class="space-y-2">
                            @foreach($timeToAnswerData as $month => $time)
                                <div class="flex items-center">
                                    <div class="w-24 text-sm">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</div>
                                    <div class="flex-1 ml-4">
                                        <progress class="progress progress-accent w-full" value="{{ $time }}" max="{{ max($timeToAnswerData) }}"></progress>
                                        <div class="text-xs text-base-content mt-1">{{ $time }} {{ __('day(s)') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-base-content">{{ __('No data available') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grafik Status Laporan -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-base-content">{{ __('Report Status Distribution') }} ({{ $selectedYear }})</h3>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @if($statusData)
                    @foreach($statusData as $status)
                        <div class="card bg-base-200 shadow-xl">
                            <div class="card-body items-center text-center">
                                <h4 class="card-title">
                                    @if($status['status'] === \App\Enums\ResponseStatus::Initiation)
                                        <i class="bi bi-hourglass-split text-warning"></i> {{ __('Initiation') }}
                                    @elseif($status['status'] === \App\Enums\ResponseStatus::Process)
                                        <i class="bi bi-arrow-repeat text-info"></i> {{ __('Process') }}
                                    @elseif($status['status'] === \App\Enums\ResponseStatus::Disposition)
                                        <i class="bi bi-cursor-fill text-primary"></i> {{ __('Disposition') }}
                                    @elseif($status['status'] === \App\Enums\ResponseStatus::Termination)
                                        <i class="bi bi-check-all text-success"></i> {{ __('Completed') }}
                                    @else
                                        <i class="bi bi-question-circle-fill"></i> {{ __('Unknown Status') }}
                                    @endif
                                </h4>
                                <p class="text-3xl font-bold">{{ $status['count'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-base-content">{{ __('No data available') }}</p>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('gratification') }}" class="btn btn-ghost">
                {{ __('Back') }}
            </a>
        </div>
    </div>
</div>