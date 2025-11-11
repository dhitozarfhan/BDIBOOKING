<div>
    <div class="bg-base-200 min-h-screen">
        <section class="bg-gradient-to-r from-cyan-900 to-cyan-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
                @php
                    $breadcrumbs = [
                        ['label' => __('Beranda'), 'url' => route('home')],
                        ['label' => __('competency.industrial_hr_competency'), 'url' => route('competency.index')],
                        ['label' => __('competency.competency_skkni'), 'url' => route('competency.section', ['section' => 'skkni'])],
                        ['label' => $skkni['judul'] ?? __('competency.skkni_details')]
                    ];
                @endphp
                @include('livewire.competency.partials.breadcrumb', ['items' => $breadcrumbs])

                <div class="text-center mt-4">
                    <h1 class="text-3xl md::text-5xl font-bold mb-3 text-base-content">
                        {{ $skkni['judul'] ?? __('competency.skkni_details') }}
                    </h1>
                    <p class="max-w-3xl mx-auto text-base md:text-lg text-base-content/80">
                        {{ __('competency.skkni_details_subtitle') }}
                    </p>
                </div>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
            @if ($error)
                <div role="alert" class="alert alert-error shadow-lg">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                    <div>
                        <h3 class="font-bold">{{ __('competency.error_title') }}</h3>
                        <div class="text-sm">{{ $error }}</div>
                    </div>
                </div>
            @else
                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body">
                        <div class="tabs tabs-boxed mb-6">
                            <button wire:click="switchTab('details')"
                                class="tab {{ $activeTab === 'details' ? 'tab-active' : '' }}">
                                {{ __('competency.details') }}
                            </button>
                            <button wire:click="switchTab('units')"
                                class="tab {{ $activeTab === 'units' ? 'tab-active' : '' }}">
                                {{ __('competency.competency_unit') }}
                            </button>
                        </div>

                        @if ($activeTab === 'details')
                            <div class="space-y-6">
                                <div class="pb-4 border-b border-base-300">
                                    <h2 class="text-2xl font-bold text-primary">{{ $skkni['judul'] ?? '-' }}</h2>
                                </div>

                                @php
                                    $createdAt = $skkni['created_date'] ?? null;
                                    try {
                                        $createdLabel = $createdAt
                                            ? \Carbon\Carbon::parse($createdAt)->isoFormat('D MMMM YYYY')
                                            : null;
                                    } catch (\Exception $e) {
                                        $createdLabel = $createdAt;
                                    }
                                @endphp
                        
                                                                @php
                        
                                                                    $details = [
                        
                                                                        ['icon' => 'bi-hash', 'label' => __('competency.skkni_code'), 'value' => $skkni['kode_skkni'] ?? '-'],
                        
                                                                        ['icon' => 'bi-briefcase', 'label' => __('competency.skkni_sector'), 'value' => $skkni['bidang'] ?? '-'],
                        
                                                                        ['icon' => 'bi-building', 'label' => __('competency.skkni_agency'), 'value' => $skkni['agency'] ?? '-'],
                        
                                                                        ['icon' => 'bi-tags', 'label' => __('competency.skkni_category'), 'value' => $skkni['category'] ?? '-'],
                        
                                                                        ['icon' => 'bi-star', 'label' => __('competency.skkni_core'), 'value' => $skkni['core'] ?? '-'],
                        
                                                                        ['icon' => 'bi-patch-check', 'label' => __('competency.skkni_ministerial_decree_number'), 'value' => $skkni['nomor'] ?? '-'],
                        
                                                                        ['icon' => 'bi-calendar', 'label' => __('competency.created_date'), 'value' => $createdLabel ?? '-'],
                        
                                                                        ['icon' => 'bi-person-badge', 'label' => __('competency.skkni_job_title'), 'value' => $skkni['job_title'] ?? '-'],
                        
                                                                    ];
                        
                                
                        
                                                                    if (strtolower((string) ($skkni['availability'] ?? '')) === 'cancelled' && !empty($skkni['revoked_date'])) {
                        
                                                                        $revokedDate = $skkni['revoked_date'];
                        
                                                                        try {
                        
                                                                            $revokedDateLabel = \Carbon\Carbon::parse($revokedDate)->isoFormat('D MMMM YYYY');
                        
                                                                        } catch (\Exception $e) {
                        
                                                                            $revokedDateLabel = $revokedDate;
                        
                                                                        }
                        
                                                                        $details[] = ['icon' => 'bi-calendar-x', 'label' => __('competency.revoked_date'), 'value' => $revokedDateLabel];
                        
                                                                    }
                        
                                                                @endphp

                                <div class="divide-y divide-base-300">
                                    @foreach ($details as $detail)
                                        <div class="flex items-center py-3">
                                            <div class="w-full sm:w-1/3 font-semibold text-base-content/80 flex items-center gap-3">
                                                <i class="bi {{ $detail['icon'] }} text-primary text-lg"></i>
                                                <span>{{ $detail['label'] }}</span>
                                            </div>
                                            <div class="w-full sm:w-2/3 text-base-content">
                                                {{ $detail['value'] }}
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="flex items-center py-3">
                                        <div class="w-full sm:w-1/3 font-semibold text-base-content/80 flex items-center gap-3">
                                            <i class="bi bi-info-circle text-primary text-lg"></i>
                                            <span>{{ __('competency.skkni_status') }}</span>
                                        </div>
                                        <div class="w-full sm:w-2/3">
                                            @php
                                                $availability = strtolower((string) ($skkni['availability'] ?? ''));
                                                $statusLabel = match ($availability) {
                                                    'applied' => __('competency.skkni_status_applied'),
                                                    'replaced' => __('competency.skkni_status_replaced'),
                                                    'cancelled' => __('competency.skkni_status_cancelled'),
                                                    default => __('competency.unknown_status'),
                                                };
                                                $buttonClasses = match ($availability) {
                                                    'applied' => 'bg-green-500 border-green-500 text-white',
                                                    'replaced' => 'btn-warning',
                                                    'cancelled' => 'bg-red-500 border-red-500 text-white',
                                                    default => 'btn-ghost',
                                                };
                                            @endphp
                                            <span class="btn {{ $buttonClasses }}">{{ $statusLabel }}</span>
                                            @if (!empty($skkni['notes']))
                                                <p class="mt-2 text-sm text-base-content/70">
                                                    {{ $skkni['notes'] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                                                    @else
                                                        <div class="overflow-x-auto">
                                                            <table class="table table-zebra w-full">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>{{ __('competency.unit_code') }}</th>
                                                                        <th>{{ __('competency.unit_title') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse ($units as $index => $unit)
                                                                        <tr>
                                                                            <td>{{ $units->firstItem() + $index }}</td>
                                                                            <td>{{ $unit['code'] ?? '-' }}</td>
                                                                            <td>{{ $unit['title'] ?? '-' }}</td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="3" class="text-center text-base-content/60 py-6">
                                                                                <em>{{ __('competency.item_not_found', ['item' => __('competency.unit')]) }}</em>
                                                                            </td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @if ($units->hasPages())
                                                            <div class="mt-4">
                                                                {{ $units->links() }}
                                                            </div>
                                                        @endif
                                                    @endif                    </div>

                    <div class="card-footer flex flex-col sm:flex-row justify-center items-center gap-4 bg-base-200/60 px-8 py-4 border-t border-base-300 mt-6">
                        @if (!empty($skkni['skkni_id']))
                            <a href="{{ route('competency.skkni.download', ['skkniId' => $skkni['skkni_id'], 'slug' => \Illuminate\Support\Str::slug($skkni['judul'] ?? '')]) }}"
                                class="btn btn-primary gap-2">
                                <i class="bi bi-download"></i> {{ __('competency.download_document') }}
                            </a>
                        @endif
                        <a wire:navigate href="{{ route('competency.section', ['section' => 'skkni']) }}"
                            class="btn btn-outline gap-2">
                            <i class="bi bi-arrow-left"></i> {{ __('competency.back') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
        @include('livewire.competency.partials.quick-menu')
    </div>
</div>