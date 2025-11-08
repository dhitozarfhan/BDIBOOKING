<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('information.contact') }}</h2>
        <ul class="space-y-2 text-gray-700 dark:text-gray-300">
            <li class="flex items-center">
                <i class="fas fa-phone-alt w-5 h-5 mr-2 text-blue-500"></i>
                <span><strong>{{ __('information.phone') }}:</strong> {{ __('information._phone') }}</span>
            </li>
            <li class="flex items-center">
                <i class="fas fa-fax w-5 h-5 mr-2 text-blue-500"></i>
                <span><strong>{{ __('information.fax') }}:</strong> {{ __('information._fax') }}</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-map-marker-alt w-5 h-5 mr-2 text-blue-500 mt-1"></i>
                <span><strong>{{ __('information.address') }}:</strong> {{ __('information._address') }}</span>
            </li>
            <li class="flex items-center">
                <i class="fas fa-envelope w-5 h-5 mr-2 text-blue-500"></i>
                <a href="mailto:bdiyogyakarta@kemenperin.go.id" class="text-blue-600 hover:underline">{{ __('information._email') }}</a>
            </li>
        </ul>
    </div>
    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('information.maps') }}</h2>
        <div class="rounded-lg overflow-hidden">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7905.548842741774!2d110.403105!3d-7.813687!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x310b8a2efcab039a!2sBALAI+DIKLAT+INDUSTRI!5e0!3m2!1sen!2sus!4v1496382397073"
                width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </div>
    <div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">{{ __('information.facebook') }}</h2>
        <div class="flex items-center">
            <i class="fab fa-facebook-square w-5 h-5 mr-2 text-blue-500"></i>
            <a href="https://www.facebook.com/bdiyogyakarta" class="text-blue-600 hover:underline">bdiyogyakarta</a>
        </div>
    </div>
</div>
