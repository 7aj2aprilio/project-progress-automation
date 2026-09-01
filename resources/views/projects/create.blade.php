<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight">
            {{ __('Buat Proyek Baru') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 p-6">
                <form method="POST" action="{{ route('projects.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Proyek</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                               placeholder="Masukkan nama proyek...">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-slate-900">Batal</a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-primary-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-600 focus:bg-primary-600 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                            Buat Proyek
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
