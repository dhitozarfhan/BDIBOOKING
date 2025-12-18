<x-app-layout>
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex min-h-screen bg-gray-50 rounded-lg overflow-hidden shadow-sm">
        {{-- Sidebar --}}
        <div class="w-64 bg-white shadow-sm">
            <div class="p-4">
                <nav class="space-y-2">
                    <a href="{{ route('information.answer') }}" 
                       class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ !request()->query('status') ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            Semua
                        </div>
                        <span class="text-xs">({{ $totalCount ?? 0 }})</span>
                    </a>
                    
                    <a href="{{ route('information.answer', ['status' => 'baru']) }}" 
                       class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->query('status') == 'baru' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            Pertanyaan Baru
                        </div>
                        <span class="text-xs">({{ $newCount ?? 0 }})</span>
                    </a>
                    
                    <a href="{{ route('information.answer', ['status' => 'diproses']) }}" 
                       class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->query('status') == 'diproses' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Sedang Diproses
                        </div>
                        <span class="text-xs">({{ $processCount ?? 0 }})</span>
                    </a>
                    
                    <a href="{{ route('information.answer', ['status' => 'disposisi']) }}" 
                       class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->query('status') == 'disposisi' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Disposisi
                        </div>
                        <span class="text-xs">({{ $disposalCount ?? 0 }})</span>
                    </a>
                    
                    <a href="{{ route('information.answer', ['status' => 'selesai']) }}" 
                       class="flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg {{ request()->query('status') == 'selesai' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Selesai
                        </div>
                        <span class="text-xs">({{ $finishedCount ?? 0 }})</span>
                    </a>
                </nav>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 p-6">
            {{-- Filter Section --}}
            <div class="mb-6 bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <form method="GET" action="{{ route('information.answer') }}">
                        @if(request()->query('status'))
                            <input type="hidden" name="status" value="{{ request()->query('status') }}">
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Row 1 --}}
                            <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Kategori</option>
                                <option value="permohonan-informasi" {{ request()->query('category') == 'permohonan-informasi' ? 'selected' : '' }}>Permohonan Informasi</option>
                                <option value="pengaduan-masyarakat" {{ request()->query('category') == 'pengaduan-masyarakat' ? 'selected' : '' }}>Pengaduan Masyarakat</option>
                            </select>

                            <select name="month" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request()->query('month') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                                @endfor
                            </select>

                            <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Tahun</option>
                                @for ($year = date('Y'); $year >= 2020; $year--)
                                    <option value="{{ $year }}" {{ request()->query('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>

                            {{-- Row 2 --}}
                            <div class="md:col-span-2">
                                <input type="text" name="keywords" placeholder="Kata kunci..." 
                                       value="{{ request()->query('keywords') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <input type="text" name="reg_code" placeholder="Kode Register" 
                                   value="{{ request()->query('reg_code') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="flex items-center px-6 py-2 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table Section --}}
            <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-600 uppercase">
                                    <a href="{{ route('information.answer', array_merge(request()->query(), ['sort' => 'waktu_reg'])) }}" class="flex items-center gap-1 hover:text-blue-800">
                                        Waktu Reg
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-600 uppercase">Kd Reg</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-600 uppercase">Penanya</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-600 uppercase">Pertanyaan</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-600 uppercase">Kategori</th>
                                <th class="px-6 py-3 text-xs font-semibold tracking-wider text-left text-blue-600 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if (isset($items) && $items->count() > 0)
                                @foreach ($items as $item)
                                    @php
                                        $latestProcess = $item->reportProcesses->last();
                                        $statusName = 'Pertanyaan Baru';
                                        $statusClass = 'bg-blue-100 text-blue-800';
                                        
                                        if ($latestProcess) {
                                            $statusName = $latestProcess->responseStatus->name;
                                            
                                            // Set color based on status
                                            if (str_contains(strtolower($statusName), 'selesai')) {
                                                $statusClass = 'bg-green-100 text-green-800';
                                            } elseif (str_contains(strtolower($statusName), 'diproses')) {
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                            } elseif (str_contains(strtolower($statusName), 'disposisi')) {
                                                $statusClass = 'bg-purple-100 text-purple-800';
                                            }
                                        }
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                            {{ $item->created_at->format('d M Y') }}<br>
                                            <span class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                            {{ $item->registration_code }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $item->reporter_name ?? 'Anonim' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            <div class="max-w-lg">
                                                @if ($item instanceof \App\Models\InformationRequest)
                                                    {{ Str::limit($item->report_title, 100) }}
                                                    @if (strlen($item->report_title) > 100)
                                                        <a href="#" class="text-blue-600 hover:underline">Lihat jawaban</a>
                                                    @endif
                                                @elseif ($item instanceof \App\Models\Question)
                                                    {{ Str::limit($item->content, 100) }}
                                                    @if (strlen($item->content) > 100)
                                                        <a href="#" class="text-blue-600 hover:underline">Lihat jawaban</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            @if ($item instanceof \App\Models\InformationRequest)
                                                <a href="#" class="text-blue-600 hover:underline">Permohonan Informasi</a>
                                            @elseif ($item instanceof \App\Models\Question)
                                                <a href="#" class="text-blue-600 hover:underline">Pengaduan Masyarakat</a>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                                {{ $statusName }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-sm text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                            <p class="text-gray-600">Tidak ada data untuk ditampilkan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if (isset($items) && method_exists($items, 'hasPages') && $items->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $items->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>