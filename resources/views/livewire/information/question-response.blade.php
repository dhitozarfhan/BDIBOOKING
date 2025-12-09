@section('title', __('Complaint Response'))

<div>
    <div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $breadcrumbs = [
                    ['label' => __('Beranda'), 'url' => route('home')],
                    ['label' => __('Public Complaint'), 'url' => route('information.question')],
                    ['label' => __('Report Status'), 'url' => route('information.question.status')],
                    ['label' => __('Report Response')]
                ];
            @endphp
            @include('livewire.information.partials.breadcrumb', ['items' => $breadcrumbs])
            <h2 class="text-2xl font-bold text-base-content mt-4">
                {{ __('Complaint Response') }}
            </h2>

            @if($reportDetail)
                <div class="mt-6">
                    <div class="bg-base-100 rounded-xl shadow-md border border-base-300 overflow-hidden">
                        <table class="table table-zebra w-full">
                            <tbody>
                                <tr>
                                    <th class="w-1/3 bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-person mr-2 text-primary"></i>{{ __('Reporter Name') }}
                                    </th>
                                    <td class="font-medium">{{ $reportDetail->reporter_name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-chat-left-text mr-2 text-primary"></i>{{ __('Report Title') }}
                                    </th>
                                    <td>{{ $reportDetail->report_title }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-telephone mr-2 text-primary"></i>{{ __('Mobile') }}
                                    </th>
                                    <td>{{ $reportDetail->mobile }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-calendar-event mr-2 text-primary"></i>{{ __('Submitted At') }}
                                    </th>
                                    <td>{{ $reportDetail->time_insert ? $reportDetail->time_insert->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-flag mr-2 text-primary"></i>{{ __('Report Status') }}
                                    </th>
                                    <td>
                                        @if($reportDetail->status === \App\Enums\ResponseStatus::Initiation->value)
                                            <span class="badge badge-warning gap-2">
                                                <i class="bi bi-hourglass-split"></i>{{ __('Initiation') }}
                                            </span>
                                        @elseif($reportDetail->status === \App\Enums\ResponseStatus::Process->value)
                                            <span class="badge badge-info gap-2">
                                                <i class="bi bi-arrow-repeat"></i>{{ __('Process') }}
                                            </span>
                                        @elseif($reportDetail->status === \App\Enums\ResponseStatus::Disposition->value)
                                            <span class="badge badge-primary gap-2">
                                                <i class="bi bi-send-check"></i>{{ __('Disposition') }}
                                            </span>
                                        @elseif($reportDetail->status === \App\Enums\ResponseStatus::Termination->value)
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
                                        <i class="bi bi-file-text mr-2 text-primary"></i>{{ __('Content') }}
                                    </th>
                                    <td class="whitespace-pre-wrap">{{ $reportDetail->content }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Card untuk Answer, hanya muncul jika status Termination --}}
                    @if($reportDetail->status === \App\Enums\ResponseStatus::Termination->value && $reportDetail->answer)
                        <div class="mt-6 bg-base-100 rounded-xl shadow-md border border-base-300 p-4">
                            <h3 class="text-lg font-semibold text-base-content mb-3">
                                {{ __('Answer') }}
                            </h3>
                            <div class="whitespace-pre-wrap text-sm text-left">
                                {!! $reportDetail->answer !!}
                            </div>
                        </div>
                    @endif

                    {{-- Answer Attachment --}}
                    @if($reportDetail->status === \App\Enums\ResponseStatus::Termination->value && $reportDetail->answer_attachment)
                        <div class="mt-6 bg-base-100 rounded-xl shadow-md border border-base-300 p-4">
                            <h3 class="text-lg font-semibold text-base-content mb-3">
                                <i class="bi bi-paperclip mr-2 text-secondary"></i>{{ __('Answer Attachment') }}
                            </h3>
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('download', ['path' => $reportDetail->answer_attachment]) }}" target="_blank" class="btn btn-sm btn-outline btn-primary gap-2">
                                    <i class="bi bi-download"></i>
                                    {{ basename($reportDetail->answer_attachment) }}
                                </a>
                                @if(pathinfo($reportDetail->answer_attachment, PATHINFO_EXTENSION) === 'pdf')
                                    <div class="mt-2 border rounded">
                                        <iframe src="{{ route('download', ['path' => $reportDetail->answer_attachment]) }}" 
                                                class="w-full h-96" 
                                                type="application/pdf"
                                                title="Answer Attachment Preview">
                                            <p>{{ __('Your browser does not support PDF previews. Please download the file to view it.') }}</p>
                                        </iframe>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('information.question.status') }}" class="btn btn-ghost btn-outline" wire:navigate>
                           {{ __('Back to Status Check') }}
                        </a>
                        <a href="{{ route('information.question') }}" class="btn btn-primary" wire:navigate>
                            {{ __('Back to Form') }}
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
                    <a href="{{ route('information.question.status') }}" class="btn btn-primary">
                        {{ __('Try Again') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
