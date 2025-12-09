<x-app-layout>
    <div class="container px-4 py-8 mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Jawaban Informasi & Pertanyaan</h1>
        <div class="mt-4">
            <div class="flex border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('information.answer') }}" class="px-4 py-2 -mb-px font-semibold text-gray-800 border-b-2 {{ !request()->query('status') ? 'border-blue-500' : 'border-transparent' }} dark:text-white hover:border-blue-500 focus:outline-none">
                    Semua
                </a>
                <a href="{{ route('information.answer', ['status' => 'baru']) }}" class="px-4 py-2 -mb-px font-semibold {{ request()->query('status') == 'baru' ? 'text-blue-600 border-blue-500' : 'text-gray-500 border-transparent' }} dark:text-gray-400 hover:border-blue-500 focus:outline-none">
                    Baru
                </a>
                <a href="{{ route('information.answer', ['status' => 'diproses']) }}" class="px-4 py-2 -mb-px font-semibold {{ request()->query('status') == 'diproses' ? 'text-blue-600 border-blue-500' : 'text-gray-500 border-transparent' }} dark:text-gray-400 hover:border-blue-500 focus:outline-none">
                    Diproses
                </a>
                <a href="{{ route('information.answer', ['status' => 'selesai']) }}" class="px-4 py-2 -mb-px font-semibold {{ request()->query('status') == 'selesai' ? 'text-blue-600 border-blue-500' : 'text-gray-500 border-transparent' }} dark:text-gray-400 hover:border-blue-500 focus:outline-none">
                    Selesai
                </a>
            </div>
            <div class="py-4">
                {{-- This is where the content for each tab will go.
                     You would typically use Livewire or Alpine.js to handle the tab switching
                     and loading of the correct data.

                     For this example, I will just show a list of all items.
                     You will need to implement the logic to filter the items based on the selected tab.
                --}}

                @if (isset($items) && $items->count() > 0)
                    <div class="space-y-4">
                        @foreach ($items as $item)
                            <div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        @if ($item instanceof \App\Models\InformationRequest)
                                            Permintaan Informasi
                                        @elseif ($item instanceof \App\Models\Question)
                                            Pertanyaan
                                        @endif
                                        - {{ $item->registration_code }}
                                    </span>
                                    @php
                                        $latestProcess = $item->reportProcesses->last();
                                        $statusName = 'Baru';
                                        if ($latestProcess) {
                                            $statusName = $latestProcess->responseStatus->name;
                                        }
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold text-white bg-blue-500 rounded-full">
                                        {{ $statusName }}
                                    </span>
                                </div>
                                <p class="mt-2 text-gray-800 dark:text-white">
                                    <strong>Permintaan:</strong> {{ $item->content }}
                                </p>
                                @php
                                    $terminationProcess = $item->reportProcesses->where('response_status_id', \App\Enums\ResponseStatus::Termination->value)->first();
                                @endphp
                                @if ($terminationProcess && $terminationProcess->answer)
                                    <div class="p-3 mt-4 bg-gray-100 rounded-lg dark:bg-gray-700">
                                        <p class="text-gray-800 dark:text-white">
                                            <strong>Jawaban:</strong> {{ $terminationProcess->answer }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 text-center text-gray-500 bg-white rounded-lg shadow-md dark:bg-gray-800 dark:text-gray-400">
                        Tidak ada data untuk ditampilkan.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
