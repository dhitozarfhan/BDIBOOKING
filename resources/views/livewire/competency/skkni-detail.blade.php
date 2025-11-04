<div class="bg-base-200 min-h-screen">
    <section class="bg-gradient-to-r from-cyan-900 to-cyan-700 text-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 text-center">
            <h1 class="text-3xl md:text-5xl font-bold mb-3">
                {{ $skkni['judul'] ?? __('competency.skkni_details') }}
            </h1>
            <p class="max-w-3xl mx-auto text-base md:text-lg text-white/80">
                {{ __('competency.skkni_details_subtitle') }}
            </p>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
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
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <tbody>
                                    <tr>
                                        <th class="w-48">{{ __('competency.skkni_title') }}</th>
                                        <td>
                                            <h2 class="text-xl font-semibold">{{ $skkni['judul'] ?? '-' }}</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('competency.skkni_code') }}</th>
                                        <td>{{ $skkni['kode_skkni'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('competency.skkni_sector') }}</th>
                                        <td>{{ $skkni['bidang'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('competency.skkni_agency') }}</th>
                                        <td>{{ $skkni['agency'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('competency.skkni_category') }}</th>
                                        <td>{{ $skkni['category'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('competency.skkni_core') }}</th>
                                        <td>{{ $skkni['core'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('competency.skkni_ministerial_decree_number') }}</th>
                                        <td>{{ $skkni['nomor'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('competency.created_date') }}</th>
                                        <td>
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
                                            {{ $createdLabel ?? '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('competency.skkni_status') }}</th>
                                        <td>
                                            @php
                                                $availability = strtolower((string) ($skkni['availability'] ?? ''));
                                                $statusLabel = match ($availability) {
                                                    'applied' => __('competency.skkni_status_applied'),
                                                    'replaced' => __('competency.skkni_status_replaced'),
                                                    'cancelled' => __('competency.skkni_status_cancelled'),
                                                    default => __('competency.unknown_status'),
                                                };
                                                $badgeClass = match ($availability) {
                                                    'applied' => 'badge-success',
                                                    'replaced' => 'badge-warning',
                                                    'cancelled' => 'badge-error',
                                                    default => 'badge',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                            @if (!empty($skkni['notes']))
                                                <p class="mt-2 text-sm text-base-content/70">
                                                    {{ $skkni['notes'] }}
                                                </p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('competency.skkni_job_title') }}</th>
                                        <td>{{ $skkni['job_title'] ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
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
                                            <td>{{ $index + 1 }}</td>
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
                    @endif
                </div>

                <div class="card-footer flex flex-col sm:flex-row justify-center gap-3 bg-base-200/60">
                    @if (!empty($skkni['skkni_id']))
                        <a href="{{ route('competency.skkni.download', ['skkniId' => $skkni['skkni_id'], 'slug' => \Illuminate\Support\Str::slug($skkni['judul'] ?? '')]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-download mr-2"></i> {{ __('competency.download_document') }}
                        </a>
                    @endif
                    <a wire:navigate href="{{ route('competency.section', ['section' => 'skkni']) }}"
                        class="btn btn-outline">
                        {{ __('competency.back') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@include('livewire.competency.partials.quick-menu')
