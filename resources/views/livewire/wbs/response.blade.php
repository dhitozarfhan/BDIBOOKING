<div>
    <div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $breadcrumbs = [
                    ['label' => __('Beranda'), 'url' => route('home')],
                    ['label' => __('WBS Reporting'), 'url' => route('wbs')],
                    ['label' => __('Report Status'), 'url' => route('wbs.status')],
                    ['label' => __('Report Response')]
                ];
            @endphp
            @include('livewire.wbs.partials.breadcrumb', ['items' => $breadcrumbs])
            <h2 class="text-2xl font-bold text-base-content mt-4">
                {{ __('WBS Report Response') }}
            </h2>

            @if($reportDetail)
                <div class="mt-6">
                    <div class="bg-base-100 rounded-xl shadow-md border border-base-300 overflow-hidden">
                        <table class="table table-zebra w-full">
                            <tbody>
                                <tr>
                                    <th class="w-1/3 bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-chat-left-text mr-2 text-primary"></i>{{ __('Report Subject') }}
                                    </th>
                                    <td class="font-medium">{{ $reportDetail->judul_laporan }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-person mr-2 text-primary"></i>{{ __('Reporter Name') }}
                                    </th>
                                    <td>{{ $reportDetail->nama_pelapor }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-telephone mr-2 text-primary"></i>{{ __('Reporter Phone') }}
                                    </th>
                                    <td>{{ $reportDetail->telepon }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-calendar-event mr-2 text-primary"></i>{{ __('Report Date') }}
                                    </th>
                                    <td>{{ $reportDetail->created_at ? $reportDetail->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-flag mr-2 text-primary"></i>{{ __('Report Status') }}
                                    </th>
                                    <td>
                                        @if($reportDetail->processes->last()?->status == 'I')
                                            <span class="badge badge-warning gap-2">
                                                <i class="bi bi-hourglass-split"></i>{{ __('Initiation') }}
                                            </span>
                                        @elseif($reportDetail->processes->last()?->status == 'P')
                                            <span class="badge badge-info gap-2">
                                                <i class="bi bi-arrow-repeat"></i>{{ __('Process') }}
                                            </span>
                                        @elseif($reportDetail->processes->last()?->status == 'D')
                                            <span class="badge badge-primary gap-2">
                                                <i class="bi bi-send-check"></i>{{ __('Disposition') }}
                                            </span>
                                        @elseif($reportDetail->processes->last()?->status == 'T')
                                            <span class="badge badge-success gap-2">
                                                <i class="bi bi-check-circle"></i>{{ __('Completed') }}
                                            </span>
                                        @else
                                            <span class="badge badge-ghost gap-2">
                                                <i class="bi bi-question-circle"></i>{{ __('Unknown Status') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content align-top">
                                        <i class="bi bi-file-text mr-2 text-primary"></i>{{ __('Report Content') }}
                                    </th>
                                    <td class="whitespace-pre-wrap">{{ $reportDetail->uraian_laporan }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content align-top">
                                        <i class="bi bi-reply mr-2 text-secondary"></i>{{ __('Answer') }}
                                    </th>
                                    <td class="whitespace-pre-wrap">
                                        @if($reportDetail->processes->last()?->status == 'T' && $reportDetail->processes->last()?->jawaban)
                                            <div class="bg-success/10 border-l-4 border-success p-3 rounded">
                                                {{ $reportDetail->processes->last()->jawaban }}
                                            </div>
                                        @else
                                            <div class="bg-base-200 border-l-4 border-base-300 p-3 rounded text-base-content/60 italic">
                                                {{ __('No official response to this report yet.') }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @if($reportDetail->data_dukung)
                                    <tr>
                                        <th class="bg-base-200 font-semibold text-base-content">
                                            <i class="bi bi-paperclip mr-2 text-secondary"></i>{{ __('Report Attachment') }}
                                        </th>
                                        <td>
                                            <div class="flex flex-col gap-2">
                                                <a href="{{ Storage::url($reportDetail->data_dukung) }}" target="_blank" class="btn btn-sm btn-outline btn-primary gap-2">
                                                    <i class="bi bi-download"></i>
                                                    {{ basename($reportDetail->data_dukung) }}
                                                </a>
                                                @if(pathinfo($reportDetail->data_dukung, PATHINFO_EXTENSION) === 'pdf')
                                                    <div class="mt-2 border rounded">
                                                        <iframe src="{{ Storage::url($reportDetail->data_dukung) }}"
                                                                class="w-full h-96"
                                                                type="application/pdf"
                                                                title="Report Attachment Preview">
                                                            <p>{{ __('Your browser does not support PDF previews. Please download the file to view it.') }}</p>
                                                        </iframe>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('wbs.status') }}" class="btn btn-ghost">
                            {{ __('Back to Status Check') }}
                        </a>
                        <a href="{{ route('wbs') }}" class="btn btn-primary">
                            {{ __('Back to Home') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="alert alert-error mt-6">
                    <div class="flex-1">
                        <i class="bi bi-exclamation-triangle-fill text-xl mr-3"></i>
                        <span>{{ __('Report not found. Please check the registration code.') }}</span>
                    </div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('wbs.status') }}" class="btn btn-primary">
                        {{ __('Try Again') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>