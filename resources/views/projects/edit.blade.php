<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                Edit: {{ $project->name }}
            </h2>
            <a href="{{ route('projects.show', $project) }}"
               class="inline-flex items-center px-4 py-2 bg-slate-200 border border-transparent rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-300 transition ease-in-out duration-150">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="px-4 sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('projects.update', $project) }}">
                @csrf
                @method('PUT')

                {{-- Project Header --}}
                <div class="bg-white shadow-sm rounded-xl border border-slate-200 p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Proyek</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" required
                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                            <select name="status" id="status"
                                    class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="draft" {{ $project->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ $project->status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tabbed Modules --}}
                <div x-data="{ activeTab: 'informasi' }" class="bg-white shadow-sm rounded-xl border border-slate-200 mb-6">
                    {{-- Tab Navigation --}}
                    <div class="border-b border-slate-200 overflow-x-auto">
                        <nav class="flex -mb-px whitespace-nowrap">
                            @php
                                $tabs = [
                                    'informasi' => '1. Informasi',
                                    'cost_beban' => '2. Cost & Beban',
                                    'revenue' => '3. Revenue',
                                    'jaminan' => '4. Jaminan',
                                    'pajak' => '5. Pajak',
                                    'pinjaman' => '6. Pinjaman',
                                ];
                            @endphp
                            @foreach($tabs as $key => $label)
                                <button type="button" @click="activeTab = '{{ $key }}'"
                                        :class="activeTab === '{{ $key }}' ? 'border-primary-500 text-primary-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                                        class="px-4 py-4 text-sm font-medium border-b-2 transition-colors">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </nav>
                    </div>

                    <div class="p-6">
                        @php
                            $info = $project->information;
                            $asumsi = $project->assumption;
                            $cost = $project->costStructure;
                        @endphp

                        {{-- Tab 1: Informasi Project --}}
                        <div x-show="activeTab === 'informasi'" x-cloak>
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Informasi Project</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Pelanggan</label>
                                    <input type="text" name="information[nama_pelanggan]" value="{{ old('information.nama_pelanggan', $info->nama_pelanggan) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Kategori Project</label>
                                    <input type="text" name="information[kategori_project]" value="{{ old('information.kategori_project', $info->kategori_project) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Project</label>
                                    <input type="text" name="information[nama_project]" value="{{ old('information.nama_project', $info->nama_project) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Lokasi Project</label>
                                    <input type="text" name="information[lokasi_project]" value="{{ old('information.lokasi_project', $info->lokasi_project) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Estimasi Mulai Pekerjaan</label>
                                    <input type="month" name="information[estimasi_mulai]" value="{{ old('information.estimasi_mulai', $info->estimasi_mulai?->format('Y-m')) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Durasi Project (bulan)</label>
                                    <input type="number" name="information[durasi_project]" value="{{ old('information.durasi_project', $info->durasi_project) }}" min="0"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Durasi Retensi (bulan)</label>
                                    <input type="number" name="information[durasi_retensi]" value="{{ old('information.durasi_retensi', $info->durasi_retensi) }}" min="0"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Pengawasan Konstruksi</label>
                                    <select name="information[pengawasan_konstruksi]"
                                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">-- Pilih --</option>
                                        <option value="Sendiri" {{ old('information.pengawasan_konstruksi', $info->pengawasan_konstruksi) === 'Sendiri' ? 'selected' : '' }}>Sendiri</option>
                                        <option value="Menggunakan MK" {{ old('information.pengawasan_konstruksi', $info->pengawasan_konstruksi) === 'Menggunakan MK' ? 'selected' : '' }}>Menggunakan MK</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Tipe Bangunan</label>
                                    <input type="text" name="information[tipe_bangunan]" value="{{ old('information.tipe_bangunan', $info->tipe_bangunan) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">TOP Pembayaran Mitra</label>
                                    <input type="text" name="information[top_pembayaran_mitra]" value="{{ old('information.top_pembayaran_mitra', $info->top_pembayaran_mitra) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">TOP Pembayaran Pelanggan</label>
                                    <input type="text" name="information[top_pembayaran_pelanggan]" value="{{ old('information.top_pembayaran_pelanggan', $info->top_pembayaran_pelanggan) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                            </div>
                        </div>

                        {{-- Tab 2: Cost & Beban --}}
                        <div x-show="activeTab === 'cost_beban'" x-cloak>
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Cost Structure & Beban Lainnya</h3>
                            
                            {{-- Cost Mitra (Fixed) --}}
                            <div class="mb-6 p-4 border border-slate-200 rounded-lg bg-slate-50">
                                <h4 class="font-medium text-slate-900 mb-2">Cost Utama</h4>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Biaya Mitra Pelaksana (Exclude PPN)</label>
                                    <input type="number" name="cost_structure[biaya_mitra_pelaksana]" value="{{ old('cost_structure.biaya_mitra_pelaksana', $cost->biaya_mitra_pelaksana ? round($cost->biaya_mitra_pelaksana) : '') }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <p class="text-xs text-slate-500 mt-1">Nilai ini digunakan sebagai dasar perhitungan Pinjaman otomatis.</p>
                                </div>
                            </div>

                            {{-- Beban Lainnya (Dynamic) --}}
                            <div x-data="{ rows: {{ json_encode(old('beban', $project->beban->map(fn($b) => ['name' => $b->name, 'amount' => round($b->amount)])->toArray() ?: [['name' => '', 'amount' => '']])) }} }">
                                <h4 class="font-medium text-slate-900 mb-2">Beban Lainnya (Dinamis)</h4>
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="flex gap-2 mb-2 items-start">
                                        <div class="w-1/2">
                                            <input type="text" x-model="row.name" :name="'beban['+index+'][name]'" placeholder="Nama Beban (contoh: Biaya Pengawasan)"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="w-1/2 flex gap-2">
                                            <input type="number" x-model="row.amount" :name="'beban['+index+'][amount]'" placeholder="Nominal"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <button type="button" @click="rows.splice(index, 1)" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200">✕</button>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" @click="rows.push({name: '', amount: ''})" class="mt-2 text-sm text-primary-600 hover:text-primary-800 font-medium">
                                    + Tambah Beban
                                </button>
                            </div>
                        </div>

                        {{-- Tab 4: Revenue --}}
                        <div x-show="activeTab === 'revenue'" x-cloak x-data="{ mode: '{{ $info->total_revenue && count($project->revenues) === 0 ? 'total' : 'rincian' }}' }">
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Revenue Stream / Cash In</h3>
                            
                            <div class="flex gap-4 mb-4 border-b pb-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="revenue_mode" x-model="mode" value="total" class="text-primary-600 border-slate-300 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700">Input Total Langsung</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="revenue_mode" x-model="mode" value="rincian" class="text-primary-600 border-slate-300 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700">Input Rincian</span>
                                </label>
                            </div>

                            {{-- Total Input --}}
                            <div x-show="mode === 'total'" class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Total Revenue</label>
                                <input type="number" name="information[total_revenue]" value="{{ old('information.total_revenue', $info->total_revenue ? round($info->total_revenue) : '') }}"
                                       class="w-full md:w-1/2 rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>

                            {{-- Dynamic Rincian --}}
                            <div x-show="mode === 'rincian'" x-data="{ rows: {{ json_encode(old('revenues', $project->revenues->map(fn($r) => ['name' => $r->name, 'amount' => round($r->amount)])->toArray() ?: [['name' => '', 'amount' => '']])) }} }">
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="flex gap-2 mb-2 items-start">
                                        <div class="w-1/2">
                                            <input type="text" x-model="row.name" :name="'revenues['+index+'][name]'" placeholder="Nama Revenue (contoh: Jasa Konstruksi)"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="w-1/2 flex gap-2">
                                            <input type="number" x-model="row.amount" :name="'revenues['+index+'][amount]'" placeholder="Nominal"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <button type="button" @click="rows.splice(index, 1)" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200">✕</button>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" @click="rows.push({name: '', amount: ''})" class="mt-2 text-sm text-primary-600 hover:text-primary-800 font-medium">
                                    + Tambah Rincian
                                </button>
                                <p class="text-xs text-slate-500 mt-2">Jika rincian diisi, total revenue akan dijumlah otomatis dari rincian ini.</p>
                            </div>
                        </div>

                        {{-- Tab 5: Jaminan --}}
                        <div x-show="activeTab === 'jaminan'" x-cloak>
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Jaminan-Jaminan</h3>
                            <div x-data="{ rows: {{ json_encode(old('jaminan', $project->jaminan->map(fn($j) => ['name' => $j->name, 'percentage' => $j->percentage ? round($j->percentage, 2) : '', 'amount' => $j->amount ? round($j->amount) : ''])->toArray() ?: [
                                ['name' => 'Jaminan Uang Muka', 'percentage' => '', 'amount' => ''],
                                ['name' => 'Jaminan Penawaran', 'percentage' => '', 'amount' => ''],
                                ['name' => 'Jaminan Pelaksanaan', 'percentage' => '', 'amount' => ''],
                                ['name' => 'Jaminan Pemeliharaan', 'percentage' => '', 'amount' => '']
                            ])) }} }">
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-2 mb-2 items-start p-3 bg-slate-50 rounded-lg border">
                                        <div class="md:col-span-4">
                                            <label class="block text-xs text-slate-500 mb-1">Nama Jaminan</label>
                                            <input type="text" x-model="row.name" :name="'jaminan['+index+'][name]'" placeholder="Nama"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-xs text-slate-500 mb-1">Persentase (%)</label>
                                            <input type="number" step="0.01" x-model="row.percentage" :name="'jaminan['+index+'][percentage]'" placeholder="%"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="md:col-span-4">
                                            <label class="block text-xs text-slate-500 mb-1">Nilai (kosong = hitung dari %)</label>
                                            <input type="number" x-model="row.amount" :name="'jaminan['+index+'][amount]'" placeholder="Nominal Override"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="md:col-span-1 flex items-end justify-center h-full pb-1">
                                            <button type="button" @click="rows.splice(index, 1)" class="p-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200">✕</button>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" @click="rows.push({name: '', percentage: '', amount: ''})" class="mt-2 text-sm text-primary-600 hover:text-primary-800 font-medium">
                                    + Tambah Jaminan
                                </button>
                            </div>
                        </div>

                        {{-- Tab 6: Pajak --}}
                        <div x-show="activeTab === 'pajak'" x-cloak>
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Perpajakan</h3>
                            <p class="text-sm text-slate-500 mb-4">Gunakan nama yang memuat "PPh", "PPN Keluaran", atau "PPN Masukan" untuk mengaktifkan perhitungan otomatis dari Global Settings.</p>
                            <div x-data="{ rows: {{ json_encode(old('pajak', $project->pajak->map(fn($p) => ['name' => $p->name, 'amount' => $p->amount ? round($p->amount) : ''])->toArray() ?: [
                                ['name' => 'PPh Pasal 23', 'amount' => ''],
                                ['name' => 'PPN Keluaran', 'amount' => ''],
                                ['name' => 'PPN Masukan', 'amount' => '']
                            ])) }} }">
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="flex gap-2 mb-2 items-start">
                                        <div class="w-1/2">
                                            <input type="text" x-model="row.name" :name="'pajak['+index+'][name]'" placeholder="Nama Pajak"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="w-1/2 flex gap-2">
                                            <input type="number" x-model="row.amount" :name="'pajak['+index+'][amount]'" placeholder="Nominal Override (kosong=auto)"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <button type="button" @click="rows.splice(index, 1)" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200">✕</button>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" @click="rows.push({name: '', amount: ''})" class="mt-2 text-sm text-primary-600 hover:text-primary-800 font-medium">
                                    + Tambah Pajak
                                </button>
                            </div>
                        </div>

                        {{-- Tab 6: Pinjaman --}}
                        <div x-show="activeTab === 'pinjaman'" x-cloak>
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Pinjaman</h3>
                            
                            <div class="mb-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Rate Besar Pinjaman Khusus Proyek (%)</label>
                                <input type="number" step="0.01" name="information[loan_rate]" value="{{ old('information.loan_rate', $info->loan_rate ? round($info->loan_rate, 2) : '') }}"
                                       placeholder="Contoh: 1.65"
                                       class="w-full md:w-1/2 rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <p class="text-xs text-slate-500 mt-1">Digunakan untuk menghitung "Besar Pinjaman" dari "Cost Mitra". Kosong = auto pakai default 1.65%.</p>
                                
                                <label class="block text-sm font-medium text-slate-700 mb-1 mt-4">Periode Pinjaman (Bulan)</label>
                                <input type="number" step="1" name="assumption[periode_pinjaman]" value="{{ old('assumption.periode_pinjaman', $asumsi->periode_pinjaman ?? '') }}"
                                       placeholder="Contoh: 12"
                                       class="w-full md:w-1/2 rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <p class="text-xs text-slate-500 mt-1">Lama pinjaman dalam bulan.</p>
                            </div>

                            <p class="text-sm text-slate-500 mb-4">Gunakan nama "Besar Pinjaman", "Biaya Provisi", atau "Bunga Pinjaman" untuk mengaktifkan perhitungan otomatis dari Global Settings.</p>
                            <div x-data="{ rows: {{ json_encode(old('pinjaman', $project->pinjaman->map(fn($p) => ['name' => $p->name, 'amount' => $p->amount ? round($p->amount) : ''])->toArray() ?: [
                                ['name' => 'Besar Pinjaman', 'amount' => ''],
                                ['name' => 'Biaya Provisi', 'amount' => ''],
                                ['name' => 'Bunga Pinjaman (per bulan)', 'amount' => '']
                            ])) }} }">
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="flex gap-2 mb-2 items-start">
                                        <div class="w-1/2">
                                            <input type="text" x-model="row.name" :name="'pinjaman['+index+'][name]'" placeholder="Nama Komponen Pinjaman"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="w-1/2 flex gap-2">
                                            <input type="number" x-model="row.amount" :name="'pinjaman['+index+'][amount]'" placeholder="Nominal Override (kosong=auto)"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <button type="button" @click="rows.splice(index, 1)" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200">✕</button>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" @click="rows.push({name: '', amount: ''})" class="mt-2 text-sm text-primary-600 hover:text-primary-800 font-medium">
                                    + Tambah Komponen Pinjaman
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-4">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-primary-700 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-600 focus:bg-primary-600 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        💾 Simpan & Hitung Ulang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const numberInputs = document.querySelectorAll('input[type="number"]');
            numberInputs.forEach(input => {
                // Skip small numbers like percentages or durations
                if (input.name.includes('durasi') || input.name.includes('percentage') || input.step === "0.01") return;

                input.type = 'text';
                input.inputMode = 'numeric';
                
                // Format initial value
                if (input.value) {
                    let val = input.value.replace(/,/g, '');
                    if (!isNaN(val) && val !== '') {
                        input.value = Number(val).toLocaleString('en-US');
                    }
                }

                input.addEventListener('input', function(e) {
                    // Get cursor position
                    let cursor = e.target.selectionStart;
                    let originalLength = e.target.value.length;
                    
                    // Remove non-digits
                    let value = e.target.value.replace(/[^0-9]/g, '');
                    if (value !== '') {
                        e.target.value = parseInt(value, 10).toLocaleString('en-US');
                        
                        // Adjust cursor
                        let newLength = e.target.value.length;
                        cursor += (newLength - originalLength);
                        e.target.setSelectionRange(cursor, cursor);
                    } else {
                        e.target.value = '';
                    }
                    
                    // If this input is bound to Alpine x-model, dispatch event to update it
                    if (e.target.getAttribute('x-model')) {
                        e.target.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                });
            });

            // Clean up before submit
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    document.querySelectorAll('input[type="text"]').forEach(input => {
                        if (input.value.includes(',')) {
                            input.value = input.value.replace(/,/g, '');
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
