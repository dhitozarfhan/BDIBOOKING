<x-app-layout>
    <section class="bg-gray-100 py-10">
        <div class="container">
            <div class="flex flex-col md:flex-row w-full gap-10">
                <!-- Sidebar -->
                <div class="w-full md:w-1/4 lg:w-1/4">
                    <div class="bg-white shadow-lg p-4 rounded-lg sticky top-40">
                        <nav class="flex flex-col space-y-2">
                            @foreach (['propose', 'challenge', 'dispute', 'court_dispute'] as $tp)
                                <a href="{{ route('information.procedure', $tp) }}"
                                    class="block px-4 py-2 font-medium rounded-lg {{ $tp === $type ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-200' }}">
                                    <i class="fas fa-check mr-2"></i>
                                    {{ __('information.' . $tp . '_menu_head') }}
                                    <span>{{ __('information.' . $tp . '_menu_small') }}</span>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </div>
                <!-- Main Content -->
                <div class="w-full md:w-3/4 lg:w-3/4">
                    <img src="{{ asset('assets/images/' . $type . '.jpg') }}" alt="{{ $title }}" class="w-full rounded-3xl border shadow-lg">
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
