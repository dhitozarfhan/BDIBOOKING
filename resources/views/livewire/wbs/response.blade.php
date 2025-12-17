@section('title', __('WBS Report Response'))

<div>
    <div class="p-4 sm:p-8 bg-base-100">
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

            <h1 class="text-3xl font-bold text-base-content mt-4 mb-6">
                {{ __('WBS Report Response') }}
            </h1>

            @if($reportDetail)
                {{-- Report Details Card --}}
                <div class="bg-base-200/50 rounded-2xl shadow-lg border border-base-300/50 overflow-hidden mb-8">
                    <div class="p-6 border-b border-base-300/50">
                        <h2 class="text-xl font-bold text-base-content flex items-center gap-3">
                            <i class="bi bi-file-earmark-text text-primary"></i>
                            <span>{{ __('Report Details') }}</span>
                        </h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                        <div>
                            <p class="text-sm text-base-content/70">{{ __('Report Subject') }}</p>
                            <p class="font-semibold text-base-content">{{ $reportDetail->subject }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">{{ __('Report Status') }}</p>
                            <p class="font-semibold">
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
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">{{ __('Reporter Name') }}</p>
                            <p class="font-semibold text-base-content">{{ $reportDetail->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">{{ __('Reporter Phone') }}</p>
                            <p class="font-semibold text-base-content">{{ $reportDetail->mobile }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">{{ __('Report Date') }}</p>
                            <p class="font-semibold text-base-content">{{ $reportDetail->time_insert ? $reportDetail->time_insert->format('d F Y, H:i') : 'N/A' }}</p>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <p class="text-sm text-base-content/70">{{ __('Report Content') }}</p>
                            <p class="font-semibold text-base-content whitespace-pre-wrap">{{ $reportDetail->content }}</p>
                        </div>

                        @if($reportDetail->attachment)
                            <div class="col-span-1 md:col-span-2">
                                <p class="text-sm text-base-content/70 mb-2">{{ __('Report Attachment') }}</p>
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('download', ['path' => $reportDetail->attachment]) }}" target="_blank" class="btn btn-sm btn-outline btn-primary gap-2 w-fit">
                                        <i class="bi bi-download"></i>
                                        {{ basename($reportDetail->attachment) }}
                                    </a>
                                    @if(pathinfo($reportDetail->attachment, PATHINFO_EXTENSION) === 'pdf')
                                        <div class="mt-2 border rounded-lg overflow-hidden">
                                            <iframe src="{{ route('download', ['path' => $reportDetail->attachment]) }}" class="w-full min-h-screen" type="application/pdf" title="Report Attachment Preview">
                                                <p>{{ __('Your browser does not support PDF previews. Please download the file to view it.') }}</p>
                                            </iframe>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Response History --}}
                @if($reportDetail->processes && $reportDetail->processes->count() > 0)
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-base-content mb-4 flex items-center gap-3">
                            <i class="bi bi-clock-history text-primary"></i>
                            <span>{{ __('Response History') }}</span>
                        </h2>
                        <ul class="flex flex-col gap-y-8">
                            @foreach($reportDetail->processes as $process)
                                @php
                                    $isTermination = $process->response_status_id === \App\Enums\ResponseStatus::Termination->value;
                                    $isLast = $loop->last;
                                    $statusConfig = [
                                        \App\Enums\ResponseStatus::Initiation->value => ['icon' => 'bi-hourglass-split', 'color' => 'bg-warning/30 text-warning', 'border' => 'border-warning'],
                                        \App\Enums\ResponseStatus::Process->value => ['icon' => 'bi-arrow-repeat', 'color' => 'bg-info/30 text-info', 'border' => 'border-info'],
                                        \App\Enums\ResponseStatus::Disposition->value => ['icon' => 'bi-send-check', 'color' => 'bg-primary/30 text-primary', 'border' => 'border-primary'],
                                        \App\Enums\ResponseStatus::Termination->value => ['icon' => 'bi-check-circle-fill', 'color' => 'bg-success/30 text-success', 'border' => 'border-success'],
                                    ];
                                    $config = $statusConfig[$process->response_status_id] ?? ['icon' => 'bi-question-circle', 'color' => 'bg-base-300', 'border' => 'border-base-300'];
                                @endphp
                                <li>
                                    <div class="p-6 rounded-2xl shadow-md border {{ $config['border'] }} {{ $config['color'] }} flex items-center gap-3">
                                        <i class="bi {{ $config['icon'] }} text-2xl"></i>
                                        <div>
                                            <time class="font-mono italic text-sm opacity-70">{{ $process->created_at->format('d F Y, H:i') }}</time>
                                            <div class="text-xl font-bold mt-1">{{ $process->responseStatus->name }}</div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Final Answer Card --}}
                @if($reportDetail->status === \App\Enums\ResponseStatus::Termination->value && $reportDetail->answer)
                    <div class="bg-base-200/50 rounded-2xl shadow-lg border border-base-300/50 overflow-hidden mb-8">
                        <div class="p-6 border-b border-base-300/50">
                            <h2 class="text-xl font-bold text-base-content flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span>{{ __('Final Answer') }}</span>
                            </h2>
                        </div>
                        <div class="p-6 prose max-w-none">
                            {!! $reportDetail->answer !!}
                        </div>
                    </div>
                @endif

                {{-- Answer Attachment Card --}}
                @if($reportDetail->answer_attachment)
                    <div class="bg-base-200/50 rounded-2xl shadow-lg border border-base-300/50 overflow-hidden mb-8">
                        <div class="p-6 border-b border-base-300/50">
                            <h2 class="text-xl font-bold text-base-content flex items-center gap-3">
                                <i class="bi bi-paperclip text-secondary"></i>
                                <span>{{ __('Answer Attachment') }}</span>
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('download', ['path' => $reportDetail->answer_attachment]) }}" target="_blank" class="btn btn-sm btn-outline btn-primary gap-2 w-fit">
                                    <i class="bi bi-download"></i>
                                    {{ basename($reportDetail->answer_attachment) }}
                                </a>
                                @php
                                    $extension = strtolower(pathinfo($reportDetail->answer_attachment, PATHINFO_EXTENSION));
                                @endphp
                                @if(in_array($extension, ['pdf']))
                                    <div class="mt-2 border rounded-lg overflow-hidden">
                                        <iframe src="{{ route('download', ['path' => $reportDetail->answer_attachment]) }}" class="w-full min-h-screen" type="application/pdf" title="Answer Attachment Preview">
                                            <p>{{ __('Your browser does not support PDF previews. Please download the file to view it.') }}</p>
                                        </iframe>
                                    </div>
                                @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                    <div class="mt-2">
                                        <img src="{{ route('download', ['path' => $reportDetail->answer_attachment]) }}" class="max-w-full h-auto rounded-lg border" alt="{{ __('Answer Attachment') }}">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Navigation Buttons --}}
                <div class="mt-8 flex items-center gap-4">
                    <a href="{{ route('wbs.status') }}" class="btn btn-ghost" wire:navigate>
                        <i class="bi bi-arrow-left"></i>
                        {{ __('Back to Status Check') }}
                    </a>
                    <a href="{{ route('wbs') }}" class="btn btn-primary" wire:navigate>
                        <i class="bi bi-house"></i>
                        {{ __('Back to Home') }}
                    </a>
                </div>

            @elseif($statusError)
                <div class="mt-4 alert alert-error shadow-lg">
                    <div>
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>{{ $statusError }}</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
