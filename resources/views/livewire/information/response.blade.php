@section('title', __('Question Report Response'))

<div>
    <div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $breadcrumbs = [
                    ['label' => __('Beranda'), 'url' => route('home')],
                    ['label' => __('Question Reporting'), 'url' => route('information.question')],
                    ['label' => __('Report Status'), 'url' => route('information.question.status')],
                    ['label' => __('Report Response')]
                ];
            @endphp
            @include('livewire.information.partials.breadcrumb', ['items' => $breadcrumbs])
            <h2 class="text-2xl font-bold text-base-content mt-4">
                {{ __('Question Report Response') }}
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
                                        <i class="bi bi-file-text mr-2 text-primary"></i>{{ __('Report Content') }}
                                    </th>
                                    <td class="whitespace-pre-wrap">{{ $reportDetail->content }}</td>
                                </tr>
                                @if($reportDetail->attachment)
                                    <tr>
                                        <th class="bg-base-200 font-semibold text-base-content">
                                            <i class="bi bi-paperclip mr-2 text-secondary"></i>{{ __('Report Attachment') }}
                                        </th>
                                        <td>
                                            <div class="flex flex-col gap-2">
                                                <a href="{{ route('download', ['path' => $reportDetail->attachment]) }}" target="_blank" class="btn btn-sm btn-outline btn-primary gap-2">
                                                    <i class="bi bi-download"></i>
                                                    {{ basename($reportDetail->attachment) }}
                                                </a>
                                                @if(pathinfo($reportDetail->attachment, PATHINFO_EXTENSION) === 'pdf')
                                                    <div class="mt-2 border rounded">
                                                        <iframe src="{{ route('download', ['path' => $reportDetail->attachment]) }}"
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

                    {{-- Riwayat Jawaban --}}
                    @if($reportDetail->processes && $reportDetail->processes->count() > 0)
                        <div class="mt-8">
                            <h3 class="text-2xl font-bold text-base-content mb-4">
                                <i class="bi bi-hourglass-split mr-2 text-primary"></i>{{ __('Response History') }}
                            </h3>
                            <ul class="timeline timeline-snap-icon max-md:timeline-compact timeline-vertical">
                                @foreach($reportDetail->processes as $process)
                                    @php
                                        $statusClass = '';
                                        $icon = '';
                                        switch ($process->response_status_id) {
                                            case \App\Enums\ResponseStatus::Initiation->value:
                                                $statusClass = 'bg-warning/10 text-warning-content';
                                                $icon = 'bi-hourglass-split';
                                                break;
                                            case \App\Enums\ResponseStatus::Process->value:
                                                $statusClass = 'bg-info/10 text-info-content';
                                                $icon = 'bi-arrow-repeat';
                                                break;
                                            case \App\Enums\ResponseStatus::Disposition->value:
                                                $statusClass = 'bg-primary/10 text-primary-content';
                                                $icon = 'bi-send-check';
                                                break;
                                            case \App\Enums\ResponseStatus::Termination->value:
                                                $statusClass = 'bg-success/10 text-success-content';
                                                $icon = 'bi-check-circle';
                                                break;
                                        }
                                    @endphp
                                    <li>
                                        <div class="timeline-middle">
                                            <i class="bi {{ $icon }} text-2xl"></i>
                                        </div>
                                        <div class="timeline-end mb-10 p-4 rounded-lg shadow-md border border-base-300 {{ $statusClass }}">
                                            <time class="font-mono italic text-sm">{{ $process->created_at->format('d/m/Y H:i') }}</time>
                                            <div class="text-xl font-bold">{{ $process->responseStatus->name }}</div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="bg-base-300"/>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Card untuk Answer, hanya muncul jika status Termination --}}
                    @if($reportDetail->status === \App\Enums\ResponseStatus::Termination->value && $reportDetail->answer)
                        <div class="mt-8 p-6 bg-success/10 text-success-content rounded-xl shadow-md border border-success/20">
                            <h3 class="text-2xl font-bold mb-4">
                                <i class="bi bi-check-circle-fill mr-2"></i>{{ __('Final Answer') }}
                            </h3>
                            <div class="text-base">
                                {!! $reportDetail->answer !!}
                            </div>
                        </div>
                    @endif

                    {{-- Answer Attachment --}}
                    @if($reportDetail->answer_attachment)
                        <div class="mt-6 bg-base-100 rounded-xl shadow-md border border-base-300 p-4">
                            <h3 class="text-lg font-semibold text-base-content mb-3">
                                <i class="bi bi-paperclip mr-2 text-secondary"></i>{{ __('Answer Attachment') }}
                            </h3>
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('download', ['path' => $reportDetail->answer_attachment]) }}" target="_blank" class="btn btn-sm btn-outline btn-primary gap-2">
                                    <i class="bi bi-download"></i>
                                    {{ basename($reportDetail->answer_attachment) }}
                                </a>
                                @php
                                    $extension = strtolower(pathinfo($reportDetail->answer_attachment, PATHINFO_EXTENSION));
                                @endphp
                                @if(in_array($extension, ['pdf']))
                                    <div class="mt-2 border rounded">
                                        <iframe src="{{ route('download', ['path' => $reportDetail->answer_attachment]) }}"
                                                class="w-full h-96"
                                                type="application/pdf"
                                                title="Answer Attachment Preview">
                                            <p>{{ __('Your browser does not support PDF previews. Please download the file to view it.') }}</p>
                                        </iframe>
                                    </div>
                                @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <div class="mt-2">
                                        <img src="{{ route('download', ['path' => $reportDetail->answer_attachment]) }}" class="max-w-full h-auto rounded border" alt="{{ __('Answer Attachment') }}">
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
                            {{ __('Back to Home') }}
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
