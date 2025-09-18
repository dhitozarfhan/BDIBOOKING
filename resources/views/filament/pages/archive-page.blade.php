<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search ?? '' }}"
                            placeholder="{{ __('Cari arsip...') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            @if(isset($search)) value="{{ $search }}" @endif
                        >
                    </div>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        {{ __('Cari') }}
                    </button>
                    @if(isset($search) && $search)
                        <a 
                            href="{{ request()->url() }}" 
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                        >
                            {{ __('Reset') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Kode Klasifikasi') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Uraian Berkas') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Tanggal Berkas') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Kurun Waktu') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Jumlah Berkas') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('No. Item Arsip') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Segment') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px;">
                                {{ __('Akun') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Uraian Item Arsip') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Tanggal Item') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Lokasi') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Keterangan') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Retensi Arsip Aktif') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Retensi Arsip Inaktif') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Nasib Akhir Arsip') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('Download') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($folders as $folder)
                            @forelse($folder->documents as $index => $document)
                                <tr>
                                    @if($index === 0)
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" rowspan="{{ $folder->documents->count() }}">
                                            @php
                                                if ($folder->classification) {
                                                    // Get ancestors ordered from root to parent
                                                    $ancestors = $folder->classification->ancestors()->defaultOrder()->get();
                                                    
                                                    // Build the hierarchical path
                                                    $path = [];
                                                    
                                                    // Add ancestors codes
                                                    foreach ($ancestors as $ancestor) {
                                                        $path[] = $ancestor->code;
                                                    }
                                                    
                                                    // Add the current classification's code
                                                    $path[] = $folder->classification->code;
                                                    
                                                    // Join with dots and display
                                                    echo implode('.', $path);
                                                } else {
                                                    echo '';
                                                }
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500" rowspan="{{ $folder->documents->count() }}">
                                            {{ $folder->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" rowspan="{{ $folder->documents->count() }}">
                                            @php
                                                $latestDate = $folder->documents->max('published_at');
                                                echo $latestDate ? $latestDate->format('d/m/Y') : '-';
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" rowspan="{{ $folder->documents->count() }}">
                                            @php
                                                $latestDate = $folder->documents->max('published_at');
                                                echo $latestDate ? $latestDate->format('Y') : '-';
                                            @endphp
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" rowspan="{{ $folder->documents->count() }}">
                                            {{ $folder->documents->count() }} {{ $folder->type === 'lembar' ? 'lembar' : 'berkas' }}
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            if ($document->segment) {
                                                // Get ancestors ordered from root to parent
                                                $ancestors = $document->segment->ancestors()->defaultOrder()->get();
                                                
                                                // Build the hierarchical path
                                                $path = [];
                                                
                                                // Add ancestors codes
                                                foreach ($ancestors as $ancestor) {
                                                    $path[] = $ancestor->code;
                                                }
                                                
                                                // Add the current segment's code
                                                $path[] = $document->segment->code;
                                                
                                                // Join with dots and display
                                                echo implode('.', $path);
                                            } else {
                                                echo '';
                                            }
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500" style="width: 120px; white-space: normal; word-wrap: break-word;">
                                        @foreach($document->accounts as $account)
                                            {{ $account->code }}@if(!$loop->last)<br style="margin-bottom: 1rem;">@endif
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $document->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $document->published_at ? $document->published_at->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            // Get the location tree (ancestors and current location)
                                            $location = $folder->location;
                                            if ($location) {
                                                // Get ancestors ordered from root to parent
                                                $ancestors = $location->ancestors()->defaultOrder()->get();
                                                
                                                // Build the hierarchical path
                                                $path = [];
                                                
                                                // Add ancestors codes
                                                foreach ($ancestors as $ancestor) {
                                                    $path[] = $ancestor->code;
                                                }
                                                
                                                // Add the current location's code
                                                $path[] = $location->code;
                                                
                                                // Join with dots and display
                                                echo implode('.', $path);
                                            } else {
                                                echo '-';
                                            }
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $document->information ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $document->active_retention ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $document->inactive_retention ?? '' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                        if ($document->condition == '0') { 
                                            echo ' Musnah';
                                        } elseif ($document->condition == '1') {
                                            echo 'Tidak Musnah';
                                        }
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($document->file_path)
                                            <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                {{ __('Download') }}
                                            </a>
                                        @else
                                            <span class="text-gray-400">{{ __('Tidak tersedia') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            if ($folder->classification) {
                                                // Get ancestors ordered from root to parent
                                                $ancestors = $folder->classification->ancestors()->defaultOrder()->get();
                                                
                                                // Build the hierarchical path
                                                $path = [];
                                                
                                                // Add ancestors codes
                                                foreach ($ancestors as $ancestor) {
                                                    $path[] = $ancestor->code;
                                                }
                                                
                                                // Add the current classification's code
                                                $path[] = $folder->classification->code;
                                                
                                                // Join with dots and display
                                                echo implode('.', $path);
                                            } else {
                                                echo '';
                                            }
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $folder->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $latestDate = $folder->documents->max('published_at');
                                            echo $latestDate ? $latestDate->format('d/m/Y') : '-';
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $latestDate = $folder->documents->max('published_at');
                                            echo $latestDate ? $latestDate->format('Y') : '-';
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        0 {{ $folder->type === 'lembar' ? 'lembar' : 'berkas' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500" colspan="9">
                                        {{ __('Tidak ada dokumen dalam folder ini.') }}
                                    </td>
                                </tr>
                            @endforelse
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if(isset($search) && $search && $folders->count() == 0)
                <div class="p-6 text-center text-gray-500">
                    {{ __('Tidak ada hasil ditemukan untuk pencarian: ') }} <strong>"{{ $search }}"</strong>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>