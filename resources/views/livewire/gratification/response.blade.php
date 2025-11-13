<div>
    <div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $breadcrumbs = [
                    ['label' => __('Beranda'), 'url' => route('home')],
                    ['label' => __('Gratification Reporting'), 'url' => route('gratification')],
                    ['label' => __('Report Status'), 'url' => route('gratification.status')],
                    ['label' => __('Report Response')]
                ];
            @endphp
            @include('livewire.gratification.partials.breadcrumb', ['items' => $breadcrumbs])
            <h2 class="text-2xl font-bold text-base-content mt-4">
                {{ __('Gratification Report Response') }}
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
                                    <td class="font-medium">{{ $reportDetail->subject }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-person mr-2 text-primary"></i>{{ __('Reporter Name') }}
                                    </th>
                                    <td>{{ $reportDetail->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-telephone mr-2 text-primary"></i>{{ __('Reporter Phone') }}
                                    </th>
                                    <td>{{ $reportDetail->mobile }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-calendar-event mr-2 text-primary"></i>{{ __('Report Date') }}
                                    </th>
                                    <td>{{ $reportDetail->time_insert ? $reportDetail->time_insert->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-flag mr-2 text-primary"></i>{{ __('Report Status') }}
                                    </th>
                                    <td>
                                        @if($reportDetail->status === \App\Enums\ResponseStatus::Initiation)
                                            <span class="badge badge-warning gap-2">
                                                <i class="bi bi-hourglass-split"></i>{{ __('Initiation') }}
                                            </span>
                                        @elseif($reportDetail->status === \App\Enums\ResponseStatus::Process)
                                            <span class="badge badge-info gap-2">
                                                <i class="bi bi-arrow-repeat"></i>{{ __('Process') }}
                                            </span>
                                        @elseif($reportDetail->status === \App\Enums\ResponseStatus::Disposition)
                                            <span class="badge badge-primary gap-2">
                                                <i class="bi bi-send-check"></i>{{ __('Disposition') }}
                                            </span>
                                        @elseif($reportDetail->status === \App\Enums\ResponseStatus::Termination)
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
                                    <td class="whitespace-pre-wrap">{{ $reportDetail->content }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content align-top">
                                        <i class="bi bi-reply mr-2 text-secondary"></i>{{ __('Answer') }}
                                    </th>
                                    <td class="whitespace-pre-wrap">
                                        @if($reportDetail->status === \App\Enums\ResponseStatus::Termination)
                                            <div class="bg-success/10 border-l-4 border-success p-3 rounded">
                                                {!! $reportDetail->answer ?? __('No answer available.') !!}
                                            </div>
                                        @else
                                            <div class="bg-base-200 border-l-4 border-base-300 p-3 rounded text-base-content/60 italic">
                                                {{ __('No official response to this report yet.') }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @if($reportDetail->status === \App\Enums\ResponseStatus::Termination && $reportDetail->attachment)
                                    <tr>
                                        <th class="bg-base-200 font-semibold text-base-content">
                                            <i class="bi bi-paperclip mr-2 text-secondary"></i>{{ __('Report Attachment') }}
                                        </th>
                                        <td>
                                            <div class="flex flex-col gap-2">
                                                <a href="{{ Storage::url($reportDetail->attachment) }}" target="_blank" class="btn btn-sm btn-outline btn-primary gap-2">
                                                    <i class="bi bi-download"></i>
                                                    {{ basename($reportDetail->attachment) }}
                                                </a>
                                                @if(pathinfo($reportDetail->attachment, PATHINFO_EXTENSION) === 'pdf')
                                                    <div class="mt-2 border rounded">
                                                        <iframe src="{{ Storage::url($reportDetail->attachment) }}" 
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
                                @if($reportDetail->status === \App\Enums\ResponseStatus::Termination && $reportDetail->answer_attachment)
                                    <tr>
                                        <th class="bg-base-200 font-semibold text-base-content">
                                            <i class="bi bi-paperclip mr-2 text-secondary"></i>{{ __('Answer Attachment') }}
                                        </th>
                                        <td>
                                            <div class="flex flex-col gap-2">
                                                <a href="{{ Storage::url($reportDetail->answer_attachment) }}" target="_blank" class="btn btn-sm btn-outline btn-primary gap-2">
                                                    <i class="bi bi-download"></i>
                                                    {{ basename($reportDetail->answer_attachment) }}
                                                </a>
                                                @if(pathinfo($reportDetail->answer_attachment, PATHINFO_EXTENSION) === 'pdf')
                                                    <div class="mt-2 border rounded">
                                                        <iframe src="{{ Storage::url($reportDetail->answer_attachment) }}" 
                                                                class="w-full h-96" 
                                                                type="application/pdf"
                                                                title="Answer Attachment Preview">
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
                        <a href="{{ route('gratification.status') }}" class="btn btn-ghost btn-outline" wire:navigate>
                           {{ __('Back') }}
                        </a>
                    </div>
                </div>
            @elseif($statusError)
                <div class="mt-4 alert alert-error shadow-lg">
                    <div>
                        <span>{{ $statusError }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
