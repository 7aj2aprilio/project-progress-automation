<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Laporan Mingguan ke-{{ $weeklyReport->week_number }} : {{ $project->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('weekly-reports.download-pdf', $weeklyReport) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700">
                    Cetak PDF
                </a>
                <a href="{{ route('weekly-reports.project-dashboard', ['project' => $project->id, 'tab' => 'weekly_reports']) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || 'progress' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4">
                <nav class="flex space-x-4 border-b">
                    <button @click="activeTab = 'progress'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'progress' }" class="px-3 py-2 border-b-2 border-transparent font-medium text-sm">
                        Input Progress
                    </button>
                    <button @click="activeTab = 'visual'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'visual' }" class="px-3 py-2 border-b-2 border-transparent font-medium text-sm">
                        Laporan Visual
                    </button>
                </nav>
            </div>

            <!-- Menampilkan Pesan Error / Sukses -->
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Ada kesalahan:</strong>
                    <ul class="mt-2 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tab Progress -->
            <div x-show="activeTab === 'progress'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('weekly-reports.update', $weeklyReport) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th rowspan="2" class="px-4 py-2 border text-xs text-center font-medium">NO</th>
                                    <th rowspan="2" class="px-4 py-2 border text-xs text-left font-medium">ITEM PEKERJAAN</th>
                                    <th rowspan="2" class="px-4 py-2 border text-xs text-center font-medium">BOBOT (%)</th>
                                    <th colspan="2" class="px-4 py-2 border text-xs text-center font-medium">S/D MINGGU INI</th>
                                </tr>
                                <tr>
                                    <th class="px-4 py-2 border text-xs text-center font-medium bg-indigo-50 text-indigo-700">PRESTASI (%)<br>(Input)</th>
                                    <th class="px-4 py-2 border text-xs text-center font-medium">BOBOT (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workItems as $mainIndex => $mainItem)
                                    @php
                                        $mBobot = $mainItem->base_bobot;
                                        $mBobotSD = 0;
                                        foreach($mainItem->children as $child) {
                                            if ($child->type === 'sub') {
                                                foreach($child->children as $c) {
                                                    $mBobotSD += ($c->base_bobot * ($progresses[$c->id] ?? 0)) / 100;
                                                }
                                            } else {
                                                $mBobotSD += ($child->base_bobot * ($progresses[$child->id] ?? 0)) / 100;
                                            }
                                        }
                                    @endphp
                                    @php
                                        $mainClasses = $mainIndex > 0 ? 'border-t-4 border-t-slate-300' : '';
                                    @endphp
                                    <tr class="bg-gray-100 font-bold">
                                        <td class="px-4 py-2 border {{ $mainClasses }} text-center">{{ App\Helpers\FormatHelper::romawi($mainIndex + 1) }}</td>
                                        <td class="px-4 py-2 border {{ $mainClasses }}">{{ $mainItem->name }}</td>
                                        <td class="px-4 py-2 border {{ $mainClasses }} text-right">{{ number_format($mBobot, 2) }}</td>
                                        <td class="px-4 py-2 border {{ $mainClasses }} text-right bg-indigo-50"></td>
                                        <td class="px-4 py-2 border {{ $mainClasses }} text-right">{{ number_format($mBobotSD, 2) }}</td>
                                    </tr>
                                    @foreach($mainItem->children as $subIndex => $subItem)
                                        @if($subItem->type === 'sub')
                                            @php
                                                $sBobotSD = 0;
                                                foreach($subItem->children as $c) {
                                                    $sBobotSD += ($c->base_bobot * ($progresses[$c->id] ?? 0)) / 100;
                                                }
                                            @endphp
                                            @php
                                                $subClasses = $subIndex > 0 ? 'border-t-[3px] border-t-slate-200' : '';
                                            @endphp
                                            <tr class="font-semibold bg-gray-50">
                                                <td class="px-4 py-2 border {{ $subClasses }} text-center"></td>
                                                <td class="px-4 py-2 border {{ $subClasses }} pl-8">{{ $subItem->name }}</td>
                                                <td class="px-4 py-2 border {{ $subClasses }} text-right">{{ number_format($subItem->base_bobot, 2) }}</td>
                                                <td class="px-4 py-2 border {{ $subClasses }} text-right bg-indigo-50"></td>
                                                <td class="px-4 py-2 border {{ $subClasses }} text-right">{{ number_format($sBobotSD, 2) }}</td>
                                            </tr>
                                            @foreach($subItem->children as $itemIndex => $item)
                                                <tr>
                                                    <td class="px-4 py-2 border text-center">{{ $itemIndex + 1 }}</td>
                                                    <td class="px-4 py-2 border pl-12">{{ $item->name }}</td>
                                                    <td class="px-4 py-2 border text-right" id="bobot_{{ $item->id }}">{{ number_format($item->base_bobot, 2) }}</td>
                                                    <td class="px-4 py-2 border">
                                                        @php
                                                            $prevProgress = $previousProgresses[$item->id] ?? 0;
                                                            $currProgress = old('progress.' . $item->id, $progresses[$item->id] ?? $prevProgress);
                                                            $isComplete = $prevProgress >= 100;
                                                        @endphp
                                                        @if($isComplete)
                                                            <input type="number" name="progress[{{ $item->id }}]" value="100" readonly class="w-full border-gray-200 bg-gray-100 text-gray-500 rounded text-right shadow-sm cursor-not-allowed" title="Sudah 100% di minggu sebelumnya">
                                                        @else
                                                            <input type="number" step="0.01" min="{{ $prevProgress }}" max="100" name="progress[{{ $item->id }}]" value="{{ $currProgress > 0 ? round($currProgress, 2) : '' }}" class="w-full border-gray-300 rounded text-right shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 border text-right bg-gray-50">
                                                        {{ number_format(($progresses[$item->id] ?? 0) / 100 * $item->base_bobot, 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="px-4 py-2 border text-center">{{ $subIndex + 1 }}</td>
                                                <td class="px-4 py-2 border pl-8">{{ $subItem->name }}</td>
                                                <td class="px-4 py-2 border text-right" id="bobot_{{ $subItem->id }}">{{ number_format($subItem->base_bobot, 2) }}</td>
                                                <td class="px-4 py-2 border">
                                                    @php
                                                        $prevProgress = $previousProgresses[$subItem->id] ?? 0;
                                                        $currProgress = old('progress.' . $subItem->id, $progresses[$subItem->id] ?? $prevProgress);
                                                        $isComplete = $prevProgress >= 100;
                                                    @endphp
                                                    @if($isComplete)
                                                        <input type="number" name="progress[{{ $subItem->id }}]" value="100" readonly class="w-full border-gray-200 bg-gray-100 text-gray-500 rounded text-right shadow-sm cursor-not-allowed" title="Sudah 100% di minggu sebelumnya">
                                                    @else
                                                        <input type="number" step="0.01" min="{{ $prevProgress }}" max="100" name="progress[{{ $subItem->id }}]" value="{{ $currProgress > 0 ? round($currProgress, 2) : '' }}" class="w-full border-gray-300 rounded text-right shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 border text-right bg-gray-50">
                                                    {{ number_format(($progresses[$subItem->id] ?? 0) / 100 * $subItem->base_bobot, 2) }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-end">
                        @if(auth()->user()->canEdit())
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow">Simpan Progress</button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tab Visual -->
            <div x-show="activeTab === 'visual'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6" x-cloak>
                <form action="{{ route('weekly-reports.visuals.store', $weeklyReport) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Logo Upload Section -->
                    <div class="mb-6 p-4 border rounded-lg bg-gray-50">
                        <h4 class="font-bold text-gray-700 mb-2">Logo Laporan (Kiri Cover)</h4>
                        <div class="flex items-start gap-4">
                            @if($weeklyReport->logo_left_path)
                                <div class="relative">
                                    <img src="{{ asset($weeklyReport->logo_left_path) }}" alt="Logo" class="h-16 object-contain bg-white p-1 border rounded shadow-sm">
                                    <label class="block mt-2 text-sm text-red-600 font-semibold cursor-pointer">
                                        <input type="checkbox" name="delete_logo" value="1" class="rounded border-red-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                        Hapus Logo
                                    </label>
                                </div>
                            @endif
                            <div class="flex-1">
                                <label class="block text-xs text-gray-500 mb-1">{{ $weeklyReport->logo_left_path ? 'Ganti Logo:' : 'Upload Logo:' }}</label>
                                <input type="file" name="logo_left" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>
                    </div>

                    <div x-data="{ newVisuals: [] }" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($weeklyReport->visuals()->orderBy('position')->get() as $index => $visual)
                                <div class="border p-4 rounded-lg bg-white shadow-sm">
                                    <input type="hidden" name="existing_visual_ids[]" value="{{ $visual->id }}">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Visual {{ $index + 1 }}</label>
                                        <label class="inline-flex items-center text-sm text-red-600 font-semibold cursor-pointer">
                                            <input type="checkbox" name="delete_visuals[{{ $visual->id }}]" value="1" class="rounded border-red-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                            <span class="ml-1">Hapus</span>
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <img src="{{ asset($visual->image_path) }}" alt="Preview" class="h-40 w-full object-cover rounded border">
                                    </div>
                                    <div class="space-y-2">
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">Ganti Gambar (Opsional):</label>
                                            <input type="file" name="existing_visual_images[{{ $visual->id }}]" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                        </div>
                                        <div>
                                            <input type="text" name="existing_visual_titles[{{ $visual->id }}]" value="{{ $visual->title }}" placeholder="Judul Gambar" class="w-full border-gray-300 rounded shadow-sm text-sm">
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <template x-for="(item, index) in newVisuals" :key="item.id">
                                <div class="border p-4 rounded-lg bg-gray-50 border-dashed border-2 border-indigo-300 relative">
                                    <button type="button" @click="newVisuals.splice(index, 1)" class="absolute top-2 right-2 text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Visual Baru</label>
                                    <div class="space-y-3">
                                        <div>
                                            <input type="file" :name="`new_visual_images[${item.id}]`" accept="image/*" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                        </div>
                                        <div>
                                            <input type="text" :name="`new_visual_titles[${item.id}]`" placeholder="Judul Gambar" class="w-full border-gray-300 rounded shadow-sm text-sm">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        @if(auth()->user()->canEdit())
                            <div class="mt-4">
                                <button type="button" @click="newVisuals.push({ id: Date.now() })" class="inline-flex items-center px-4 py-2 bg-white border border-indigo-300 rounded-lg text-sm font-semibold text-indigo-700 hover:bg-indigo-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Tambah Visual Baru
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 flex justify-end">
                        @if(auth()->user()->canEdit())
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Upload Visual</button>
                        @endif
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
