<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-slate-800 leading-tight">
                Edit: {{ $project->name }}
            </h2>
            <a href="{{ route('projects.show', $project) }}"
               class="inline-flex items-center px-4 py-2 bg-slate-200 border border-transparent rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-300 transition ease-in-out duration-150">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="px-4 sm:px-6 lg:px-8">

            @if(isset($errors) && $errors->any())
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
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Project Name</label>
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
                <div x-data="{ activeTab: '{{ request('tab', session('activeTab', 'informasi')) }}' }" class="bg-white shadow-sm rounded-xl border border-slate-200 mb-6">
                    {{-- Tab Navigation --}}
                    <div class="border-b border-slate-200 overflow-x-auto">
                        <nav class="flex -mb-px whitespace-nowrap">
                            @php
                                $tabs = [
                                    'informasi' => '1. Information & Assumptions',
                                    'cashflow' => '2. Project Cashflow (Main Input)',
                                    'cost_beban' => '3. Cost & Expenses',
                                    'revenue' => '4. Revenue',
                                    'jaminan' => '5. Guarantee',
                                    'pajak' => '6. Tax',
                                    'pinjaman' => '7. Loan',
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

                        {{-- Tab 1: Project Information --}}
                        <div x-show="activeTab === 'informasi'" x-cloak>
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Project Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Customer Name</label>
                                    <input type="text" name="information[nama_pelanggan]" value="{{ old('information.nama_pelanggan', $info->nama_pelanggan) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Project Category</label>
                                    <input type="text" name="information[kategori_project]" value="{{ old('information.kategori_project', $info->kategori_project) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Project Name</label>
                                    <input type="text" name="information[nama_project]" value="{{ old('information.nama_project', $info->nama_project) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Project Location</label>
                                    <input type="text" name="information[lokasi_project]" value="{{ old('information.lokasi_project', $info->lokasi_project) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Estimated Start <span class="text-red-500">*</span></label>
                                    <input type="date" name="information[estimasi_mulai]" value="{{ old('information.estimasi_mulai', $info->estimasi_mulai ? \Carbon\Carbon::parse($info->estimasi_mulai)->format('Y-m-d') : '') }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500" required>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-sm font-medium text-slate-700">Estimated End <span class="text-red-500">*</span></label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input type="date" name="information[estimasi_selesai]" value="{{ old('information.estimasi_selesai', $info->estimasi_selesai) }}"
                                            class="focus:ring-primary-500 focus:border-primary-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Retention Duration (months)</label>
                                    <input type="number" name="information[durasi_retensi]" value="{{ old('information.durasi_retensi', $info->durasi_retensi) }}" min="0"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Construction Supervision</label>
                                    <select name="information[pengawasan_konstruksi]"
                                            class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="">-- Select --</option>
                                        <option value="Self" {{ old('information.pengawasan_konstruksi', $info->pengawasan_konstruksi) === 'Self' ? 'selected' : '' }}>Self</option>
                                        <option value="Using CM (Construction Management)" {{ old('information.pengawasan_konstruksi', $info->pengawasan_konstruksi) === 'Using CM (Construction Management)' ? 'selected' : '' }}>Using CM (Construction Management)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Building Type</label>
                                    <input type="text" name="information[tipe_bangunan]" value="{{ old('information.tipe_bangunan', $info->tipe_bangunan) }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-4 border-t border-slate-200 flex justify-end">
                                <button type="submit" name="target_tab" value="cashflow"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-md transition ease-in-out duration-150">
                                    <span>💾 Save Information & Generate Cashflow</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Tab 2: Cost & Beban --}}
                        <div x-show="activeTab === 'cost_beban'" x-cloak>
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Cost Structure & Other Expenses</h3>
                            
                            {{-- Cost Mitra (Fixed) --}}
                            <div class="mb-6 p-4 border border-slate-200 rounded-lg bg-slate-50">
                                <h4 class="font-medium text-slate-900 mb-2">Main Cost</h4>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Implementing Partner Cost (Exclude VAT)</label>
                                    <input type="number" name="cost_structure[biaya_mitra_pelaksana]" value="{{ old('cost_structure.biaya_mitra_pelaksana', $cost->biaya_mitra_pelaksana ? round($cost->biaya_mitra_pelaksana) : '') }}"
                                           class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <p class="text-xs text-slate-500 mt-1">This value is used as the basis for automatic Loan calculation.</p>
                                </div>
                            </div>

                            {{-- Beban Lainnya (Dynamic) --}}
                            <div x-data="{ rows: {{ json_encode(old('beban', $project->beban->map(fn($b) => ['name' => $b->name, 'amount' => round($b->amount)])->toArray() ?: [['name' => '', 'amount' => '']])) }} }">
                                <h4 class="font-medium text-slate-900 mb-2">Other Expenses (Dynamic)</h4>
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="flex gap-2 mb-2 items-start">
                                        <div class="w-1/2">
                                            <input type="text" x-model="row.name" :name="'beban['+index+'][name]'" placeholder="Expense Name (e.g., Supervision Cost)"
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
                                    + Add Expense
                                </button>
                            </div>
                        </div>

                        {{-- Tab 4: Revenue --}}
                        <div x-show="activeTab === 'revenue'" x-cloak x-data="{ mode: '{{ $info->total_revenue && count($project->revenues) === 0 ? 'total' : 'rincian' }}' }">
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Revenue Stream / Cash In</h3>
                            
                            <div class="flex gap-4 mb-4 border-b pb-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="revenue_mode" x-model="mode" value="total" class="text-primary-600 border-slate-300 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700">Direct Total Input</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="revenue_mode" x-model="mode" value="rincian" class="text-primary-600 border-slate-300 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700">Detail Input</span>
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
                                            <input type="text" x-model="row.name" :name="'revenues['+index+'][name]'" placeholder="Revenue Name (e.g., Construction Services)"
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
                                    + Add Detail
                                </button>
                                <p class="text-xs text-slate-500 mt-2">If details are filled, the total revenue will be automatically summed from these details.</p>
                            </div>
                        </div>

                        {{-- Tab 5: Jaminan --}}
                        <div x-show="activeTab === 'jaminan'" x-cloak>
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Guarantees</h3>
                            <div x-data="{ rows: {{ json_encode(old('jaminan', $project->jaminan->map(fn($j) => ['name' => $j->name, 'percentage' => $j->percentage ? round($j->percentage, 2) : '', 'amount' => $j->amount ? round($j->amount) : ''])->toArray() ?: [
                                ['name' => 'Advance Payment Guarantee', 'percentage' => '', 'amount' => ''],
                                ['name' => 'Bid Bond', 'percentage' => '', 'amount' => ''],
                                ['name' => 'Performance Bond', 'percentage' => '', 'amount' => ''],
                                ['name' => 'Maintenance Bond', 'percentage' => '', 'amount' => '']
                            ])) }} }">
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-2 mb-2 items-start p-3 bg-slate-50 rounded-lg border">
                                        <div class="md:col-span-4">
                                            <label class="block text-xs text-slate-500 mb-1">Guarantee Name</label>
                                            <input type="text" x-model="row.name" :name="'jaminan['+index+'][name]'" placeholder="Nama"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="md:col-span-3">
                                            <label class="block text-xs text-slate-500 mb-1">Percentage (%)</label>
                                            <input type="number" step="0.01" x-model="row.percentage" :name="'jaminan['+index+'][percentage]'" placeholder="%"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="md:col-span-4">
                                            <label class="block text-xs text-slate-500 mb-1">Value (empty = calculate from %)</label>
                                            <input type="number" x-model="row.amount" :name="'jaminan['+index+'][amount]'" placeholder="Nominal Override"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="md:col-span-1 flex items-end justify-center h-full pb-1">
                                            <button type="button" @click="rows.splice(index, 1)" class="p-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200">✕</button>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" @click="rows.push({name: '', percentage: '', amount: ''})" class="mt-2 text-sm text-primary-600 hover:text-primary-800 font-medium">
                                    + Add Guarantee
                                </button>
                            </div>
                        </div>

                        {{-- Tab 6: Pajak --}}
                        <div x-show="activeTab === 'pajak'" x-cloak>
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Taxation</h3>
                            <p class="text-sm text-slate-500 mb-4">Use names containing "PPh", "Output VAT", or "Input VAT" to activate automatic calculations from Global Settings.</p>
                            <div x-data="{ rows: {{ json_encode(old('pajak', $project->pajak->map(fn($p) => ['name' => $p->name, 'amount' => $p->amount ? round($p->amount) : ''])->toArray() ?: [
                                ['name' => 'Income Tax Article 23', 'amount' => ''],
                                ['name' => 'Output VAT', 'amount' => ''],
                                ['name' => 'Input VAT', 'amount' => ''],
                                ['name' => 'VAT Credit', 'amount' => '']
                            ])) }} }">
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="flex gap-2 mb-2 items-start">
                                        <div class="w-1/2">
                                            <input type="text" x-model="row.name" :name="'pajak['+index+'][name]'" placeholder="Tax Name"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="w-1/2 flex gap-2">
                                            <input type="number" x-model="row.amount" :name="'pajak['+index+'][amount]'" placeholder="Nominal Override (empty=auto)"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <button type="button" @click="rows.splice(index, 1)" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200">✕</button>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" @click="rows.push({name: '', amount: ''})" class="mt-2 text-sm text-primary-600 hover:text-primary-800 font-medium">
                                    + Add Tax
                                </button>
                            </div>
                        </div>

                        {{-- Tab 6: Pinjaman --}}
                        <div x-show="activeTab === 'pinjaman'" x-cloak>
                            <h3 class="text-lg font-semibold mb-4 text-slate-800">Pinjaman</h3>
                            
                            <div class="mb-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Project Specific Loan Rate (%)</label>
                                <input type="number" step="0.01" name="information[loan_rate]" value="{{ old('information.loan_rate', $info->loan_rate ? round($info->loan_rate, 2) : '') }}"
                                       placeholder="Example: 1.65"
                                       class="w-full md:w-1/2 rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <p class="text-xs text-slate-500 mt-1">Used to calculate "Loan Amount" from Revenue / Project Value. Empty = auto use default 1.65%.</p>
                                
                                <label class="block text-sm font-medium text-slate-700 mb-1 mt-4">Loan Period (Months)</label>
                                <input type="number" step="1" name="assumption[periode_pinjaman]" value="{{ old('assumption.periode_pinjaman', $asumsi->periode_pinjaman ?? '') }}"
                                       placeholder="Example: 12"
                                       class="w-full md:w-1/2 rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <p class="text-xs text-slate-500 mt-1">Loan duration in months.</p>
                            </div>

                            <p class="text-sm text-slate-500 mb-4">Use names "Loan Amount", "Provision Fee", or "Loan Interest" to activate automatic calculations from Global Settings.</p>
                            <div x-data="{ rows: {{ json_encode(old('pinjaman', $project->pinjaman->map(fn($p) => ['name' => $p->name, 'amount' => $p->amount ? round($p->amount) : ''])->toArray() ?: [
                                ['name' => 'Loan Amount', 'amount' => ''],
                                ['name' => 'Provision Fee', 'amount' => ''],
                                ['name' => 'Loan Interest (per month)', 'amount' => '']
                            ])) }} }">
                                <template x-for="(row, index) in rows" :key="index">
                                    <div class="flex gap-2 mb-2 items-start">
                                        <div class="w-1/2">
                                            <input type="text" x-model="row.name" :name="'pinjaman['+index+'][name]'" placeholder="Loan Component Name"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        </div>
                                        <div class="w-1/2 flex gap-2">
                                            <input type="number" x-model="row.amount" :name="'pinjaman['+index+'][amount]'" placeholder="Nominal Override (empty=auto)"
                                                   class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                            <button type="button" @click="rows.splice(index, 1)" class="px-3 py-2 bg-red-100 text-red-600 rounded-md hover:bg-red-200">✕</button>
                                        </div>
                                    </div>
                                </template>
                                 <button type="button" @click="rows.push({name: '', amount: ''})" class="mt-2 text-sm text-primary-600 hover:text-primary-800 font-medium">
                                     + Add Loan Component
                                 </button>
                             </div>
                         </div>

                        {{-- Tab 7: Cashflow Project --}}
                        <div x-show="activeTab === 'cashflow'" x-cloak>
                            <style>
                                .table-cashflow-edit { border-spacing: 0; border-collapse: separate; }
                                .table-cashflow-edit th:nth-child(1), .table-cashflow-edit td:not([colspan]):nth-child(1) { 
                                    position: sticky; left: 0; z-index: 10; background-color: white; border-right: 1px solid #e2e8f0; 
                                }
                                .table-cashflow-edit th:nth-child(2), .table-cashflow-edit td:not([colspan]):nth-child(2) { 
                                    position: sticky; left: 220px; z-index: 10; background-color: #f1f5f9; border-right: 2px solid #cbd5e1; 
                                }
                                .table-cashflow-edit thead th:nth-child(1) { z-index: 20; background-color: #f1f5f9; color: #334155; }
                                .table-cashflow-edit thead th:nth-child(2) { z-index: 20; background-color: #334155; color: white; }
                                
                                /* Explicit background colors for different row states */
                                .table-cashflow-edit tr.bg-yellow-50 td:not([colspan]):nth-child(1) { background-color: #fefce8; }
                                .table-cashflow-edit tr.bg-yellow-50 td:not([colspan]):nth-child(2) { background-color: #fef9c3; }
                                
                                .table-cashflow-edit tr.bg-yellow-50\/50 td:not([colspan]):nth-child(1) { background-color: #fcfcf5; }
                                .table-cashflow-edit tr.bg-yellow-50\/50 td:not([colspan]):nth-child(2) { background-color: #fef9c3; }
                                
                                .table-cashflow-edit tr.bg-slate-200 td:not([colspan]):nth-child(1) { background-color: #e2e8f0; }
                                .table-cashflow-edit tr.bg-slate-200 td:not([colspan]):nth-child(2) { background-color: #e2e8f0; }
                                
                                .table-cashflow-edit tr.bg-slate-100 td:not([colspan]):nth-child(1) { background-color: #f1f5f9; }
                                .table-cashflow-edit tr.bg-slate-100 td:not([colspan]):nth-child(2) { background-color: #f1f5f9; }
                            </style>

                            <h3 class="text-lg font-semibold mb-2 text-slate-800">Distribution % TOP Monthly Cashflow</h3>
                            <p class="text-xs text-slate-500 mb-4">Fill in the Term of Payment percentage (% TOP) of Customer and Partner per month. The system will calculate Cash In, Cash Out, and Project Feasibility automatically.</p>
                            
                            <div class="flex justify-end mb-3">
                                <button type="button"
                                        onclick="if(confirm('Are you sure you want to clear all cashflow data? All % TOP distributions and manual nominals will be reset. Data will be recalculated from the beginning.')) { document.getElementById('reset-cashflow-form').submit(); }"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 text-xs font-semibold rounded-lg border border-red-200 hover:bg-red-100 hover:border-red-300 transition duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Clear Cashflow
                                </button>
                            </div>
                            
                            @if(count($project->cashflows) > 0)
                                <div class="overflow-x-auto border border-slate-200 rounded-lg shadow-sm relative">
                                    <table class="table-cashflow-edit min-w-full divide-y divide-slate-200 text-xs relative">
                                        <thead class="bg-slate-100 font-bold text-slate-700">
                                            <tr>
                                                <th class="px-3 py-2 text-left w-[220px] min-w-[220px] max-w-[220px]">Item / Month</th>
                                                <th class="px-3 py-2 text-right w-[130px] min-w-[130px] max-w-[130px] bg-slate-700 text-white font-bold">Total</th>
                                                @foreach($project->cashflows as $cf)
                                                    <th class="px-3 py-2 text-center min-w-[120px] w-[120px]">
                                                        Month {{ $cf->month_index }}<br>
                                                        <span class="text-[10px] font-normal text-slate-500">
                                                            {{ $cf->month_date ? \Carbon\Carbon::parse($cf->month_date)->format('M Y') : '-' }}
                                                        </span>
                                                    </th>
                                                @endforeach
                                            </tr>
                                            <tbody class="divide-y divide-slate-200 bg-white">
                                             {{-- SECTION 1: TOP (progress) --}}
                                             <tr class="bg-red-800 text-white font-bold text-xs uppercase">
                                                 <td class="p-0 bg-red-800" colspan="{{ count($project->cashflows) + 2 }}">
                                                     <div class="px-3 py-2 sticky left-0 w-max">TOP (progress)</div>
                                                 </td>
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-2 font-semibold text-slate-800">% WORK PROGRESS</td>
                                                 <td class="px-3 py-2 text-right font-bold bg-slate-100">{{ number_format($project->cashflows->max('pct_progress'), 1, '.', '') }}%</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="text"
                                                                value="{{ number_format(old('cashflows.'.$cf->month_index.'.pct_progress', $cf->pct_progress), 1, '.', '') }}%"
                                                                class="w-full text-center text-xs rounded border-slate-200 py-1 bg-slate-100 text-slate-500 cursor-not-allowed" readonly title="Automatically calculated based on duration">
                                                     </td>
                                                 @endforeach
                                             </tr>
                                             <tr class="bg-yellow-50">
                                                 <td class="px-3 py-2 font-semibold text-slate-900">% TERM OF PAYMENT CUSTOMER</td>
                                                 <td class="px-3 py-2 text-right font-bold text-slate-900 bg-yellow-100">{{ number_format($project->cashflows->sum('pct_top_pelanggan'), 1, '.', '') }}%</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="number" step="0.01" min="0" max="100"
                                                                name="cashflows[{{ $cf->month_index }}][pct_top_pelanggan]"
                                                                value="{{ old('cashflows.'.$cf->month_index.'.pct_top_pelanggan', $cf->pct_top_pelanggan) }}"
                                                                class="w-full text-center text-xs rounded border-yellow-300 focus:ring-yellow-500 py-1 font-semibold text-slate-900 bg-yellow-50/50">
                                                     </td>
                                                 @endforeach
                                             </tr>
                                             <tr class="bg-yellow-50/50">
                                                 <td class="px-3 py-2 font-semibold text-slate-900">% TERM OF PAYMENT TO PARTNER</td>
                                                 <td class="px-3 py-2 text-right font-bold text-slate-900 bg-yellow-100">{{ number_format($project->cashflows->sum('pct_top_mitra'), 1, '.', '') }}%</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="number" step="0.01" min="0" max="100"
                                                                name="cashflows[{{ $cf->month_index }}][pct_top_mitra]"
                                                                value="{{ old('cashflows.'.$cf->month_index.'.pct_top_mitra', $cf->pct_top_mitra) }}"
                                                                class="w-full text-center text-xs rounded border-yellow-300 focus:ring-yellow-500 py-1 font-semibold text-slate-900">
                                                     </td>
                                                 @endforeach
                                             </tr>

                                             {{-- SECTION 2: REVENUE STREAM / CASH IN --}}
                                             <tr class="bg-slate-200 font-bold border-t-2 border-slate-400">
                                                 <td class="px-3 py-2 text-slate-900 !bg-slate-200">REVENUE STREAM / CASH IN</td>
                                                 <td class="px-3 py-2 text-right text-slate-900 font-black">Rp {{ number_format($project->cashflows->sum('cash_in'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-2 text-right text-slate-900 font-bold">Rp {{ number_format($cf->cash_in, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-700 pl-6">Construction Implementation Services</td>
                                                 <td class="px-3 py-1.5 text-right font-bold bg-slate-50">Rp {{ number_format($project->cashflows->sum('jasa_konstruksi'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="number" step="1"
                                                                name="cashflows[{{ $cf->month_index }}][jasa_konstruksi]"
                                                                value="{{ old('cashflows.'.$cf->month_index.'.jasa_konstruksi', $cf->jasa_konstruksi ? round($cf->jasa_konstruksi) : '') }}"
                                                                placeholder="0"
                                                                class="w-full text-right text-xs rounded border-slate-300 py-1">
                                                     </td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-700 pl-6">GSD Management Fee</td>
                                                 <td class="px-3 py-1.5 text-right font-bold bg-slate-50">Rp {{ number_format($project->cashflows->sum('management_fee'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="number" step="1"
                                                                name="cashflows[{{ $cf->month_index }}][management_fee]"
                                                                value="{{ old('cashflows.'.$cf->month_index.'.management_fee', $cf->management_fee ? round($cf->management_fee) : '') }}"
                                                                placeholder="0"
                                                                class="w-full text-right text-xs rounded border-slate-300 py-1">
                                                     </td>
                                                 @endforeach
                                             </tr>

                                             {{-- SECTION 3: COST STRUCTURE / CASH OUT --}}
                                             <tr class="bg-slate-200 font-bold border-t-2 border-slate-400">
                                                 <td class="px-3 py-2 text-slate-900 !bg-slate-200">COST STRUCTURE / CASH OUT</td>
                                                 <td class="px-3 py-2 text-right text-slate-900 font-black">Rp {{ number_format($project->cashflows->sum('cash_out'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-2 text-right text-slate-900 font-bold">Rp {{ number_format($cf->cash_out, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-700 pl-6">Implementing Partner Cost (Exclude VAT)</td>
                                                 <td class="px-3 py-1.5 text-right font-bold bg-slate-50">Rp {{ number_format($project->cashflows->sum('biaya_mitra'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="number" step="1"
                                                                name="cashflows[{{ $cf->month_index }}][biaya_mitra]"
                                                                value="{{ old('cashflows.'.$cf->month_index.'.biaya_mitra', $cf->biaya_mitra ? round($cf->biaya_mitra) : '') }}"
                                                                placeholder="0"
                                                                class="w-full text-right text-xs rounded border-slate-300 py-1">
                                                     </td>
                                                 @endforeach
                                             </tr>

                                             {{-- SECTION 4: OTHER EXPENSES --}}
                                             <tr class="bg-slate-100 font-bold text-slate-800 uppercase">
                                                 <td class="p-0 bg-slate-100" colspan="{{ count($project->cashflows) + 2 }}">
                                                     <div class="px-3 py-2 sticky left-0 w-max">OTHER EXPENSES</div>
                                                 </td>
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Guarantee Facility Fee</td>
                                                 <td class="px-3 py-1.5 text-right font-semibold bg-slate-50">Rp {{ number_format($project->cashflows->sum('fee_jaminan'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="number" step="1"
                                                                name="cashflows[{{ $cf->month_index }}][fee_jaminan]"
                                                                value="{{ old('cashflows.'.$cf->month_index.'.fee_jaminan', $cf->fee_jaminan ? round($cf->fee_jaminan) : '') }}"
                                                                placeholder="0"
                                                                class="w-full text-right text-xs rounded border-slate-300 py-1">
                                                     </td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Guarantee Facility Admin</td>
                                                 <td class="px-3 py-1.5 text-right font-semibold bg-slate-50">Rp {{ number_format($project->cashflows->sum('admin_jaminan'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="number" step="1"
                                                                name="cashflows[{{ $cf->month_index }}][admin_jaminan]"
                                                                value="{{ old('cashflows.'.$cf->month_index.'.admin_jaminan', $cf->admin_jaminan ? round($cf->admin_jaminan) : '') }}"
                                                                placeholder="0"
                                                                class="w-full text-right text-xs rounded border-slate-300 py-1">
                                                     </td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Construction Assurance Risk (CAR)</td>
                                                 <td class="px-3 py-1.5 text-right font-semibold bg-slate-50">Rp {{ number_format($project->cashflows->sum('car'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="number" step="1"
                                                                name="cashflows[{{ $cf->month_index }}][car]"
                                                                value="{{ old('cashflows.'.$cf->month_index.'.car', $cf->car ? round($cf->car) : '') }}"
                                                                placeholder="0"
                                                                class="w-full text-right text-xs rounded border-slate-300 py-1">
                                                     </td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Construction Service Contribution</td>
                                                 <td class="px-3 py-1.5 text-right font-semibold bg-slate-50">Rp {{ number_format($project->cashflows->sum('iuran_jasa'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="number" step="1"
                                                                name="cashflows[{{ $cf->month_index }}][iuran_jasa]"
                                                                value="{{ old('cashflows.'.$cf->month_index.'.iuran_jasa', $cf->iuran_jasa ? round($cf->iuran_jasa) : '') }}"
                                                                placeholder="0"
                                                                class="w-full text-right text-xs rounded border-slate-300 py-1">
                                                     </td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Supervision Cost</td>
                                                 <td class="px-3 py-1.5 text-right font-semibold bg-slate-50">Rp {{ number_format($project->cashflows->sum('biaya_pengawasan'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="number" step="1"
                                                                name="cashflows[{{ $cf->month_index }}][biaya_pengawasan]"
                                                                value="{{ old('cashflows.'.$cf->month_index.'.biaya_pengawasan', $cf->biaya_pengawasan ? round($cf->biaya_pengawasan) : '') }}"
                                                                placeholder="0"
                                                                class="w-full text-right text-xs rounded border-slate-300 py-1">
                                                     </td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Project BOP</td>
                                                 <td class="px-3 py-1.5 text-right font-semibold bg-slate-50">Rp {{ number_format($project->cashflows->sum('bop_project'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-2 py-1">
                                                         <input type="number" step="1"
                                                                name="cashflows[{{ $cf->month_index }}][bop_project]"
                                                                value="{{ old('cashflows.'.$cf->month_index.'.bop_project', $cf->bop_project ? round($cf->bop_project) : '') }}"
                                                                placeholder="0"
                                                                class="w-full text-right text-xs rounded border-slate-300 py-1">
                                                     </td>
                                                 @endforeach
                                             </tr>


                                             <tr class="bg-slate-800 text-white font-bold">
                                                 <td class="px-3 py-2 !bg-slate-800">GROSS MARGIN / EBIT</td>
                                                 <td class="px-3 py-2 text-right font-black !bg-slate-800 text-white">Rp {{ number_format($project->cashflows->sum('gross_margin'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-2 text-right font-bold">Rp {{ number_format($cf->gross_margin, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Income Tax Article 23 Deduction</td>
                                                 <td class="px-3 py-1.5 text-right font-bold bg-slate-100">Rp {{ number_format($project->cashflows->sum('pph'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->pph, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr class="bg-slate-100 font-semibold">
                                                 <td class="p-0 bg-slate-100" colspan="{{ count($project->cashflows) + 2 }}">
                                                     <div class="px-3 py-1.5 sticky left-0 w-max">VAT Payable</div>
                                                 </td>
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Output VAT</td>
                                                 <td class="px-3 py-1.5 text-right font-bold bg-slate-100">Rp {{ number_format($project->cashflows->sum('ppn_keluaran'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->ppn_keluaran, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Input VAT</td>
                                                 <td class="px-3 py-1.5 text-right font-bold bg-slate-100">Rp {{ number_format($project->cashflows->sum('ppn_masukan'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->ppn_masukan, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr class="bg-purple-100">
                                                 <td class="px-3 py-1.5 text-purple-900 pl-6 !bg-purple-100 font-semibold">VAT Credit</td>
                                                 <td class="px-3 py-1.5 text-right font-bold text-purple-950 !bg-purple-100">Rp {{ number_format($project->cashflows->sum('kredit_ppn'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-1.5 text-right font-bold text-purple-950">Rp {{ number_format($cf->kredit_ppn, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr class="bg-emerald-100 font-bold border-t border-b border-emerald-300">
                                                 <td class="px-3 py-2 text-emerald-950 !bg-emerald-100">GROSS MARGIN + INCOME TAX</td>
                                                 <td class="px-3 py-2 text-right text-emerald-950 font-black !bg-emerald-100">Rp {{ number_format($project->cashflows->sum('gross_margin') - $project->cashflows->sum('pph'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-2 text-right text-emerald-950 font-bold">Rp {{ number_format($cf->gross_margin - $cf->pph, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Loan Drawdown</td>
                                                 <td class="px-3 py-1.5 text-right font-bold bg-slate-100">Rp {{ number_format($project->cashflows->sum('penarikan_pinjaman'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->penarikan_pinjaman, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Principal Payment</td>
                                                 <td class="px-3 py-1.5 text-right font-bold bg-slate-100">Rp {{ number_format($project->cashflows->sum('pembayaran_pokok'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->pembayaran_pokok, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Provision Fee</td>
                                                 <td class="px-3 py-1.5 text-right font-bold bg-slate-100">Rp {{ number_format($project->cashflows->sum('biaya_provisi'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->biaya_provisi, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr>
                                                 <td class="px-3 py-1.5 text-slate-600 pl-6">Interest Expense</td>
                                                 <td class="px-3 py-1.5 text-right font-bold bg-slate-100">Rp {{ number_format($project->cashflows->sum('beban_bunga'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->beban_bunga, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr class="bg-indigo-100 font-bold border-t-2 border-indigo-200">
                                                 <td class="px-3 py-2 text-indigo-900 !bg-indigo-100">CASH MARGIN</td>
                                                 <td class="px-3 py-2 text-right text-indigo-950 font-black !bg-indigo-100">Rp {{ number_format($project->cashflows->sum('cash_margin'), 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-2 text-right text-indigo-900">Rp {{ number_format($cf->cash_margin, 0, ',', '.') }}</td>
                                                 @endforeach
                                             </tr>
                                             <tr class="bg-slate-900 text-white font-bold">
                                                 <td class="px-3 py-2.5 !bg-slate-900">CUMULATIVE CASH FLOW</td>
                                                 <td class="px-3 py-2.5 text-right font-black text-yellow-300 !bg-slate-900">Rp {{ number_format($project->cashflows->last()->cash_flow_kumulatif ?? 0, 0, ',', '.') }}</td>
                                                 @foreach($project->cashflows as $cf)
                                                     <td class="px-3 py-2.5 text-right {{ $cf->cash_flow_kumulatif < 0 ? 'text-red-400 font-extrabold' : 'text-emerald-400' }}">
                                                         Rp {{ number_format($cf->cash_flow_kumulatif, 0, ',', '.') }}
                                                     </td>
                                                 @endforeach
                                             </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-4">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-primary-700 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-600 focus:bg-primary-600 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                        💾 Save & Recalculate
                    </button>
                </div>
            </form>

            {{-- Hidden form for cashflow reset (outside main form to avoid nesting) --}}
            <form id="reset-cashflow-form" method="POST" action="{{ route('projects.reset-cashflow', $project) }}" class="hidden">
                @csrf
                @method('DELETE')
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
