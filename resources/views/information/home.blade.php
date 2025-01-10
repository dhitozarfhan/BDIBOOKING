<x-app-layout>
    <section class="bg-gray-100">
        <div class="container mx-auto text-center p-12">
            <div class="flex justify-between items-center mb-8">
                <i class="fas fa-plus text-purple-600 text-2xl"></i>
                <i class="fas fa-star text-orange-500 text-2xl"></i>
            </div>
            <h1 class="text-5xl font-bold text-gray-800 mb-12">{{ __('home.dip') }}</h1>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach ($cores as $dt)
                    <div class="bg-white rounded-lg shadow-md hover:ring-1 hover:ring-blue-500 group">
                        <a href="{{ route('information.core', ['slug' => $dt->slug]) }}" class="p-4">
                            <h2 class="text-4xl text-gray-400 mb-2 group-hover:text-blue-500">{!! $dt['icon'] !!}</h2>
                            <h6 class="font-bold">{{ $dt[config('app.locale').'_name'] }}</h6>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-app-layout>
