<div>
    <div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $breadcrumbs = [
                    ['label' => __('Beranda'), 'url' => route('home')],
                    ['label' => __('WBS Report'), 'url' => route('wbs')],
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
                                    <td class="font-medium">{{ $reportDetail->report_title }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-person mr-2 text-primary"></i>{{ __('Reporter Name') }}
                                    </th>
                                    <td>{{ $reportDetail->reporter_name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-telephone mr-2 text-primary"></i>{{ __('Reporter Phone') }}
                                    </th>
                                    <td>{{ $reportDetail->phone }}</td>
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
                                        @if($reportDetail->reportProcesses->last()?->response_status_id === \App\Enums\ResponseStatus::Initiation->value)
                                            <span class="badge badge-warning gap-2">
                                                <i class="bi bi-hourglass-split"></i>{{ __('Initiation') }}
                                            </span>
                                        @elseif($reportDetail->reportProcesses->last()?->response_status_id === \App\Enums\ResponseStatus::Process->value)
                                            <span class="badge badge-info gap-2">
                                                <i class="bi bi-arrow-repeat"></i>{{ __('Process') }}
                                            </span>
                                        @elseif($reportDetail->reportProcesses->last()?->response_status_id === \App\Enums\ResponseStatus::Disposition->value)
                                            <span class="badge badge-primary gap-2">
                                                <i class="bi bi-send-check"></i>{{ __('Disposition') }}
                                            </span>
                                        @elseif($reportDetail->reportProcesses->last()?->response_status_id === \App\Enums\ResponseStatus::Termination->value)
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
                                    <td class="whitespace-pre-wrap">{{ $reportDetail->report_description }}</td>
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

                    {{-- Card untuk Answer, hanya muncul jika proses selesai --}}
                    @php
                        $terminationProcess = $reportDetail->reportProcesses
                            ->where('response_status_id', \App\Enums\ResponseStatus::Termination->value)
                            ->where('is_completed', true)
                            ->first();
                    @endphp
                    @if($terminationProcess)
                        <div class="mt-6 bg-base-100 rounded-xl shadow-md border border-base-300 p-4">
                            <h3 class="text-lg font-semibold text-base-content mb-3">
                                {{ __('Jawaban') }}
                            </h3>
                            @if($terminationProcess->answer)
                                <div class="whitespace-pre-wrap text-sm text-left">
                                    {!! $terminationProcess->answer !!}
                                </div>
                            @else
                                <div class="whitespace-pre-wrap text-sm text-left text-base-content/60 italic">
                                    {{ __('No official response to this report yet.') }}
                                </div>
                            @endif
                        </div>

                        {{-- Answer Attachment --}}
                        @if($terminationProcess->answer_attachment)
                            <div class="mt-6 bg-base-100 rounded-xl shadow-md border border-base-300 p-4">
                                <h3 class="text-lg font-semibold text-base-content mb-3">
                                    <i class="bi bi-paperclip mr-2 text-secondary"></i>{{ __('Answer Attachment') }}
                                </h3>
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('download', ['path' => $terminationProcess->answer_attachment]) }}" target="_blank" class="btn btn-sm btn-outline btn-primary gap-2">
                                        <i class="bi bi-download"></i>
                                        {{ basename($terminationProcess->answer_attachment) }}
                                    </a>
                                    @php
                                        $extension = strtolower(pathinfo($terminationProcess->answer_attachment, PATHINFO_EXTENSION));
                                    @endphp
                                    @if(in_array($extension, ['pdf']))
                                        <div class="mt-2 border rounded">
                                            <iframe src="{{ route('download', ['path' => $terminationProcess->answer_attachment]) }}"
                                                    class="w-full h-96"
                                                    type="application/pdf"
                                                    title="Answer Attachment Preview">
                                                <p>{{ __('Your browser does not support PDF previews. Please download the file to view it.') }}</p>
                                            </iframe>
                                        </div>
                                    @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <div class="mt-2">
                                            <img src="{{ route('download', ['path' => $terminationProcess->answer_attachment]) }}" class="max-w-full h-auto rounded border" alt="{{ __('Answer Attachment') }}">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('wbs.status') }}" class="btn btn-ghost btn-outline" wire:navigate>
                            {{ __('Back to Status Check') }}
                        </a>
                        <a href="{{ route('wbs') }}" class="btn btn-primary" wire:navigate>
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