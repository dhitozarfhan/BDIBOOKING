<aside class="w-64 min-h-screen bg-base-100 border-r border-base-200 flex flex-col shrink-0">
    {{-- Profile --}}
    <div class="px-5 py-5 border-b border-base-200">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-md">
                <span class="text-sm font-bold text-white">{{ strtoupper(substr(Auth::guard('participant')->user()->name ?? 'P', 0, 1)) }}</span>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-base-content truncate">{{ Auth::guard('participant')->user()->name ?? 'Peserta' }}</p>
                <p class="text-xs text-base-content/40 truncate">Peserta Diklat</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1">
        <p class="px-3 pb-2 text-xs font-bold uppercase tracking-wider text-base-content/30">Menu</p>

        <a href="{{ route('participant.dashboard') }}" wire:navigate
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('participant.dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-base-content/60 hover:bg-base-200/70 hover:text-base-content' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            Dashboard
        </a>

        <a href="{{ route('participant.enrolled') }}" wire:navigate
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('participant.enrolled') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-base-content/60 hover:bg-base-200/70 hover:text-base-content' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            Diklat Diikuti
        </a>

        <a href="{{ route('participant.completed') }}" wire:navigate
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('participant.completed') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-base-content/60 hover:bg-base-200/70 hover:text-base-content' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Diklat Diselesaikan
        </a>

        <a href="{{ route('participant.profile') }}" wire:navigate
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
                  {{ request()->routeIs('participant.profile') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-base-content/60 hover:bg-base-200/70 hover:text-base-content' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            Profil
        </a>
    </nav>

    {{-- Logout --}}
    <div class="px-3 py-4 border-t border-base-200">
        <button wire:click="logout" class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-semibold bg-red-500 text-white hover:bg-red-600 shadow-sm hover:shadow-md transition-all duration-150">
            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
            Keluar
        </button>
    </div>
</aside>
