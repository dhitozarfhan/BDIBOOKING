<x-app-layout>
    <div class="container flex mx-auto px-4 mt-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <div class="lg:col-span-8">
                <div class="bg-white p-6 rounded-lg shadow-md w-full mb-16">
                    <h1 class="text-4xl font-bold mb-6">{{ __('information.public_information_request_form') }}</h1>
                    <hr>
                    <form action="" method="POST" class="my-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="form-group mb-4">
                                    <label for="name" class="block text-gray-700">
                                        <i class="fas fa-user"></i>
                                        {{ __('information.applicant_name') }} <span class="text-error">*</span>
                                    </label>
                                    <div class="flex items-center">
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control w-full p-2 border border-gray-300 rounded-md"/>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="id_card_number" class="block text-gray-700">
                                        <i class="fas fa-list-alt"></i>
                                        {{ __('information.applicant_id_card_number') }} <span class="text-error">*</span>
                                    </label>
                                    <div class="flex items-center">
                                        <input type="text" name="id_card_number" value="{{ old('id_card_number') }}"
                                            class="form-control w-full p-2 border border-gray-300 rounded-md"/>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="address" class="block text-gray-700">
                                        <i class="fas fa-map-marker"></i>
                                        {{ __('information.applicant_address') }} <span class="text-error">*</span>
                                    </label>
                                    <div class="flex items-center">
                                        <input type="text" name="address" value="{{ old('address') }}"
                                            class="form-control w-full p-2 border border-gray-300 rounded-md"/>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="occupation" class="block text-gray-700">
                                        <i class="fas fa-suitcase"></i>
                                        {{ __('information.applicant_occupation') }} <span class="text-error">*</span>
                                    </label>
                                    <div class="flex items-center">
                                        <input type="text" name="occupation" value="{{ old('occupation') }}"
                                            class="form-control w-full p-2 border border-gray-300 rounded-md"/>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="mobile" class="block text-gray-700">
                                        <i class="fas fa-phone"></i>
                                        {{ __('information.applicant_mobile') }} <span class="text-error">*</span>
                                    </label>
                                    <div class="flex items-center">
                                        <input type="text" name="mobile" value="{{ old('mobile') }}"
                                            class="form-control w-full p-2 border border-gray-300 rounded-md"/>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="email" class="block text-gray-700">
                                        <i class="fas fa-envelope"></i>
                                        {{ __('information.applicant_email') }} <span class="text-error">*</span>
                                    </label>
                                    <div class="flex items-center">
                                        <input type="text" name="email" value="{{ old('email') }}"
                                            class="form-control w-full p-2 border border-gray-300 rounded-md"/>
                                    </div>
                                </div>
                                <label>{{ __('information.applicant_grab_method') }} <span class="text-error">*</span></label>
                                <div class="flex items-center">
                                    <input id="default-checkbox" type="checkbox" name="grab_method[]" value="see" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded " {{ in_array('see', old('grab_method', [])) ? 'checked' : '' }}>
                                    <label for="default-checkbox" class="ms-2 text-gray-900">{{ __('information.applicant_see') }}</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="default-checkbox" type="checkbox" name="grab_method[]" value="read" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded " {{ in_array('read', old('grab_method', [])) ? 'checked' : '' }}>
                                    <label for="default-checkbox" class="ms-2 text-gray-900">{{ __('information.applicant_read') }}</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="default-checkbox" type="checkbox" name="grab_method[]" value="hear" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded " {{ in_array('hear', old('grab_method', [])) ? 'checked' : '' }}>
                                    <label for="default-checkbox" class="ms-2 text-gray-900">{{ __('information.applicant_hear') }}</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="default-checkbox" type="checkbox" name="grab_method[]" value="write" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded " {{ in_array('write', old('grab_method', [])) ? 'checked' : '' }}>
                                    <label for="default-checkbox" class="ms-2 text-gray-900">{{ __('information.applicant_write') }}</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="default-checkbox" type="checkbox" name="grab_method[]" value="hardcopy" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded " {{ in_array('hardcopy', old('grab_method', [])) ? 'checked' : '' }}>
                                    <label for="default-checkbox" class="ms-2 text-gray-900">{!! __('information.applicant_hardcopy') !!}</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="default-checkbox" type="checkbox" name="grab_method[]" value="softcopy" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded " {{ in_array('softcopy', old('grab_method', [])) ? 'checked' : '' }}>
                                    <label for="default-checkbox" class="ms-2 text-gray-900">{!! __('information.applicant_softcopy') !!}</label>
                                </div>
                            </div>
                            <div>
                                <div class="form-group mb-4">
                                    <label for="content" class="block text-gray-700">
                                        {{ __('information.applicant_request') }} <span class="text-error">*</span>
                                    </label>
                                    <div class="flex items-center">
                                        <textarea name="content" class="form-control w-full p-2 border border-gray-300 rounded-md h-60">
                                            {{ old('content') }}</textarea>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="used_for" class="block text-gray-700">
                                        {{ __('information.applicant_used_for') }} <span class="text-error">*</span>
                                    </label>
                                    <div class="flex items-center">
                                        <textarea name="used_for" class="form-control w-full p-2 border border-gray-300 rounded-md h-60">
                                            {{ old('used_for') }}</textarea>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <input id="default-checkbox" type="checkbox" name="rule" value="accept" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded" {{ old('rule') == 'accept' ? 'checked' : '' }}>
                                    <label for="default-checkbox" class="ms-2 text-gray-900">{!! __('information.applicant_provision') !!} <span class="text-error">*</span></label>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary text-base text-white w-28">
                                        {{ __('information.submit') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="lg:col-span-4">
                @include('components.information-sidebar')
            </div>
        </div>
    </div>
</x-app-layout>
