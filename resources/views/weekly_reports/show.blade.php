<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Laporan Mingguan ke-{{ $weeklyReport->week_number }} : {{ $project->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('weekly-reports.download-pdf', $weeklyReport) }}" target="_blank" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700">
                    Cetak PDF
                </a>
                <a href="{{ route('projects.show', $project) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'progress' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4">
                <nav class="flex space-x-4 border-b">
                    <button @click="activeTab = 'progress'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'progress' }" class="px-3 py-2 border-b-2 border-transparent font-medium text-sm">
                        Input Progress
                    </button>
                    <button @click="activeTab = 'visual'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'visual' }" class="px-3 py-2 border-b-2 border-transparent font-medium text-sm">
                        Laporan Visual
                    </button>
                    <button @click="activeTab = 'scurve'" :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'scurve' }" class="px-3 py-2 border-b-2 border-transparent font-medium text-sm">
                        Kurva S
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
                                                        <input type="number" step="0.01" name="progress[{{ $item->id }}]" value="{{ old('progress.' . $item->id, isset($progresses[$item->id]) && $progresses[$item->id] > 0 ? round($progresses[$item->id], 2) : '') }}" class="w-full border-gray-300 rounded text-right shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
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
                                                    <input type="number" step="0.01" name="progress[{{ $subItem->id }}]" value="{{ old('progress.' . $subItem->id, isset($progresses[$subItem->id]) && $progresses[$subItem->id] > 0 ? round($progresses[$subItem->id], 2) : '') }}" class="w-full border-gray-300 rounded text-right shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
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
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow">Simpan Progress</button>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @for($i = 1; $i <= 8; $i++)
                            @php $visual = $visuals[$i-1] ?? null; @endphp
                            <div class="border p-4 rounded-lg">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gambar {{ $i }}</label>
                                @if($visual && $visual->image_path)
                                    <input type="hidden" name="visual_id_{{ $i }}" value="{{ $visual->id }}">
                                    <div class="mb-2 relative">
                                        <img src="{{ asset($visual->image_path) }}" alt="Preview" class="h-32 object-cover rounded shadow border w-full">
                                        <label class="inline-flex items-center mt-2 text-sm text-red-600 font-semibold cursor-pointer">
                                            <input type="checkbox" name="delete_visual_{{ $i }}" value="1" class="rounded border-red-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                            <span class="ml-2">Hapus Gambar Ini</span>
                                        </label>
                                    </div>
                                    <label class="block text-xs text-gray-500 mb-1">Ganti Gambar (Opsional):</label>
                                @endif
                                <input type="file" name="visual_image_{{ $i }}" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <input type="text" name="visual_title_{{ $i }}" value="{{ old('visual_title_'.$i, $visual->title ?? '') }}" placeholder="Judul Gambar" class="mt-2 w-full border-gray-300 rounded shadow-sm">
                            </div>
                        @endfor
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700">Upload Visual</button>
                    </div>
                </form>
            </div>

            <!-- Tab S-Curve -->
            <div x-show="activeTab === 'scurve'" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6" x-cloak>
                <h3 class="text-lg font-bold mb-4">Kurva S (S-Curve)</h3>
                <div class="w-full h-96 bg-gray-50 border rounded-lg flex items-center justify-center">
                    <span class="text-gray-400">[Chart.js Canvas Placeholder]</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
