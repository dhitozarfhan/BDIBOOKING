<div>
    <div class="bg-base-200 min-h-screen">
        <section class="bg-gradient-to-r from-cyan-900 to-cyan-700 text-white">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 text-center">
                <h1 class="text-3xl md:text-5xl font-bold mb-3">
                    {{ $lsp['nama'] ?? __('competency.lsp_details') }}
                </h1>
                <p class="max-w-3xl mx-auto text-base md:text-lg text-white/80">
                    {{ __('competency.lsp_details_subtitle') }}
                </p>
            </div>
        </section>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12">
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
                            <button wire:click="setTab('lsp')"
                                class="tab {{ $activeTab === 'lsp' ? 'tab-active' : '' }}">
                                {{ __('competency.details') }}
                            </button>
                            <button wire:click="setTab('assessor')"
                                class="tab {{ $activeTab === 'assessor' ? 'tab-active' : '' }}">
                                {{ __('competency.assessor_data') }}
                            </button>
                            <button wire:click="setTab('tuk')"
                                class="tab {{ $activeTab === 'tuk' ? 'tab-active' : '' }}">
                                {{ __('competency.tuk_data') }}
                            </button>
                            <button wire:click="setTab('scheme')"
                                class="tab {{ $activeTab === 'scheme' ? 'tab-active' : '' }}">
                                {{ __('competency.scheme_data') }}
                            </button>
                        </div>

                        @if ($activeTab === 'lsp')
                            <div class="grid gap-6 lg:grid-cols-3">
                                <div class="lg:col-span-2 overflow-x-auto">
                                    <table class="table w-full">
                                        <tbody>
                                            <tr>
                                                <th class="w-48">{{ __('competency.lsp_name') }}</th>
                                                <td>
                                                    <h2 class="text-xl font-semibold">{{ $lsp['nama'] ?? '-' }}</h2>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('competency.address') }}</th>
                                                <td>
                                                    <p>{{ $lsp['alamat'] ?? '-' }}</p>
                                                    <p class="text-sm text-base-content/70">
                                                        {{ $lsp['kota'] ?? '-' }}, {{ $lsp['provinsi'] ?? '-' }}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('competency.decision_letter_number') }}</th>
                                                <td>{{ $lsp['nomor_sk'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('competency.license_number') }}</th>
                                                <td>{{ $lsp['nomor_lisensi'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('competency.phone') }}</th>
                                                <td>{{ $lsp['telepon'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('competency.handphone') }}</th>
                                                <td>{{ $lsp['mobile'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('competency.fax') }}</th>
                                                <td>{{ $lsp['fax'] ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('competency.email') }}</th>
                                                <td>
                                                    @if (!empty($lsp['email']))
                                                        <a href="mailto:{{ $lsp['email'] }}"
                                                            class="text-primary hover:underline">{{ $lsp['email'] }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('competency.website') }}</th>
                                                <td>
                                                    @if (!empty($lsp['website']))
                                                        <a href="{{ $lsp['website'] }}" target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="text-primary hover:underline">{{ $lsp['website'] }}</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('competency.letter_expiration') }}</th>
                                                <td>
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
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex items-start justify-center">
                                    @if (!empty($lsp['image']))
                                        <img src="{{ $lsp['image'] }}" alt="{{ $lsp['nama'] ?? 'LSP' }}"
                                            class="rounded-xl border border-base-200 shadow-md max-w-xs">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center border-2 border-dashed border-base-300 rounded-xl text-base-content/40 p-10">
                                            <i class="bi bi-image text-4xl mb-3"></i>
                                            <p>{{ __('competency.no_logo') }}</p>
                                        </div>
                                    @endif
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
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="font-semibold">{{ $assessor['nama'] ?? '-' }}</div>
                                                    @php
                                                        $contact = [];
                                                        if (!empty($assessor['mobile'])) {
                                                            $contact[] = '<i class="fas fa-phone"></i> ' . e($assessor['mobile']);
                                                        }
                                                        if (!empty($assessor['email'])) {
                                                            $contact[] = '<i class="fas fa-envelope"></i> ' . e($assessor['email']);
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
                                                    $icon = $gender === 'L' ? 'mars' : ($gender === 'P' ? 'venus' : 'user');
                                                    $toneClass = $gender === 'L' ? 'text-primary' : ($gender === 'P' ? 'text-secondary' : 'text-base-content/60');
                                                    $genderLabel = match ($gender) {
                                                        'L' => __('competency.gender_male'),
                                                        'P' => __('competency.gender_female'),
                                                        default => __('competency.gender_unknown'),
                                                    };
                                                @endphp
                                                <td>
                                                    <div class="inline-flex items-center gap-2">
                                                        <i class="fas fa-{{ $icon }} {{ $toneClass }}"></i>
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
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
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
                        @elseif ($activeTab === 'scheme')
                            <div class="space-y-4">
                                @forelse ($schemes as $scheme)
                                    @php
                                        $schemeId = $scheme['id_skema'] ?? null;
                                        $unitCount = (int) ($scheme['total_unit'] ?? 0);
                                        $isExpanded = $expandedSchemeId === $schemeId;
                                        $unitList = $schemeId ? ($unitsByScheme[$schemeId] ?? []) : [];
                                    @endphp
                                    <div class="border border-base-200 rounded-xl shadow-sm overflow-hidden">
                                        <button type="button" wire:click="toggleScheme({{ $schemeId ?? 0 }})"
                                            class="w-full px-4 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-base-100 hover:bg-base-200 transition">
                                            <div>
                                                <h3 class="text-lg font-semibold text-base-content">
                                                    {{ $scheme['nama'] ?? '-' }}
                                                </h3>
                                                <p class="text-sm text-base-content/70">
                                                    {{ __('competency.scheme_code') }}:
                                                    <span class="font-mono">{{ $scheme['kode'] ?? '-' }}</span>
                                                    &bull;
                                                    {{ __('competency.scheme_type') }}:
                                                    <span class="font-semibold">{{ $scheme['jenis'] ?? '-' }}</span>
                                                </p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="badge badge-primary">
                                                    {{ trans_choice('competency.unit_count_label', $unitCount, ['count' => $unitCount]) }}
                                                </span>
                                                <i
                                                    class="fas fa-chevron-{{ $isExpanded ? 'up' : 'down' }} text-base-content/70"></i>
                                            </div>
                                        </button>
                                        <div class="{{ $isExpanded ? 'block' : 'hidden' }}">
                                            <div class="px-4 pb-4">
                                                <p class="text-sm text-base-content/70 mb-3">
                                                    {{ __('competency.scheme_sector') }}: {{ $scheme['bidang'] ?? '-' }}
                                                    &bull;
                                                    {{ __('competency.scheme_subsector') }}:
                                                    {{ $scheme['subsektor'] ?? '-' }}
                                                </p>
                                                <div class="overflow-x-auto">
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
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="border border-dashed border-base-300 rounded-xl text-center py-12 text-base-content/60">
                                        <i class="fas fa-box-open text-3xl mb-3"></i>
                                        <p>{{ __('competency.item_not_found', ['item' => __('competency.competency_scheme')]) }}</p>
                                    </div>
                                @endforelse
                            </div>
                        @endif
                    </div>

                    <div class="card-footer flex flex-col sm:flex-row justify-center gap-3 bg-base-200/60">
                        <a wire:navigate href="{{ route('competency.section', ['section' => 'lsp']) }}"
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
