<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Progres & Laporan') }} : {{ $project->name }}
            </h2>
            <a href="{{ route('weekly-reports.projects') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300">
                &larr; Kembali ke Daftar Proyek
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || 'boq' }">
                    
                    {{-- Tab Navigation --}}
                    <div class="mb-6 border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8">
                            <button @click="activeTab = 'boq'"
                                    :class="activeTab === 'boq' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm">
                                1. Rincian Pekerjaan (BoQ)
                            </button>
                            <button @click="activeTab = 'weekly_reports'"
                                    :class="activeTab === 'weekly_reports' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm">
                                2. Laporan Mingguan
                            </button>
                            <button @click="activeTab = 'jadwal'"
                                    :class="activeTab === 'jadwal' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm">
                                3. Time Schedule (Gantt)
                            </button>
                            <button @click="activeTab = 'time_schedule'"
                                    :class="activeTab === 'time_schedule' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm">
                                4. Time Schedule & Kurva S
                            </button>
                        </nav>
                    </div>

                    {{-- Tab Contents --}}
                    <div>
                        @include('projects.partials.boq-tab')
                        @include('projects.partials.weekly-reports-tab')
                        @include('projects.partials.jadwal-tab')
                        @include('projects.partials.time-schedule-tab')
                        @include('projects.partials.modal-manage-weeks')
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
