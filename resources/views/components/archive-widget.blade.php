<div class="mb-8">
    <h3 class="text-white text-center text-2xl font-bold bg-red-500 py-4 px-2 mb-3">Zona Integritas</h3>
    <img src="{{ asset('images/zi.png') }}" alt="Zona Integritas BDI Yogyakarta">
</div>

@if (!@empty($archive))
    <div class="mb-8">
        <div class="mb-4">
            <h3 class="text-3xl font-bold">{{ __('home.archives') }}</h3>
        </div>
        <div class="space-y-2">
            @foreach ($archive as $arc)
                <a href="" class="block bg-white border p-3 rounded-lg shadow hover:border-blue-500">
                    <div class="flex items-center justify-between">
                        <h6 class="font-semibold">{{ \Carbon\Carbon::create($arc->year, $arc->month, 1)->translatedFormat('F Y') }}</h6>
                        <i class="fas fa-chevron-right text-blue-500"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
