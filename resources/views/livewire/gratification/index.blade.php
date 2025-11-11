<div class="p-4 sm:p-8 bg-base-100 shadow sm:rounded-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 lg:gap-4">
            <div class="lg:col-span-8 mb-6 lg:mb-0">
                        @php
                            $breadcrumbs = [
                                ['label' => __('Beranda'), 'url' => route('home')],
                                ['label' => __('Gratification Reporting')]
                            ];
                        @endphp
                        @include('livewire.gratification.partials.breadcrumb', ['items' => $breadcrumbs])                <h2 class="text-2xl font-bold text-base-content mt-4">
                    {{ __('Gratification Reporting') }}
                </h2>

                <div class="mt-4 prose max-w-none">
                    <p>{{ __('gratification.index.prose1') }}</p>
                    <p>{{ __('gratification.index.prose2') }}</p>
                    <h6>{{ __('gratification.index.forbidden_cases_title') }}</h6>
                    <ul>
                        <li>{{ __('gratification.index.case1') }}</li>
                        <li>{{ __('gratification.index.case2') }}</li>
                        <li>{{ __('gratification.index.case3') }}</li>
                        <li>{{ __('gratification.index.case4') }}</li>
                        <li>{{ __('gratification.index.case5') }}</li>
                        <li>{{ __('gratification.index.case6') }}</li>
                        <li>{{ __('gratification.index.case7') }}</li>
                        <li>{{ __('gratification.index.case8') }}</li>
                    </ul>
                    <h6>{{ __('gratification.index.reporting_channels_title') }}</h6>
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <tbody>
                                <tr>
                                    <td>{{ __('Website') }}</td>
                                    <td>:</td>
                                    <td>
                                        <a href="#" wire:click.prevent="setView('form')" class="link link-primary">{{ __('Gratification Report Form') }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>{{ __('Letter') }}</td>
                                    <td>:</td>
                                    <td>BDI Yogyakarta<br/>Jl. Babarsari No. 245, Yogyakarta</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Phone') }}</td>
                                    <td>:</td>
                                    <td>0274-487711</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Fax') }}</td>
                                    <td>:</td>
                                    <td>0274-487711</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-medium text-base-content">{{ __('Related Services') }}</h3>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <a href="{{ route('gratification.form') }}" class="btn btn-primary">
                            <i class="bi bi-gift-fill"></i> {{ __('Report Form') }}
                        </a>
                        <a href="{{ route('gratification.status') }}" class="btn btn-info">
                            <i class="bi bi-check-circle-fill"></i> {{ __('Report Status') }}
                        </a>
                        <a href="{{ route('gratification.report') }}" class="btn btn-accent">
                            <i class="bi bi-bar-chart-fill"></i> {{ __('Statistical Report') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-4">
                <div class="bg-base-200 p-4 rounded-lg shadow-sm mt-12">
                    <h2 class="text-2xl font-bold text-base-content mb-4">{{ __('Contact Us') }}</h2>
                    <div class="space-y-2 text-base-content/80">
                        <p><i class="bi bi-telephone-fill mr-2"></i> <strong>{{ __('Phone') }}:</strong> 0274-487711</p>
                        <p><i class="bi bi-printer-fill mr-2"></i> <strong>{{ __('Fax') }}:</strong> 0274-487711</p>
                        <p><i class="bi bi-geo-alt-fill mr-2"></i> <strong>{{ __('Address') }}:</strong> BDI Yogyakarta, Jl. Babarsari No. 245, Yogyakarta</p>
                        <p><i class="bi bi-envelope-fill mr-2"></i> <strong>{{ __('Email') }}:</strong> info@bdiyk.id</p>
                    </div>
                    <div class="mt-6">
                        <h2 class="text-2xl font-bold text-base-content mb-4">{{ __('Maps') }}</h2>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3953.164483635528!2d110.401864!3d-7.816401!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a57156a828f41%3A0x310b8a2efcab039a!2sBalai%20Diklat%20Industri%20Yogyakarta!5e0!3m2!1sen!2sid!4v1730698774152!5m2!1sen!2sid" class="w-full h-72 border-0 rounded-md" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>