@section('title', __('Information Request Response'))

<div>
    <div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $breadcrumbs = [
                    ['label' => __('Home'), 'url' => route('home')],
                    ['label' => __('Public Information Request'), 'url' => route('information.request')],
                    ['label' => __('Request Status'), 'url' => route('information.request.status')],
                    ['label' => __('Request Response')]
                ];
            @endphp
            @include('livewire.information.partials.breadcrumb', ['items' => $breadcrumbs])
            <h2 class="text-2xl font-bold text-base-content mt-4">
                {{ __('Information Request Response') }}
            </h2>

            @if($reportDetail)
                <div class="mt-6">
                    <div class="bg-base-100 rounded-xl shadow-md border border-base-300 overflow-hidden">
                        <table class="table table-zebra w-full">
                            <tbody>
                                <tr>
                                    <th class="w-1/3 bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-person mr-2 text-primary"></i>{{ __('Requester Name') }}
                                    </th>
                                    <td class="font-medium">{{ $reportDetail->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-telephone mr-2 text-primary"></i>{{ __('Requester Phone') }}
                                    </th>
                                    <td>{{ $reportDetail->mobile }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-calendar-event mr-2 text-primary"></i>{{ __('Request Date') }}
                                    </th>
                                    <td>{{ $reportDetail->time_insert ? \Carbon\Carbon::parse($reportDetail->time_insert)->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-base-200 font-semibold text-base-content">
                                        <i class="bi bi-flag mr-2 text-primary"></i>{{ __('Request Status') }}
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
                                        <i class="bi bi-file-text mr-2 text-primary"></i>{{ __('Request Content') }}
                                    </th>
                                    <td class="whitespace-pre-wrap">{{ $reportDetail->content }}</td>
                                </tr>
                                @if($reportDetail->answer_attachment)
                                    <tr>
                                        <th class="bg-base-200 font-semibold text-base-content">
                                            <i class="bi bi-paperclip mr-2 text-secondary"></i>{{ __('Answer Attachment') }}
                                        </th>
                                        <td>
                                            <div class="flex flex-col gap-2">
                                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($reportDetail->answer_attachment) }}" target="_blank" class="btn btn-sm btn-outline btn-primary gap-2">
                                                    <i class="bi bi-download"></i>
                                                    {{ basename($reportDetail->answer_attachment) }}
                                                </a>
                                                @if($reportDetail->answer_attachment && pathinfo($reportDetail->answer_attachment, PATHINFO_EXTENSION) === 'pdf')
                                                    <div class="mt-2 border rounded">
                                                        <iframe src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($reportDetail->answer_attachment) }}"
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

                    {{-- Card untuk Answer, hanya muncul jika proses selesai --}}
                    @if($reportDetail->status === \App\Enums\ResponseStatus::Termination && $reportDetail->answer)
                        <div class="mt-6 bg-base-100 rounded-xl shadow-md border border-base-300 p-4">
                            <h3 class="text-lg font-semibold text-base-content mb-3">
                                {{ __('Answer') }}
                            </h3>
                            <div class="whitespace-pre-wrap text-sm text-left">
                                {!! $reportDetail->answer !!}
                            </div>
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('information.request.status') }}" class="btn btn-ghost btn-outline" wire:navigate>
                           {{ __('Back to Status Check') }}
                        </a>
                        <a href="{{ route('information.request') }}" class="btn btn-primary" wire:navigate>
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
