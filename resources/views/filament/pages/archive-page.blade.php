<x-filament-panels::page>
    <style>
        .filter-button {
            transition: all 0.2s ease;
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            min-width: 100px; /* Ensure minimum width for visibility */
            font-weight: 600; /* Make text bolder for better visibility */
        }
        
        .filter-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .filter-button:active {
            transform: translateY(0);
        }
        
        .reset-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 100px; /* Ensure minimum width for visibility */
            font-weight: 600; /* Make text bolder for better visibility */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add subtle shadow for better visibility */
        }
        
        .filter-indicator {
            position: relative;
        }
        
        .filter-dot {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 12px;
            height: 12px;
            background-color: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
            animation: pulse 2s infinite;
            z-index: 11;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }
        
        /* Ensure buttons are always visible */
        .btn-visible {
            visibility: visible !important;
            opacity: 1 !important;
            display: inline-flex !important; /* Force display as flex */
        }
    </style>
    <div class="space-y-6">
        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <form method="GET">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex-grow min-w-[200px]">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search ?? '' }}"
                            placeholder="{{ __('Cari arsip...') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    <div class="flex flex-wrap gap-2 items-center">
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center font-medium whitespace-nowrap"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Cari') }}
                        </button>
                        <button 
                            type="button" 
                            id="filterButton"
                            class="filter-button px-4 py-2 {{ ((isset($classificationId) && $classificationId) || (isset($locationId) && $locationId) || (isset($startDate) && $startDate) || (isset($endDate) && $endDate)) ? 'bg-blue-600' : 'bg-gray-600' }} text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center font-medium shadow-lg relative whitespace-nowrap btn-visible transition-colors duration-200 ease-in-out"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Filter') }}
                            @if((isset($classificationId) && $classificationId) || (isset($locationId) && $locationId) || (isset($startDate) && $startDate) || (isset($endDate) && $endDate))
                                <div class="filter-dot"></div>
                            @endif
                        </button>
                        <div class="flex flex-col items-start relative group">
                            <a 
                                href="{{ route('archive.export') }}?search={{ urlencode(request()->query('search', '')) }}&classificationId={{ urlencode(request()->query('classificationId', '')) }}&locationId={{ urlencode(request()->query('locationId', '')) }}&startDate={{ urlencode(request()->query('startDate', '')) }}&endDate={{ urlencode(request()->query('endDate', '')) }}"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center font-medium whitespace-nowrap export-button"
                                title="{{ __('Export ke Excel (hanya data terfilter)') }}"
                                data-search="{{ request()->query('search', '') }}"
                                data-classification="{{ request()->query('classificationId', '') }}"
                                data-location="{{ request()->query('locationId', '') }}"
                                data-start="{{ request()->query('startDate', '') }}"
                                data-end="{{ request()->query('endDate', '') }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                {{ __('Export') }}
                            </a>
                            <span class="text-xs text-gray-500 mt-1">{{ __('Export data terfilter') }}</span>
                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-48 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-50">
                                <div class="text-center">
                                    {{ __('Hanya mengekspor data yang sesuai dengan filter yang aktif') }}
                                </div>
                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-800"></div>
                            </div>
                        </div>
                        @if((isset($search) && $search) || (isset($classificationId) && $classificationId) || (isset($locationId) && $locationId) || (isset($startDate) && $startDate) || (isset($endDate) && $endDate))
                            <a 
                                href="{{ request()->url() }}" 
                                class="reset-button px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 flex items-center font-medium whitespace-nowrap btn-visible transition-colors duration-200 ease-in-out"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                {{ __('Reset') }}
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Filter Dropdown -->
                <div id="filterDropdown" class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200 shadow-sm transition-all duration-300 ease-in-out {{ ((isset($classificationId) && $classificationId) || (isset($locationId) && $locationId) || (isset($startDate) && $startDate) || (isset($endDate) && $endDate)) ? '' : 'hidden' }}" style="z-index: 20;">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Classification Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Klasifikasi') }}</label>
                            <select 
                                name="classificationId" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">{{ __('Semua Klasifikasi') }}</option>
                                @foreach($classifications as $classification)
                                    <option value="{{ $classification->id }}" {{ (isset($classificationId) && $classificationId == $classification->id) ? 'selected' : '' }}>
                                        {{ $classification->code }} - {{ $classification->getReadableNameAttribute() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Location Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Lokasi') }}</label>
                            <select 
                                name="locationId" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">{{ __('Semua Lokasi') }}</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (isset($locationId) && $locationId == $location->id) ? 'selected' : '' }}>
                                        {{ $location->code }} - {{ $location->getReadableNameAttribute() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Date Range Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Rentang Waktu') }}</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">{{ __('Tanggal Mulai') }}</label>
                                    <input 
                                        type="date" 
                                        name="startDate" 
                                        value="{{ $startDate ?? '' }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">{{ __('Tanggal Selesai') }}</label>
                                    <input 
                                        type="date" 
                                        name="endDate" 
                                        value="{{ $endDate ?? '' }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button 
                            type="button" 
                            id="cancelFilter"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center font-medium"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Batal') }}
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center font-medium"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Terapkan Filter') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const filterButton = document.getElementById('filterButton');
                const filterDropdown = document.getElementById('filterDropdown');
                const cancelFilter = document.getElementById('cancelFilter');
                
                // Ensure elements exist before adding event listeners
                if (filterButton && filterDropdown) {
                    // Add click effect to filter button
                    filterButton.addEventListener('click', function() {
                        // Add visual feedback
                        filterButton.classList.add('transform', 'scale-95');
                        setTimeout(() => {
                            filterButton.classList.remove('transform', 'scale-95');
                        }, 100);
                        
                        // Toggle hidden class
                        filterDropdown.classList.toggle('hidden');
                        
                        // Add animation effect
                        if (!filterDropdown.classList.contains('hidden')) {
                            filterDropdown.style.opacity = '0';
                            filterDropdown.style.transform = 'translateY(-10px)';
                            
                            // Trigger reflow
                            void filterDropdown.offsetWidth;
                            
                            // Animate in
                            filterDropdown.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            filterDropdown.style.opacity = '1';
                            filterDropdown.style.transform = 'translateY(0)';
                        }
                    });
                }
                
                // Cancel filter
                if (cancelFilter) {
                    cancelFilter.addEventListener('click', function() {
                        if (filterDropdown) {
                            filterDropdown.classList.add('hidden');
                        }
                    });
                }
                
                // Hide dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (filterButton && filterDropdown) {
                        const isClickInside = filterButton.contains(event.target) || filterDropdown.contains(event.target);
                        if (!isClickInside && !filterDropdown.classList.contains('hidden')) {
                            filterDropdown.classList.add('hidden');
                        }
                    }
                });
                
                // Handle export button click to preserve filter parameters
                const exportButton = document.querySelector('.export-button');
                if (exportButton) {
                    exportButton.addEventListener('click', function(e) {
                        // Prevent default behavior
                        e.preventDefault();
                        
                        // Get current filter parameters
                        const search = this.getAttribute('data-search');
                        const classificationId = this.getAttribute('data-classification');
                        const locationId = this.getAttribute('data-location');
                        const startDate = this.getAttribute('data-start');
                        const endDate = this.getAttribute('data-end');
                        
                        // Build URL with parameters
                        let exportUrl = "{{ route('archive.export') }}?";
                        const params = [];
                        
                        if (search) params.push("search=" + encodeURIComponent(search));
                        if (classificationId) params.push("classificationId=" + encodeURIComponent(classificationId));
                        if (locationId) params.push("locationId=" + encodeURIComponent(locationId));
                        if (startDate) params.push("startDate=" + encodeURIComponent(startDate));
                        if (endDate) params.push("endDate=" + encodeURIComponent(endDate));
                        
                        exportUrl += params.join("&");
                        
                        // Open export URL in new tab
                        window.open(exportUrl, '_blank');
                    });
                }
            });
        </script>

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
            
            <!-- Informasi tambahan tentang export -->
            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200 mt-4">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-blue-700">
                        {{ __('Catatan: Fitur export ke Excel hanya akan mengekspor data yang sesuai dengan filter yang sedang aktif. Pastikan filter sudah disetel sesuai kebutuhan sebelum melakukan export.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>