@section('title', $type === 'question' ? __('Complaint Response Public') : __('Information Request Response'))

<div class="p-4 sm:p-8 bg-base-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $breadcrumbs = [
                ['label' => __('Home'), 'url' => route('home')],
                ['label' => __('Information Answer'), 'url' => route('information.answer')],
                ['label' => $type === 'question' ? __('Complaint Response Public') : __('Information Request Response')]
            ];
        @endphp
        @include('livewire.information.partials.breadcrumb', ['items' => $breadcrumbs])

        <h1 class="text-3xl font-bold text-base-content mt-4 mb-6">
            {{ $type === 'question' ? __('Complaint Response Public') : __('Information Request Response') }}
        </h1>

        @if($reportDetail)
            {{-- Report Details Card --}}
            <div class="bg-base-200/50 rounded-2xl shadow-lg border border-base-300/50 overflow-hidden mb-8">
                <div class="p-6 border-b border-base-300/50">
                    <h2 class="text-xl font-bold text-base-content flex items-center gap-3">
                        <i class="bi bi-file-earmark-text text-primary"></i>
                        <span>{{ $type === 'question' ? __('Report Details') : __('Request Details') }}</span>
                    </h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                    <div>
                        <p class="text-sm text-base-content/70">{{ $type === 'question' ? __('Report Title') : __('Request Content') }}</p>
                        <p class="font-semibold text-base-content">{{ $reportDetail->report_title }}</p>
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
                        <p class="font-semibold text-base-content">{{ $reportDetail->reporter_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-base-content/70">{{ __('Mobile') }}</p>
                        <p class="font-semibold text-base-content">{{ $reportDetail->mobile }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-base-content/70">{{ __('Submitted At') }}</p>
                        <p class="font-semibold text-base-content">{{ $reportDetail->time_insert ? $reportDetail->time_insert->format('d F Y, H:i') : 'N/A' }}</p>
                    </div>

                    @if ($type === 'question')
                        <div class="col-span-1 md:col-span-2">
                            <p class="text-sm text-base-content/70">{{ __('Content') }}</p>
                            <p class="font-semibold text-base-content whitespace-pre-wrap">{{ $reportDetail->content }}</p>
                        </div>
                    @else
                        <div>
                            <p class="text-sm text-base-content/70">{{ __('Information Usage') }}</p>
                            <p class="font-semibold text-base-content">{{ $reportDetail->used_for }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">{{ __('Acquisition Method') }}</p>
                            <p class="font-semibold text-base-content">
                                @if(is_array($reportDetail->grab_method))
                                    {{ implode(', ', array_map(fn($m) => ucfirst(__($m)), $reportDetail->grab_method)) }}
                                @else
                                    {{ ucfirst(__($reportDetail->grab_method)) }}
                                @endif
                            </p>
                        </div>
                        @if(!empty($reportDetail->delivery_method))
                            <div>
                                <p class="text-sm text-base-content/70">{{ __('Delivery Method') }}</p>
                                <p class="font-semibold text-base-content">
                                    @if(is_array($reportDetail->delivery_method))
                                        {{ implode(', ', array_map(fn($m) => ucfirst(__($m)), $reportDetail->delivery_method)) }}
                                    @else
                                        {{ ucfirst(__($reportDetail->delivery_method)) }}
                                    @endif
                                </p>
                            </div>
                        @endif
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
                                        <div class="text-xl font-bold mt-1">{{ __($process->responseStatus->name) }}</div>
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
                                    <iframe src="{{ route('download', ['path' => $reportDetail->answer_attachment]) }}" class="w-full min-h-screen" type="application/pdf" title="{{ __('Answer Attachment Preview') }}">
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
                <a href="{{ route('information.answer') }}" class="btn btn-ghost">
                    {{ __('Back to Answer List') }}
                </a>
            </div>

        @else
            <div class="mt-4 alert alert-error shadow-lg">
                <div>
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ __('Report not found. Please check the registration code.') }}</span>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('information.answer') }}" class="btn btn-ghost btn-outline">
                    {{ __('Back to Answer List') }}
                </a>
            </div>
        @endif
    </div>
</div>