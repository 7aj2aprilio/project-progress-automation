<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Progres & Laporan Mingguan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="{ search: '' }">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                        <h3 class="text-lg font-bold">Pilih Proyek</h3>
                        
                        <!-- Search Bar -->
                        <div class="relative w-full md:w-1/3">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" x-model="search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5" placeholder="Cari nama proyek atau lokasi...">
                        </div>
                    </div>
                    
                    <div class="flex flex-col space-y-4">
                        @forelse($projects as $project)
                            <a href="{{ route('weekly-reports.project-dashboard', $project) }}" 
                               x-show="search === '' || '{{ strtolower(addslashes($project->name . ' ' . ($project->information->lokasi_project ?? ''))) }}'.includes(search.toLowerCase())"
                               class="flex items-center justify-between p-5 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition w-full">
                                <div>
                                    <h5 class="mb-1 text-xl font-bold tracking-tight text-gray-900">{{ $project->name }}</h5>
                                    <p class="font-normal text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $project->information->lokasi_project ?? 'Lokasi belum diset' }}
                                    </p>
                                </div>
                                <div class="flex items-center text-indigo-600 text-sm font-semibold shrink-0">
                                    Kelola Laporan <span class="ml-2 text-lg">&rarr;</span>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <p class="text-gray-500 italic">Belum ada proyek yang terdaftar.</p>
                            </div>
                        @endforelse
                        
                        <!-- Empty state for search -->
                        <div x-show="search !== '' && !Array.from($el.parentElement.children).some(el => el.tagName === 'A' && el.style.display !== 'none')" 
                             style="display: none;"
                             class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                            <p class="text-gray-500 italic">Tidak ada proyek yang sesuai dengan pencarian '<span x-text="search" class="font-semibold"></span>'.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
