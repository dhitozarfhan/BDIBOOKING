<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-primary-600 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-white">{{ __('Complaint Status Details') }}</h2>
            <a href="{{ route('information.question.status') }}" class="text-white hover:text-gray-200 text-sm font-medium">
                &larr; {{ __('Check Another Code') }}
            </a>
        </div>

        <div class="p-6">
            <!-- Report Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Reporter Information') }}</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Name') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reportDetail->name }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Mobile') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reportDetail->mobile }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Submitted At') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($reportDetail->time_insert)->format('d F Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">{{ __('Complaint Details') }}</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-4">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Subject') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reportDetail->subject }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Content') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reportDetail->content }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-6 border-b pb-2">{{ __('Status History') }}</h3>
                
                <div class="relative">
                    <!-- Status Item -->
                    <div class="mb-8 flex justify-between items-center w-full right-timeline">
                        <div class="order-1 w-5/12"></div>
                        <div class="z-20 flex items-center order-1 bg-gray-800 shadow-xl w-8 h-8 rounded-full">
                            <h1 class="mx-auto font-semibold text-lg text-white">1</h1>
                        </div>
                        <div class="order-1 bg-gray-100 rounded-lg shadow-xl w-5/12 px-6 py-4">
                            <h3 class="mb-3 font-bold text-gray-800 text-xl">{{ __('Current Status') }}</h3>
                            <p class="text-sm leading-snug tracking-wide text-gray-900 text-opacity-100">
                                @if($reportDetail->status == \App\Enums\ResponseStatus::Initiation->value)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        {{ __('Initiation') }}
                                    </span>
                                @elseif($reportDetail->status == \App\Enums\ResponseStatus::Process->value)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ __('Process') }}
                                    </span>
                                @elseif($reportDetail->status == \App\Enums\ResponseStatus::Disposition->value)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ __('Disposition') }}
                                    </span>
                                @elseif($reportDetail->status == \App\Enums\ResponseStatus::Termination->value)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ __('Termination') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($reportDetail->answer)
                    <div class="mb-8 flex justify-between flex-row-reverse items-center w-full left-timeline">
                        <div class="order-1 w-5/12"></div>
                        <div class="z-20 flex items-center order-1 bg-gray-800 shadow-xl w-8 h-8 rounded-full">
                            <h1 class="mx-auto text-white font-semibold text-lg">2</h1>
                        </div>
                        <div class="order-1 bg-green-100 rounded-lg shadow-xl w-5/12 px-6 py-4">
                            <h3 class="mb-3 font-bold text-gray-800 text-xl">{{ __('Response') }}</h3>
                            <p class="text-sm font-medium leading-snug tracking-wide text-gray-900 text-opacity-100">
                                {!! $reportDetail->answer !!}
                            </p>
                            @if($reportDetail->answer_attachment)
                                <div class="mt-4">
                                    <a href="{{ route('download', ['path' => $reportDetail->answer_attachment]) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        {{ __('Download Attachment') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
