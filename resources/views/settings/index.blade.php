<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Global Settings (Assumptions)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-6 text-sm text-gray-600">
                        These settings are <strong>Global Assumptions</strong> that apply to all projects in automatic calculations.
                    </p>

                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Interest Rate per year (%)</label>
                                    <input type="number" step="0.01" name="suku_bunga_pertahun" value="{{ old('suku_bunga_pertahun', round($settings['suku_bunga_pertahun'], 2)) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Interest Rate per month (%)</label>
                                    <input type="number" step="0.0001" name="suku_bunga_perbulan" value="{{ old('suku_bunga_perbulan', isset($settings['suku_bunga_perbulan']) ? round($settings['suku_bunga_perbulan'], 4) : round($settings['suku_bunga_pertahun'] / 12, 4)) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Capital: Loan (%)</label>
                                    <input type="number" step="0.01" name="pemodalan_loan" value="{{ old('pemodalan_loan', round($settings['pemodalan_loan'], 2)) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Provision (%)</label>
                                    <input type="number" step="0.01" name="provisi_rate" value="{{ old('provisi_rate', round($settings['provisi_rate'], 2)) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">VAT (%)</label>
                                    <input type="number" step="0.01" name="ppn_rate" value="{{ old('ppn_rate', round($settings['ppn_rate'], 2)) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Income Tax Article 23 (%)</label>
                                    <input type="number" step="0.01" name="pph_rate" value="{{ old('pph_rate', round($settings['pph_rate'], 2)) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Feasibility Threshold (%)</label>
                                    <input type="number" step="0.01" name="feasibility_threshold" value="{{ old('feasibility_threshold', round($settings['feasibility_threshold'], 2)) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
