<x-filament-panels::page>
    <style>
        :root {
            --primary-50: #eff6ff;
            --primary-100: #dbeafe;
            --primary-200: #bfdbfe;
            --primary-300: #93c5fd;
            --primary-400: #60a5fa;
            --primary-500: #3b82f6;
            --primary-600: #2563eb;
            --primary-700: #1d4ed8;
            --primary-800: #1e40af;
            --primary-900: #1e3a8a;
            
            --secondary-50: #f8fafc;
            --secondary-100: #f1f5f9;
            --secondary-200: #e2e8f0;
            --secondary-300: #cbd5e1;
            --secondary-400: #94a3b8;
            --secondary-500: #64748b;
            --secondary-600: #475569;
            --secondary-700: #334155;
            --secondary-800: #1e293b;
            --secondary-900: #0f172a;
            
            --success-500: #10b981;
            --warning-500: #f59e0b;
            --danger-500: #ef4444;
        }
        
        .archive-container {
            max-width: 100%;
            margin: 0 auto;
        }
        
        .search-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .search-input {
            border: 2px solid var(--secondary-200);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--primary-400);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            padding: 0.75rem 1rem;
            cursor: pointer;
            border: none;
            position: relative;
        }
        
        .btn-primary {
            background-color: var(--primary-600);
            color: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-700);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-secondary {
            background-color: var(--secondary-100);
            color: var(--secondary-700);
        }
        
        .btn-secondary:hover {
            background-color: var(--secondary-200);
        }
        
        .btn-success {
            background-color: var(--success-500);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .btn-danger {
            background-color: var(--danger-500);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .filter-btn {
            position: relative;
        }
        
        .filter-indicator {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 12px;
            height: 12px;
            background-color: var(--danger-500);
            border-radius: 50%;
            border: 2px solid white;
            animation: pulse 2s infinite;
            z-index: 10;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }
        
        .filter-dropdown {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-top: 1rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .filter-dropdown.hidden {
            display: none;
        }
        
        .filter-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--secondary-800);
            margin-bottom: 1rem;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .filter-group {
            margin-bottom: 1rem;
        }
        
        .filter-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--secondary-700);
            margin-bottom: 0.5rem;
        }
        
        .filter-select, .filter-date {
            width: 100%;
            border: 2px solid var(--secondary-200);
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        
        .filter-select:focus, .filter-date:focus {
            outline: none;
            border-color: var(--primary-400);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        
        .filter-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding-top: 1rem;
            border-top: 1px solid var(--secondary-200);
        }
        
        .data-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .table-header {
            background-color: var(--secondary-50);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--secondary-200);
        }
        
        .table-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--secondary-800);
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        .archive-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .archive-table th {
            background-color: var(--secondary-50);
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--secondary-600);
            border-bottom: 1px solid var(--secondary-200);
        }
        
        .archive-table td {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            color: var(--secondary-700);
            border-bottom: 1px solid var(--secondary-100);
        }
        
        .archive-table tr:last-child td {
            border-bottom: none;
        }
        
        .archive-table tr:hover td {
            background-color: var(--primary-50);
        }
        
        .classification-code {
            font-weight: 600;
            color: var(--primary-700);
        }
        
        .document-name {
            font-weight: 500;
        }
        
        .date-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            background-color: var(--secondary-100);
            color: var(--secondary-700);
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .download-link {
            color: var(--primary-600);
            font-weight: 500;
            text-decoration: none;
        }
        
        .download-link:hover {
            text-decoration: underline;
        }
        
        .no-data {
            text-align: center;
            padding: 3rem;
            color: var(--secondary-500);
        }
        
        .no-data-icon {
            margin-bottom: 1rem;
            color: var(--secondary-300);
        }
        
        .export-info {
            background-color: var(--primary-50);
            border: 1px solid var(--primary-200);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .export-info-icon {
            color: var(--primary-500);
            flex-shrink: 0;
            margin-top: 0.25rem;
        }
        
        .export-info-text {
            font-size: 0.875rem;
            color: var(--primary-800);
            line-height: 1.5;
        }
        
        .tooltip {
            position: relative;
            display: inline-block;
        }
        
        .tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: var(--secondary-800);
            color: white;
            text-align: center;
            border-radius: 6px;
            padding: 0.5rem;
            position: absolute;
            z-index: 100;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.75rem;
            line-height: 1.4;
        }
        
        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        
        .tooltip .tooltip-text::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: var(--secondary-800) transparent transparent transparent;
        }
        
        .searchable-dropdown {
            position: relative;
        }
        
        .dropdown-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--secondary-200);
            border-radius: 8px;
            font-size: 0.875rem;
            cursor: pointer;
            background-color: white;
        }
        
        .dropdown-input:focus {
            outline: none;
            border-color: var(--primary-400);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        
        .dropdown-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--secondary-200);
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 50;
            max-height: 240px;
            overflow-y: auto;
        }
        
        .dropdown-option {
            padding: 0.75rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .dropdown-option:hover {
            background-color: var(--primary-50);
        }
        
        .dropdown-option.selected {
            background-color: var(--primary-100);
        }
        
        /* Specific styles for the account dropdown */
        #accountDropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--secondary-200);
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            z-index: 50;
            max-height: 240px;
            overflow-y: auto;
            margin-top: 0.25rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .search-card {
                padding: 1rem;
            }
            
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .btn {
                padding: 0.625rem 0.875rem;
                font-size: 0.8125rem;
            }
            
            .archive-table th, .archive-table td {
                padding: 0.75rem 1rem;
            }
        }
        
        @media (max-width: 640px) {
            .search-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .filter-actions {
                flex-direction: column;
            }
            
            .filter-actions .btn {
                width: 100%;
            }
        }
    </style>
    
    <div class="archive-container">
        <!-- Search Form -->
        <div class="search-card">
            <form method="GET">
                <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
                    <div class="flex-grow">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ $search ?? '' }}"
                            placeholder="{{ __('Cari arsip...') }}"
                            class="search-input w-full"
                        >
                    </div>
                    <div class="search-actions flex flex-wrap gap-2">
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Cari') }}
                        </button>
                        <button 
                            type="button" 
                            id="filterButton"
                            class="btn btn-secondary filter-btn"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Filter') }}
                            @if((isset($classificationId) && $classificationId) || (isset($locationId) && $locationId) || (isset($accountId) && $accountId) || (isset($startDate) && $startDate) || (isset($endDate) && $endDate))
                                <div class="filter-indicator"></div>
                            @endif
                        </button>
                        <div class="tooltip">
                            <a 
                                href="{{ route('archive.export') }}?search={{ urlencode(request()->query('search', '')) }}&classificationId={{ urlencode(request()->query('classificationId', '')) }}&locationId={{ urlencode(request()->query('locationId', '')) }}&accountId={{ urlencode(request()->query('accountId', '')) }}&startDate={{ urlencode(request()->query('startDate', '')) }}&endDate={{ urlencode(request()->query('endDate', '')) }}"
                                class="btn btn-success export-button"
                                title="{{ __('Export ke Excel (hanya data terfilter)') }}"
                                data-search="{{ request()->query('search', '') }}"
                                data-classification="{{ request()->query('classificationId', '') }}"
                                data-location="{{ request()->query('locationId', '') }}"
                                data-account="{{ request()->query('accountId', '') }}"
                                data-start="{{ request()->query('startDate', '') }}"
                                data-end="{{ request()->query('endDate', '') }}"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                {{ __('Export') }}
                            </a>
                            <span class="tooltip-text">{{ __('Hanya mengekspor data yang sesuai dengan filter yang aktif') }}</span>
                        </div>
                        @if((isset($search) && $search) || (isset($classificationId) && $classificationId) || (isset($locationId) && $locationId) || (isset($accountId) && $accountId) || (isset($startDate) && $startDate) || (isset($endDate) && $endDate))
                            <a 
                                href="{{ request()->url() }}" 
                                class="btn btn-danger"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                {{ __('Reset') }}
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Hidden input for account ID to maintain state after form submission -->
                <input type="hidden" name="accountId" value="{{ request()->query('accountId', '') }}">
                
                <!-- Filter Dropdown -->
                <div id="filterDropdown" class="filter-dropdown {{ ((isset($classificationId) && $classificationId) || (isset($locationId) && $locationId) || (isset($accountId) && $accountId) || (isset($startDate) && $startDate) || (isset($endDate) && $endDate)) ? '' : 'hidden' }}">
                    <h3 class="filter-title">{{ __('Filter Arsip') }}</h3>
                    <div class="filter-grid">
                        <!-- Classification Filter -->
                        <div class="filter-group">
                            <label class="filter-label">{{ __('Klasifikasi') }}</label>
                            <select 
                                name="classificationId" 
                                class="filter-select"
                            >
                                <option value="">{{ __('Semua Klasifikasi') }}</option>
                                @foreach($classifications as $id => $label)
                                    <option value="{{ $id }}" {{ (isset($classificationId) && $classificationId == $id) ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Location Filter -->
                        <div class="filter-group">
                            <label class="filter-label">{{ __('Lokasi') }}</label>
                            <select 
                                name="locationId" 
                                class="filter-select"
                            >
                                <option value="">{{ __('Semua Lokasi') }}</option>
                                @foreach($locations as $id => $label)
                                    <option value="{{ $id }}" {{ (isset($locationId) && $locationId == $id) ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Account Code Filter -->
                        <div class="filter-group">
                            <label class="filter-label">{{ __('Kode Akun') }}</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="accountSearch" 
                                    placeholder="{{ __('Pilih kode akun...') }}"
                                    class="filter-select"
                                    value="{{ isset($accountId) && $accountId !== '' && isset($accounts) && isset($accounts[$accountId]) ? $accounts[$accountId] : '' }}"
                                    readonly
                                >
                                <input 
                                    type="hidden" 
                                    name="accountId" 
                                    id="selectedAccountId" 
                                    value="{{ $accountId ?? '' }}"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                
                                <!-- Dropdown options -->
                                <div id="accountDropdown" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto hidden">
                                    <div class="p-2">
                                        <input 
                                            type="text" 
                                            id="accountFilter" 
                                            placeholder="{{ __('Cari kode akun...') }}"
                                            class="w-full p-2 border border-gray-300 rounded filter-select text-sm"
                                        >
                                    </div>
                                    <div id="accountOptions" class="max-h-40 overflow-y-auto">
                                        <div class="account-option p-3 hover:bg-gray-100 cursor-pointer flex items-center" data-value="" data-label="{{ __('Semua Kode Akun') }}">
                                            <span>{{ __('Semua Kode Akun') }}</span>
                                        </div>
                                        @if(isset($accounts))
                                            @foreach($accounts as $id => $label)
                                                <div class="account-option p-3 hover:bg-gray-100 cursor-pointer flex items-center" 
                                                     data-value="{{ $id }}" 
                                                     data-label="{{ $label }}"
                                                     data-initial-selected="{{ $id == ($accountId ?? '') ? 'true' : 'false' }}">
                                                    <span>{{ $label }}</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Date Range Filter -->
                        <div class="filter-group">
                            <label class="filter-label">{{ __('Rentang Waktu Berkas') }}</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">{{ __('Tanggal Mulai') }}</label>
                                    <input 
                                        type="date" 
                                        name="startDate" 
                                        value="{{ $startDate ?? '' }}"
                                        class="filter-date"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">{{ __('Tanggal Selesai') }}</label>
                                    <input 
                                        type="date" 
                                        name="endDate" 
                                        value="{{ $endDate ?? '' }}"
                                        class="filter-date"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="filter-actions">
                        <button 
                            type="button" 
                            id="cancelFilter"
                            class="btn btn-secondary"
                        >
                            
                            {{ __('Batal') }}
                        </button>
                        <button 
                            type="submit" 
                            class="btn btn-primary"
                        >
                            
                            {{ __('Terapkan Filter') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <script>
            // Function to initialize filter functionality
            function initializeFilterFunctionality() {
                const filterButton = document.getElementById('filterButton');
                const filterDropdown = document.getElementById('filterDropdown');
                const cancelFilter = document.getElementById('cancelFilter');
                
                // Remove existing event listeners to prevent duplicates
                if (window.filterButtonListener) {
                    filterButton.removeEventListener('click', window.filterButtonListener);
                }
                if (window.cancelFilterListener) {
                    cancelFilter.removeEventListener('click', window.cancelFilterListener);
                }
                
                // Ensure elements exist before adding event listeners
                if (filterButton && filterDropdown) {
                    // Create listener function and store it for removal
                    window.filterButtonListener = function() {
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
                    };
                    
                    filterButton.addEventListener('click', window.filterButtonListener);
                }
                
                // Cancel filter
                if (cancelFilter && filterDropdown) {
                    window.cancelFilterListener = function() {
                        filterDropdown.classList.add('hidden');
                    };
                    
                    cancelFilter.addEventListener('click', window.cancelFilterListener);
                }
                
                // Hide dropdown when clicking outside - use event delegation to handle dynamic updates
                document.removeEventListener('click', window.outsideClickListener || function(){});
                window.outsideClickListener = function(event) {
                    if (filterButton && filterDropdown) {
                        const isClickInside = filterButton.contains(event.target) || filterDropdown.contains(event.target);
                        if (!isClickInside && !filterDropdown.classList.contains('hidden')) {
                            filterDropdown.classList.add('hidden');
                        }
                    }
                };
                document.addEventListener('click', window.outsideClickListener);
                
                // Handle export button click to preserve filter parameters - use event delegation
                document.removeEventListener('click', window.exportButtonListener || function(){});
                window.exportButtonListener = function(e) {
                    if (e.target.closest('.export-button')) {
                        e.preventDefault();
                        const button = e.target.closest('.export-button');
                        
                        // Get current filter parameters
                        const search = button.getAttribute('data-search');
                        const classificationId = button.getAttribute('data-classification');
                        const locationId = button.getAttribute('data-location');
                        const accountId = button.getAttribute('data-account');
                        const startDate = button.getAttribute('data-start');
                        const endDate = button.getAttribute('data-end');
                        
                        // Build URL with parameters
                        let exportUrl = "{{ route('archive.export') }}?";
                        const params = [];
                        
                        if (search) params.push("search=" + encodeURIComponent(search));
                        if (classificationId) params.push("classificationId=" + encodeURIComponent(classificationId));
                        if (locationId) params.push("locationId=" + encodeURIComponent(locationId));
                        if (accountId) params.push("accountId=" + encodeURIComponent(accountId));
                        if (startDate) params.push("startDate=" + encodeURIComponent(startDate));
                        if (endDate) params.push("endDate=" + encodeURIComponent(endDate));
                        
                        exportUrl += params.join("&");
                        
                        // Open export URL in new tab
                        window.open(exportUrl, '_blank');
                    }
                };
                document.addEventListener('click', window.exportButtonListener);
            }
            
            // Function to initialize searchable dropdown for account filter
            function initializeAccountFilter() {
                const accountSearch = document.getElementById('accountSearch');
                const accountDropdown = document.getElementById('accountDropdown');
                const accountFilter = document.getElementById('accountFilter');
                const accountOptions = document.querySelectorAll('.account-option');
                const selectedAccountIdInput = document.getElementById('selectedAccountId');
                
                if (!accountSearch) return;
                
                // Toggle dropdown visibility when clicking the input
                accountSearch.addEventListener('click', function() {
                    accountDropdown.classList.toggle('hidden');
                    if (!accountDropdown.classList.contains('hidden')) {
                        accountFilter.focus();
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!accountSearch.contains(e.target) && !accountDropdown.contains(e.target)) {
                        accountDropdown.classList.add('hidden');
                    }
                });
                
                // Filter options based on search input
                accountFilter.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    accountOptions.forEach(option => {
                        const label = option.getAttribute('data-label').toLowerCase();
                        if (label.includes(searchTerm)) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });
                });
                
                // Handle option selection
                accountOptions.forEach(option => {
                    option.addEventListener('click', function() {
                        const value = this.getAttribute('data-value');
                        const label = this.getAttribute('data-label');
                        
                        // Update the hidden input value
                        selectedAccountIdInput.value = value;
                        
                        // Update the display input
                        if (value) {
                            accountSearch.value = label;
                        } else {
                            accountSearch.value = '{{ __('Pilih kode akun...') }}';
                        }
                        
                        // Close the dropdown
                        accountDropdown.classList.add('hidden');
                    });
                });
                
                // Set initial value if one exists
                if (selectedAccountIdInput.value) {
                    const selectedOption = Array.from(accountOptions).find(option => 
                        option.getAttribute('data-value') === selectedAccountIdInput.value
                    );
                    if (selectedOption) {
                        accountSearch.value = selectedOption.getAttribute('data-label');
                    }
                } else {
                    // Check for initial selection based on data attribute
                    const initialSelected = Array.from(accountOptions).find(option => 
                        option.getAttribute('data-initial-selected') === 'true'
                    );
                    if (initialSelected) {
                        const value = initialSelected.getAttribute('data-value');
                        const label = initialSelected.getAttribute('data-label');
                        selectedAccountIdInput.value = value;
                        if (value) {
                            accountSearch.value = label;
                        } else {
                            accountSearch.value = '{{ __('Pilih kode akun...') }}';
                        }
                    }
                }
            }
            
            // Initialize on DOMContentLoaded
            document.addEventListener('DOMContentLoaded', initializeFilterFunctionality);
            
            // Initialize when Livewire components finish loading/hydrating
            document.addEventListener('livewire:navigated', initializeFilterFunctionality);
            document.addEventListener('livewire:updated', initializeFilterFunctionality);
            
            // Initialize account filter on DOMContentLoaded
            document.addEventListener('DOMContentLoaded', initializeAccountFilter);
            
            // Reinitialize account filter on Livewire events
            document.addEventListener('livewire:navigated', initializeAccountFilter);
            document.addEventListener('livewire:updated', initializeAccountFilter);
        </script>

        <div class="data-card">
            <div class="table-header">
                <h3 class="table-title">{{ __('Data Arsip') }}</h3>
            </div>
            <div class="table-container">
                <table class="archive-table">
                    <thead>
                        <tr>
                            <th>{{ __('Kode Klasifikasi') }}</th>
                            <th>{{ __('Nama Dokumen') }}</th>
                            <th>{{ __('Deskripsi') }}</th>
                            <th>{{ __('Tanggal Dokumen') }}</th>
                            <th>{{ __('Kurun Waktu') }}</th>
                            <th>{{ __('Jumlah Dokumen') }}</th>
                            <th>{{ __('Lokasi') }}</th>
                            <th>{{ __('Segment') }}</th>
                            <th style="width: 120px;">{{ __('Akun') }}</th>
                            <th>{{ __('Keterangan') }}</th>
                            <th>{{ __('Retensi Arsip Aktif') }}</th>
                            <th>{{ __('Retensi Arsip Inaktif') }}</th>
                            <th>{{ __('Nasib Akhir Arsip') }}</th>
                            <th>{{ __('Download') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($folders as $folder)
                            @forelse($folder->documents as $index => $document)
                                <tr>
                                    <td class="classification-code">
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
                                    <td class="document-name">
                                        {{ $document->name }}
                                    </td>
                                    <td>
                                        {{ $document->description ?? '' }}
                                    </td>
                                    <td>
                                        {{ $document->published_at ? $document->published_at->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        {{ $document->published_at ? $document->published_at->format('Y') : '-' }}
                                    </td>
                                    <td>
                                        1 Berkas
                                    </td>
                                    <td>
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
                                    <td>
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
                                    <td style="width: 120px; white-space: normal; word-wrap: break-word;">
                                        @foreach($document->accounts as $account)
                                            {{ $account->code }}@if(!$loop->last)<br style="margin-bottom: 1rem;">@endif
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $document->information ?? '' }}
                                    </td>
                                    <td>
                                        {{ $document->active_retention ?? '' }}
                                    </td>
                                    <td>
                                        {{ $document->inactive_retention ?? '' }}
                                    </td>
                                    <td>
                                        @php
                                        if ($document->condition == '0') { 
                                            echo '<span class="status-badge status-inactive">Musnah</span>';
                                        } elseif ($document->condition == '1') {
                                            echo '<span class="status-badge status-active">Tidak Musnah</span>';
                                        } else {
                                            echo '-';
                                        }
                                        @endphp
                                    </td>
                                    <td>
                                        @if($document->file_path)
                                            <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="download-link">
                                                {{ __('Download') }}
                                            </a>
                                        @else
                                            <span class="text-gray-400">{{ __('Tidak tersedia') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="classification-code">
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
                                    <td class="document-name">
                                        -
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        1 Berkas
                                    </td>
                                    <td>
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
                                    <td>
                                        -
                                    </td>
                                    <td style="width: 120px; white-space: normal; word-wrap: break-word;">
                                        -
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        -
                                    </td>
                                </tr>
                            @endforelse
                        @endforeach
                        
                        @if(isset($search) && $search && $folders->count() == 0)
                            <tr>
                                <td colspan="14" class="no-data">
                                    <div class="no-data-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p>{{ __('Tidak ada hasil ditemukan untuk pencarian: ') }} <strong>"{{ $search }}"</strong></p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Informasi tambahan tentang export -->
        <div class="export-info">
            <div class="export-info-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <p class="export-info-text">
                {{ __('Catatan: Fitur export ke Excel hanya akan mengekspor data yang sesuai dengan filter yang sedang aktif. Pastikan filter sudah disetel sesuai kebutuhan sebelum melakukan export.') }}
            </p>
        </div>
    </div>
</x-filament-panels::page>