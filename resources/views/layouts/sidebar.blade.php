<aside x-data="{ mobileOpen: false }" class="flex flex-col fixed inset-y-0 left-0 w-64 bg-primary-900 text-white z-30 transform transition-transform duration-200 ease-in-out md:translate-x-0"
       :class="mobileOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Logo --}}
    <div class="flex items-center justify-center px-6 py-5 border-b border-primary-700/50">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo-telpro-putih.png') }}" alt="Telkom Property" class="h-12 w-auto">
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <p class="px-3 text-xs font-semibold uppercase tracking-wider text-primary-300 mb-2">Menu</p>

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('dashboard') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-800 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Profitability dan Proyek
        </a>

        {{-- Laporan Mingguan & BoQ --}}
        <a href="{{ route('weekly-reports.projects') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                  {{ request()->routeIs('weekly-reports.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-800 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Progres & Laporan
        </a>

        @if(auth()->user()->isAdmin())
            <p class="px-3 pt-4 text-xs font-semibold uppercase tracking-wider text-primary-300 mb-2">Administrasi</p>

            {{-- Kelola User --}}
            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('users.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Kelola User
            </a>

            {{-- Pengaturan Global --}}
            <a href="{{ route('settings.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150
                      {{ request()->routeIs('settings.*') ? 'bg-primary-700 text-white' : 'text-primary-100 hover:bg-primary-800 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Pengaturan Global
            </a>
        @endif
    </nav>

    {{-- User Info Footer --}}
    <div class="border-t border-primary-700/50 p-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-primary-600 flex items-center justify-center text-sm font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-primary-300 capitalize">{{ Auth::user()->role }}</p>
            </div>
        </div>
        <div class="mt-3 flex gap-2">
            <a href="{{ route('profile.edit') }}"
               class="flex-1 text-center text-xs py-1.5 rounded bg-primary-800 text-primary-200 hover:bg-primary-700 hover:text-white transition-colors">
                Profil
            </a>
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full text-xs py-1.5 rounded bg-primary-800 text-primary-200 hover:bg-red-600 hover:text-white transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Mobile overlay --}}
<div x-show="mobileOpen" @click="mobileOpen = false"
     class="fixed inset-0 bg-black/50 z-20 md:hidden" x-cloak></div>

{{-- Mobile toggle button --}}
<button @click="$dispatch('toggle-sidebar')"
        class="fixed top-4 left-4 z-40 p-2 rounded-lg bg-primary-700 text-white shadow-lg md:hidden">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>
