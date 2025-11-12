<div>
    <div class="bg-base-200 min-h-screen">
        <section class="bg-gradient-to-r from-cyan-900 to-cyan-700 text-base-content">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
                @php
                    $breadcrumbs = [
                        ['label' => __('Beranda'), 'url' => route('home')],
                        ['label' => __('competency.industrial_hr_competency'), 'url' => route('competency.index')],
                        ['label' => __('competency.competency_lsp'), 'url' => route('competency.section', ['section' => 'lsp'])],
                        ['label' => $lsp['nama'] ?? __('competency.lsp_details')]
                    ];
                @endphp
                @include('livewire.competency.partials.breadcrumb', ['items' => $breadcrumbs])

                <div class="text-center mt-4">
                    <h1 class="text-3xl md:text-5xl font-bold mb-3">
                        {{ $lsp['nama'] ?? __('competency.lsp_details') }}
                    </h1>
                    <p class="max-w-3xl mx-auto text-base md:text-lg text-white/80">
                        {{ __('competency.lsp_details_subtitle') }}
                    </p>
                </div>
            </div>
        </section>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
            @if ($error)
                <div role="alert" class="alert alert-error shadow-lg">
                    <i class="bi bi-exclamation-triangle text-xl"></i>
                    <div>
                        <h3 class="font-bold">{{ __('competency.error_title') }}</h3>
                        <div class="text-sm">{{ $error }}</div>
                    </div>
                </div>
            @else
                <div class="card bg-base-100 shadow-xl border border-base-200">
                    <div class="card-body">
                        <div class="tabs tabs-boxed mb-6">
                            <button wire:click="setTab('lsp')"
                                class="tab {{ $activeTab === 'lsp' ? 'tab-active text-blue-600 font-bold' : '' }}">
                                <i class="bi bi-list-ul mr-2"></i>
                                {{ __('competency.details') }}
                            </button>
                            <button wire:click="setTab('assessor')"
                                class="tab {{ $activeTab === 'assessor' ? 'tab-active text-blue-600 font-bold' : '' }}">
                                <i class="bi bi-people mr-2"></i>
                                {{ __('competency.assessor_data') }}
                            </button>
                            <button wire:click="setTab('tuk')"
                                class="tab {{ $activeTab === 'tuk' ? 'tab-active text-blue-600 font-bold' : '' }}">
                                <i class="bi bi-building mr-2"></i>
                                {{ __('competency.tuk_data') }}
                            </button>
                            <button wire:click="setTab('scheme')"
                                class="tab {{ $activeTab === 'scheme' ? 'tab-active text-blue-600 font-bold' : '' }}">
                                <i class="bi bi-diagram-3 mr-2"></i>
                                {{ __('competency.scheme_data') }}
                            </button>
                        </div>

                        @if ($activeTab === 'lsp')
                            <div class="flex flex-col items-center gap-8">
                                <div class="flex items-center justify-center">
                                    @if (!empty($lsp['image']))
                                        <img src="{{ $lsp['image'] }}" alt="{{ $lsp['nama'] ?? 'LSP' }}"
                                            class="rounded-xl border border-base-200 shadow-md max-w-xs">
                                    @else
                                        <div
                                            class="w-full h-full flex flex-col items-center justify-center border-2 border-dashed border-base-300 rounded-xl text-base-content/40 p-10">
                                            <i class="bi bi-image text-4xl mb-3"></i>
                                            <p>{{ __('competency.no_logo') }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="w-full">
                                    <dl class="divide-y divide-base-200">
                                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                            <dt class="text-sm font-medium text-base-content/70">{{ __('competency.lsp_name') }}</dt>
                                            <dd class="mt-1 text-sm text-base-content sm:mt-0 sm:col-span-2">
                                                <h2 class="text-xl font-semibold">{{ $lsp['nama'] ?? '-' }}</h2>
                                            </dd>
                                        </div>
                                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                            <dt class="text-sm font-medium text-base-content/70">{{ __('competency.address') }}</dt>
                                            <dd class="mt-1 text-sm text-base-content sm:mt-0 sm:col-span-2">
                                                <p>{{ $lsp['alamat'] ?? '-' }}</p>
                                                <p class="text-sm text-base-content/70">
                                                    {{ $lsp['kota'] ?? '-' }}, {{ $lsp['provinsi'] ?? '-' }}
                                                </p>
                                            </dd>
                                        </div>
                                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                            <dt class="text-sm font-medium text-base-content/70">{{ __('competency.decision_letter_number') }}</dt>
                                            <dd class="mt-1 text-sm text-base-content sm:mt-0 sm:col-span-2">{{ $lsp['nomor_sk'] ?? '-' }}</dd>
                                        </div>
                                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                            <dt class="text-sm font-medium text-base-content/70">{{ __('competency.license_number') }}</dt>
                                            <dd class="mt-1 text-sm text-base-content sm:mt-0 sm:col-span-2">{{ $lsp['nomor_lisensi'] ?? '-' }}</dd>
                                        </div>
                                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                            <dt class="text-sm font-medium text-base-content/70">{{ __('competency.phone') }}</dt>
                                            <dd class="mt-1 text-sm text-base-content sm:mt-0 sm:col-span-2">{{ $lsp['telepon'] ?? '-' }}</dd>
                                        </div>
                                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                            <dt class="text-sm font-medium text-base-content/70">{{ __('competency.handphone') }}</dt>
                                            <dd class="mt-1 text-sm text-base-content sm:mt-0 sm:col-span-2">{{ $lsp['mobile'] ?? '-' }}</dd>
                                        </div>
                                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                            <dt class="text-sm font-medium text-base-content/70">{{ __('competency.fax') }}</dt>
                                            <dd class="mt-1 text-sm text-base-content sm:mt-0 sm:col-span-2">{{ $lsp['fax'] ?? '-' }}</dd>
                                        </div>
                                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                            <dt class="text-sm font-medium text-base-content/70">{{ __('competency.email') }}</dt>
                                            <dd class="mt-1 text-sm text-base-content sm:mt-0 sm:col-span-2">
                                                @if (!empty($lsp['email']))
                                                    <a href="mailto:{{ $lsp['email'] }}"
                                                        class="text-blue-600 hover:underline">{{ $lsp['email'] }}</a>
                                                @else
                                                    -
                                                @endif
                                            </dd>
                                        </div>
                                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                            <dt class="text-sm font-medium text-base-content/70">{{ __('competency.website') }}</dt>
                                            <dd class="mt-1 text-sm text-base-content sm:mt-0 sm:col-span-2">
                                                @if (!empty($lsp['website']))
                                                    <a href="{{ $lsp['website'] }}" target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="text-blue-600 hover:underline">{{ $lsp['website'] }}</a>
                                                @else
                                                    -
                                                @endif
                                            </dd>
                                        </div>
                                        <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                            <dt class="text-sm font-medium text-base-content/70">{{ __('competency.letter_expiration') }}</dt>
                                            <dd class="mt-1 text-sm text-base-content sm:mt-0 sm:col-span-2">
                                                @php
                                                    $expired = ($lsp['is_expired'] ?? 'N') === 'Y';
                                                    try {
                                                        $expiresAt = !empty($lsp['kadaluwarsa_sk'])
                                                            ? \Carbon\Carbon::parse($lsp['kadaluwarsa_sk'])->isoFormat('D MMMM YYYY')
                                                            : null;
                                                    } catch (\Exception $e) {
                                                        $expiresAt = $lsp['kadaluwarsa_sk'] ?? null;
                                                    }
                                                @endphp
                                                <span
                                                    class="badge {{ $expired ? 'badge-error' : 'badge-success' }}">
                                                    {{ $expired ? __('competency.expired') : __('competency.applied') }}
                                                </span>
                                                @if ($expiresAt)
                                                    <span class="ml-2 text-sm text-base-content/70">
                                                        {{ $expired ? __('competency.from') : __('competency.until') }}
                                                        {{ $expiresAt }}
                                                    </span>
                                                @endif
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        @elseif ($activeTab === 'assessor')
                            <div class="overflow-x-auto">
                                <table class="table table-zebra w-full">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('competency.assessor_name') }}</th>
                                            <th>{{ __('competency.m_or_f') }}</th>
                                            <th>{{ __('competency.register_number') }}</th>
                                            <th>{{ __('competency.education') }}</th>
                                            <th>{{ __('competency.profession') }}</th>
                                            <th>{{ __('competency.address') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($assessors as $index => $assessor)
                                            <tr class="hover">
                                                <td>{{ $assessors->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="font-semibold">{{ $assessor['nama'] ?? '-' }}</div>
                                                    @php
                                                        $contact = [];
                                                        if (!empty($assessor['mobile'])) {
                                                            $contact[] = '<i class="bi bi-phone"></i> ' . e($assessor['mobile']);
                                                        }
                                                        if (!empty($assessor['email'])) {
                                                            $contact[] = '<i class="bi bi-envelope"></i> ' . e($assessor['email']);
                                                        }
                                                    @endphp
                                                    @if (!empty($contact))
                                                        <div class="text-sm text-base-content/70 space-x-2">
                                                            {!! implode(' &nbsp; ', $contact) !!}
                                                        </div>
                                                    @endif
                                                </td>
                                                @php
                                                    $gender = strtoupper((string) ($assessor['id_kelamin'] ?? ''));
                                                    $icon = $gender === 'L' ? 'bi-gender-male' : ($gender === 'P' ? 'bi-gender-female' : 'bi-person');
                                                    $toneClass = $gender === 'L' ? 'text-primary' : ($gender === 'P' ? 'text-secondary' : 'text-base-content/60');
                                                    $genderLabel = match ($gender) {
                                                        'L' => __('competency.gender_male'),
                                                        'P' => __('competency.gender_female'),
                                                        default => __('competency.gender_unknown'),
                                                    };
                                                @endphp
                                                <td>
                                                    <div class="inline-flex items-center gap-2">
                                                        <i class="bi {{ $icon }} {{ $toneClass }}"></i>
                                                        <span>{{ $genderLabel }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $assessor['nomor_reg'] ?? '-' }}</td>
                                                <td>{{ $assessor['pendidikan'] ?? '-' }}</td>
                                                <td>{{ $assessor['pekerjaan'] ?? '-' }}</td>
                                                <td>
                                                    {{ $assessor['kota'] ?? '-' }}, {{ $assessor['provinsi'] ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-base-content/60 py-6">
                                                    <em>{{ __('competency.item_not_found', ['item' => __('competency.assessor')]) }}</em>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($assessors->hasPages())
                                <div class="mt-4">
                                    {{ $assessors->links() }}
                                </div>
                            @endif
                        @elseif ($activeTab === 'tuk')
                            <div class="overflow-x-auto">
                                <table class="table table-zebra w-full">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('competency.tuk_name') }}</th>
                                            <th>{{ __('competency.tuk_type') }}</th>
                                            <th>{{ __('competency.tuk_code') }}</th>
                                            <th>{{ __('competency.address') }}</th>
                                            <th>{{ __('competency.handphone') }}</th>
                                            <th>{{ __('competency.phone') }}</th>
                                            <th>{{ __('competency.fax') }}</th>
                                            <th>{{ __('competency.email') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tuk as $index => $item)
                                            <tr class="hover">
                                                <td>{{ $tuk->firstItem() + $index }}</td>
                                                <td>{{ $item['nama'] ?? '-' }}</td>
                                                <td>{{ $item['jenis'] ?? '-' }}</td>
                                                <td>{{ $item['kode'] ?? '-' }}</td>
                                                <td>{{ $item['alamat'] ?? '-' }}</td>
                                                <td>{{ $item['mobile'] ?? '-' }}</td>
                                                <td>{{ $item['telepon'] ?? '-' }}</td>
                                                <td>{{ $item['fax'] ?? '-' }}</td>
                                                <td>{{ $item['email'] ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-base-content/60 py-6">
                                                    <em>{{ __('competency.item_not_found', ['item' => __('competency.tuk')]) }}</em>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($tuk->hasPages())
                                <div class="mt-4">
                                    {{ $tuk->links() }}
                                </div>
                            @endif
                        @elseif ($activeTab === 'scheme')
                            <div class="overflow-x-auto">
                                <table class="table w-full">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('competency.scheme_name') }}</th>
                                            <th>{{ __('competency.scheme_type') }}</th>
                                            <th>{{ __('competency.scheme_code') }}</th>
                                            <th>{{ __('competency.scheme_sector') }}</th>
                                            <th>{{ __('competency.scheme_subsector') }}</th>
                                            <th>{{ __('competency.unit_count') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($schemes as $index => $scheme)
                                            @php
                                                $schemeId = $scheme['id_skema'] ?? null;
                                                $unitCount = (int) ($scheme['total_unit'] ?? 0);
                                                $isExpanded = $expandedSchemeId == $schemeId;
                                                $unitList = $schemeId ? ($unitsByScheme[$schemeId] ?? []) : [];
                                            @endphp
                                            <tr class="hover" wire:key="scheme-{{ $schemeId }}" id="tr-skema-{{ $schemeId }}">
                                                <td>{{ $schemes->firstItem() + $index }}</td>
                                                <td>{{ $scheme['nama'] ?? '-' }}</td>
                                                <td>{{ $scheme['jenis'] ?? '-' }}</td>
                                                <td>{{ $scheme['kode'] ?? '-' }}</td>
                                                <td>{{ $scheme['bidang'] ?? '-' }}</td>
                                                <td>{{ $scheme['subsektor'] ?? '-' }}</td>
                                                <td>
                                                    <button type="button" wire:click="toggleScheme({{ $schemeId ?? 0 }})"
                                                        class="btn btn-sm btn-primary">
                                                        {{ trans_choice('competency.unit_count_label', $unitCount, ['count' => $unitCount]) }}
                                                    </button>
                                                </td>
                                            </tr>
                                            @if ($isExpanded)
                                                <tr wire:key="scheme-units-{{ $schemeId }}">
                                                    <td colspan="7" class="bg-base-200">
                                                        <div class="p-4">
                                                            <h4 class="font-bold mb-2">{{ __('competency.unit_list') }}: {{ $scheme['nama'] ?? '-' }}</h4>
                                                            <table class="table table-zebra w-full">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>{{ __('competency.unit_code') }}</th>
                                                                        <th>{{ __('competency.unit_name') }}</th>
                                                                        <th>{{ __('competency.year') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse ($unitList as $idx => $unit)
                                                                        <tr>
                                                                            <td>{{ $idx + 1 }}</td>
                                                                            <td>{{ $unit['kode'] ?? '-' }}</td>
                                                                            <td>{{ $unit['nama'] ?? '-' }}</td>
                                                                            <td>{{ $unit['tahun'] ?? '-' }}</td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="4" class="text-center text-base-content/60 py-6">
                                                                                <em>{{ __('competency.item_not_found', ['item' => __('competency.unit')]) }}</em>
                                                                            </td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-base-content/60 py-6">
                                                    <em>{{ __('competency.item_not_found', ['item' => __('competency.competency_scheme')]) }}</em>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($schemes->hasPages())
                                <div class="mt-4">
                                    {{ $schemes->links() }}
                                </div>
                            @endif


                        @endif
                    </div>

                    <div class="card-footer flex flex-col sm:flex-row justify-start gap-3 bg-base-200/60 p-4">
                        <a wire:navigate href="{{ $backUrl }}"
                            class="btn btn-outline">
                            {{ __('competency.back') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('livewire.competency.partials.quick-menu')
</div>
