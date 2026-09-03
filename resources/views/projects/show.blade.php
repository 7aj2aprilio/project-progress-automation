<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-slate-800 leading-tight">
                    {{ $project->name }}
                </h2>
                <div class="mt-2 flex gap-2 items-center">
                    @php
                        $statusColors = [
                            'draft' => 'bg-amber-100 text-amber-800',
                            'active' => 'bg-emerald-100 text-emerald-800',
                            'completed' => 'bg-sky-100 text-sky-800',
                        ];
                    @endphp
                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$project->status] ?? '' }}">
                        Status: {{ ucfirst($project->status) }}
                    </span>

                    @if($project->kelayakan === 'Layak')
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800">
                            Kelayakan: Layak (Net Income {{ number_format($project->net_income_percentage, 2, ',', '.') }}%)
                        </span>
                    @elseif($project->kelayakan === 'Tidak Layak')
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Kelayakan: Tidak Layak (Net Income {{ number_format($project->net_income_percentage, 2, ',', '.') }}%)
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2">
                @if(auth()->user()?->canEdit())
                    <a href="{{ route('projects.edit', $project) }}"
                       class="inline-flex items-center px-4 py-2 bg-amber-500 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600 transition ease-in-out duration-150 shadow-sm">
                        Edit Proyek
                    </a>
                @endif
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-200 border border-transparent rounded-lg font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-300 transition ease-in-out duration-150">
                    ← Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-300 text-emerald-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Kelayakan Table --}}
            <div class="bg-white shadow-md sm:rounded-lg mb-8 overflow-hidden border-2 border-gray-800">
                <table class="min-w-full divide-y divide-gray-300 text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th colspan="3" class="px-4 py-2 text-center font-bold text-slate-900 uppercase tracking-wider border-b border-gray-800">
                                Kesimpulan Analisis Kelayakan Project
                            </th>
                        </tr>
                        <tr class="bg-white">
                            <th colspan="3" class="px-4 py-2 text-center text-slate-800 border-b border-gray-300">
                                {{ $project->name }}
                            </th>
                        </tr>
                        <tr class="bg-gray-200">
                            <th colspan="3" class="px-4 py-2 text-center text-slate-800 border-b border-gray-800">
                                {{ $project->information->nama_pelanggan ?? '-' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-300">
                        <tr>
                            <td class="px-4 py-2 font-bold border-r border-gray-300 w-1/3">REVENUE</td>
                            <td class="px-4 py-2 border-r border-gray-300 w-1/6"></td>
                            <td class="px-4 py-2 text-right font-bold w-1/2">Rp {{ number_format($project->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 font-bold border-r border-gray-300">TOTAL BEBAN/CASH OUT</td>
                            <td class="px-4 py-2 border-r border-gray-300"></td>
                            <td class="px-4 py-2 text-right font-bold">Rp {{ number_format($project->total_cost, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-blue-100 border-y-2 border-gray-800">
                            <td class="px-4 py-2 font-bold text-slate-900 border-r border-gray-800">GROSS MARGIN</td>
                            <td class="px-4 py-2 text-center font-bold border-r border-gray-800">{{ number_format($project->gross_margin_percentage, 2, ',', '.') }}%</td>
                            <td class="px-4 py-2 text-right font-bold text-slate-900">Rp {{ number_format($project->gross_margin, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-r border-gray-300">Total Pajak PPH</td>
                            <td class="px-4 py-2 border-r border-gray-300"></td>
                            <td class="px-4 py-2 text-right font-semibold">Rp {{ number_format($project->total_pph, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-green-100 border-y-2 border-gray-800">
                            <td class="px-4 py-2 font-bold text-slate-900 border-r border-gray-800">GROSS MARGIN + PPH</td>
                            <td class="px-4 py-2 text-center font-bold border-r border-gray-800">{{ number_format($project->gross_margin_pph_percentage, 2, ',', '.') }}%</td>
                            <td class="px-4 py-2 text-right font-bold text-slate-900">Rp {{ number_format($project->gross_margin_pph, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-r border-gray-300">Provisi</td>
                            <td class="px-4 py-2 border-r border-gray-300"></td>
                            <td class="px-4 py-2 text-right font-semibold">Rp {{ number_format($project->provisi, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-r border-gray-300">Bunga Pinjaman</td>
                            <td class="px-4 py-2 border-r border-gray-300"></td>
                            <td class="px-4 py-2 text-right font-semibold">Rp {{ number_format($project->bunga_pinjaman, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-blue-200 border-y-2 border-gray-800">
                            <td class="px-4 py-2 font-bold text-slate-900 border-r border-gray-800">NET INCOME</td>
                            <td class="px-4 py-2 text-center font-bold border-r border-gray-800">{{ number_format($project->net_income_percentage, 2, ',', '.') }}%</td>
                            <td class="px-4 py-2 text-right font-bold text-slate-900">Rp {{ number_format($project->net_income, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-r border-gray-300">Kredit PPN</td>
                            <td class="px-4 py-2 border-r border-gray-300"></td>
                            <td class="px-4 py-2 text-right font-semibold">Rp {{ number_format($project->kredit_ppn, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-blue-100 border-y-2 border-gray-800">
                            <td class="px-4 py-2 font-bold text-slate-900 border-r border-gray-800">NET CASH FLOW</td>
                            <td class="px-4 py-2 text-center font-bold border-r border-gray-800">{{ $project->net_cash_flow > 0 ? 'Cashflow Positif' : 'Cashflow Negatif' }}</td>
                            <td class="px-4 py-2 text-right font-bold text-slate-900">Rp {{ number_format($project->net_cash_flow, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 border-r border-gray-300">CF selama kontrak</td>
                            <td class="px-4 py-2 text-center border-r border-gray-300">{{ $project->has_cf_negatif ? 1 : 0 }}</td>
                            <td class="px-4 py-2 text-center font-semibold {{ $project->has_cf_negatif ? 'text-red-700 font-bold' : 'text-emerald-700' }}">
                                {{ $project->has_cf_negatif ? 'Terdapat CF Negatif' : 'All CF Positif' }}
                            </td>
                        </tr>
                        <tr class="{{ $project->kelayakan === 'Layak' ? 'bg-emerald-100' : 'bg-red-100' }} border-y-2 border-gray-800">
                            <td class="px-4 py-3 font-bold text-slate-900 border-r border-gray-800 text-center uppercase" colspan="1">KESIMPULAN</td>
                            <td class="px-4 py-3 text-center font-bold {{ $project->kelayakan === 'Layak' ? 'text-emerald-900' : 'text-red-900' }}" colspan="2">
                                {{ $project->kesimpulan_kelayakan_detail }}
                            </td>
                        </tr>
                        <tr class="bg-white border-b-2 border-gray-800">
                            <td class="px-4 py-2 font-bold text-slate-900 border-r border-gray-800">REVENUE INCL PPN</td>
                            <td class="px-4 py-2 text-center font-bold border-r border-gray-800">11/12*{{ \App\Models\GlobalSetting::getValue('ppn_rate', 12) }}%</td>
                            <td class="px-4 py-2 text-right font-bold text-slate-900">Rp {{ number_format($project->revenue_incl_ppn, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-gray-100 font-bold border-t-2 border-gray-800">
                            <td colspan="3" class="px-4 py-2 text-slate-900 font-bold">Notes lain-lain:</td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-4 py-1.5 text-slate-700 pl-8" colspan="2">Biaya Mitra Pelaksana (Exclude PPN)</td>
                            <td class="px-4 py-1.5 text-right font-bold text-slate-900">Rp {{ number_format($project->cashflows()->sum('biaya_mitra'), 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-4 py-1.5 text-slate-700 pl-8" colspan="2">BOP Project</td>
                            <td class="px-4 py-1.5 text-right font-bold text-slate-900">Rp {{ number_format($project->cashflows()->sum('bop_project'), 0, ',', '.') }}</td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-4 py-1.5 text-slate-700 pl-8" colspan="2">Management Fee GSD</td>
                            <td class="px-4 py-1.5 text-right font-bold text-slate-900">Rp {{ number_format($project->cashflows()->sum('management_fee'), 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Tab Navigation --}}
            <div x-data="{ activeTab: 'informasi' }" class="bg-white shadow-sm rounded-xl border border-slate-200">
                <div class="border-b border-slate-200 overflow-x-auto overflow-y-hidden">
                    <nav class="flex -mb-px whitespace-nowrap">
                        @php
                            $tabs = [
                                'informasi' => '1. Informasi',
                                'cost_beban' => '2. Cost & Beban',
                                'revenue' => '3. Revenue',
                                'jaminan' => '4. Jaminan',
                                'pajak' => '5. Pajak',
                                'pinjaman' => '6. Pinjaman',
                                'cashflow' => '7. Cashflow Project',
                            ];
                        @endphp
                        @foreach($tabs as $key => $label)
                            <button @click="activeTab = '{{ $key }}'"
                                    :class="activeTab === '{{ $key }}' ? 'border-primary-500 text-primary-700' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                                    class="px-4 py-4 text-sm font-medium border-b-2 transition-colors">
                                {{ $label }}
                            </button>
                        @endforeach
                    </nav>
                </div>

                {{-- Tab Content --}}
                <div class="p-6">
                    @php $info = $project->information; @endphp
                    @php $cost = $project->costStructure; @endphp

                    {{-- Tab 1: Informasi Project --}}
                    <div x-show="activeTab === 'informasi'" x-cloak>
                        <h3 class="text-lg font-semibold mb-4 text-slate-800">Informasi Project</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach([
                                'Nama Pelanggan' => $info->nama_pelanggan ?? '-',
                                'Kategori Project' => $info->kategori_project ?? '-',
                                'Nama Project' => $info->nama_project ?? '-',
                                'Lokasi Project' => $info->lokasi_project ?? '-',
                                'Estimasi Mulai' => $info->estimasi_mulai ? \Carbon\Carbon::parse($info->estimasi_mulai)->format('d F Y') : '-',
                                'Estimasi Selesai' => $info->estimasi_selesai ? \Carbon\Carbon::parse($info->estimasi_selesai)->format('d F Y') : '-',
                                'Durasi Retensi' => ($info->durasi_retensi ?? '-') . ' bulan',
                                'Pengawasan Konstruksi' => $info->pengawasan_konstruksi ?? '-',
                                'Tipe Bangunan' => $info->tipe_bangunan ?? '-',
                                'TOP Pembayaran Mitra' => $info->top_pembayaran_mitra ?? '-',
                                'TOP Pembayaran Pelanggan' => $info->top_pembayaran_pelanggan ?? '-',
                            ] as $label => $value)
                                <div class="bg-slate-50 rounded-lg p-3">
                                    <span class="text-xs font-medium text-slate-500 uppercase">{{ $label }}</span>
                                    <p class="text-sm text-slate-900 mt-1">{{ $value }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tab 2: Cost & Beban --}}
                    <div x-show="activeTab === 'cost_beban'" x-cloak>
                        <h3 class="text-lg font-semibold mb-4 text-slate-800">Cost Structure & Beban Lainnya</h3>
                        <div class="space-y-4">
                            <div class="bg-slate-50 rounded-lg p-4 border">
                                <span class="text-xs font-medium text-slate-500 uppercase">Cost Utama (Biaya Mitra Pelaksana)</span>
                                <p class="text-lg text-slate-900 mt-1 font-semibold">Rp {{ number_format($cost->biaya_mitra_pelaksana ?? 0, 0, ',', '.') }}</p>
                            </div>
                            
                            @if(count($project->beban) > 0)
                                <h4 class="font-medium text-slate-700 mt-6 mb-2">Rincian Beban Lainnya</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($project->beban as $b)
                                        <div class="bg-slate-50 rounded-lg p-3 border-l-4 border-slate-300">
                                            <span class="text-xs font-medium text-slate-500 uppercase">{{ $b->name }}</span>
                                            <p class="text-sm text-slate-900 mt-1">Rp {{ number_format($b->amount ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-slate-500 italic">Tidak ada beban lainnya.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Tab 4: Revenue --}}
                    <div x-show="activeTab === 'revenue'" x-cloak>
                        <h3 class="text-lg font-semibold mb-4 text-slate-800">Revenue Stream / Cash In</h3>
                        <div class="space-y-4">
                            <div class="bg-primary-50 rounded-lg p-4 border border-primary-200">
                                <span class="text-xs font-medium text-primary-600 uppercase">Total Revenue</span>
                                <p class="text-xl text-primary-800 mt-1 font-bold">Rp {{ number_format($project->total_revenue, 0, ',', '.') }}</p>
                            </div>

                            @if(count($project->revenues) > 0)
                                <h4 class="font-medium text-slate-700 mt-6 mb-2">Rincian Revenue</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($project->revenues as $r)
                                        <div class="bg-slate-50 rounded-lg p-3 border-l-4 border-primary-400">
                                            <span class="text-xs font-medium text-slate-500 uppercase">{{ $r->name }}</span>
                                            <p class="text-sm text-slate-900 mt-1">Rp {{ number_format($r->amount ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tab 5: Jaminan --}}
                    <div x-show="activeTab === 'jaminan'" x-cloak>
                        <h3 class="text-lg font-semibold mb-4 text-slate-800">Jaminan-Jaminan</h3>
                        @if(count($project->jaminan) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($project->jaminan as $j)
                                    <div class="bg-slate-50 rounded-lg p-3 border">
                                        <span class="text-xs font-medium text-slate-500 uppercase">{{ $j->name }}</span>
                                        <p class="text-sm text-slate-900 mt-1 flex items-center justify-between">
                                            <span>
                                                @if($j->percentage) {{ round($j->percentage, 2) }}% → @endif
                                                Rp {{ number_format($j->amount ?? 0, 0, ',', '.') }}
                                            </span>
                                            <span class="text-xs px-2 py-1 rounded {{ $j->is_manual ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                                                {{ $j->is_manual ? 'Manual' : 'Auto' }}
                                            </span>
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-500 italic">Tidak ada jaminan yang diinput.</p>
                        @endif
                    </div>

                    {{-- Tab 6: Pajak --}}
                    <div x-show="activeTab === 'pajak'" x-cloak>
                        <h3 class="text-lg font-semibold mb-4 text-slate-800">Perpajakan</h3>
                        @if(count($project->pajak) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($project->pajak as $p)
                                    <div class="bg-slate-50 rounded-lg p-3 border">
                                        <span class="text-xs font-medium text-slate-500 uppercase">{{ $p->name }}</span>
                                        <p class="text-sm text-slate-900 mt-1 flex items-center justify-between">
                                            <span>Rp {{ number_format($p->amount ?? 0, 0, ',', '.') }}</span>
                                            <span class="text-xs px-2 py-1 rounded {{ $p->is_manual ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                                                {{ $p->is_manual ? 'Manual' : 'Auto' }}
                                            </span>
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-500 italic">Tidak ada pajak yang diinput.</p>
                        @endif
                    </div>

                    {{-- Tab 6: Pinjaman --}}
                    <div x-show="activeTab === 'pinjaman'" x-cloak>
                        <h3 class="text-lg font-semibold mb-4 text-slate-800">Pinjaman</h3>
                        <div class="mb-6 bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <span class="text-xs font-medium text-slate-500 uppercase">Rate Besar Pinjaman Khusus Proyek</span>
                            <p class="text-lg text-slate-900 mt-1 font-semibold">{{ $info->loan_rate ? round($info->loan_rate, 2) : '1.65 (default)' }}%</p>
                        </div>
                        @if(count($project->pinjaman) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($project->pinjaman as $p)
                                    <div class="bg-slate-50 rounded-lg p-3 border">
                                        <span class="text-xs font-medium text-slate-500 uppercase">{{ $p->name }}</span>
                                        <p class="text-sm text-slate-900 mt-1 flex items-center justify-between">
                                            <span>Rp {{ number_format($p->amount ?? 0, 0, ',', '.') }}</span>
                                            <span class="text-xs px-2 py-1 rounded {{ $p->is_manual ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                                                {{ $p->is_manual ? 'Manual' : 'Auto' }}
                                            </span>
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-500 italic">Tidak ada komponen pinjaman yang diinput.</p>
                        @endif
                    </div>

                    {{-- Tab 7: Cashflow Project --}}
                    <div x-show="activeTab === 'cashflow'" x-cloak>
                        <style>
                            .table-cashflow { border-spacing: 0; border-collapse: separate; }
                            .table-cashflow th:nth-child(1), .table-cashflow td:not([colspan]):nth-child(1) { 
                                position: sticky; left: 0; z-index: 10; background-color: white; border-right: 1px solid #e2e8f0; 
                            }
                            .table-cashflow th:nth-child(2), .table-cashflow td:not([colspan]):nth-child(2) { 
                                position: sticky; left: 220px; z-index: 10; background-color: #f1f5f9; border-right: 2px solid #cbd5e1; 
                            }
                            .table-cashflow thead th:nth-child(1) { z-index: 20; background-color: #1e293b; color: white; }
                            .table-cashflow thead th:nth-child(2) { z-index: 20; background-color: #334155; color: white; }
                            
                            /* Explicit background colors for different row states */
                            .table-cashflow tr.bg-yellow-50 td:not([colspan]):nth-child(1) { background-color: #fefce8; }
                            .table-cashflow tr.bg-yellow-50 td:not([colspan]):nth-child(2) { background-color: #fef9c3; }
                            
                            .table-cashflow tr.bg-yellow-50\/50 td:not([colspan]):nth-child(1) { background-color: #fcfcf5; }
                            .table-cashflow tr.bg-yellow-50\/50 td:not([colspan]):nth-child(2) { background-color: #fef9c3; }
                            
                            .table-cashflow tr.bg-slate-200 td:not([colspan]):nth-child(1) { background-color: #e2e8f0; }
                            .table-cashflow tr.bg-slate-200 td:not([colspan]):nth-child(2) { background-color: #e2e8f0; }
                            
                            .table-cashflow tr.bg-slate-100 td:not([colspan]):nth-child(1) { background-color: #f1f5f9; }
                            .table-cashflow tr.bg-slate-100 td:not([colspan]):nth-child(2) { background-color: #f1f5f9; }
                        </style>

                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800">Tabel Cashflow Project Bulanan</h3>
                                <p class="text-xs text-slate-500">Matrik simulasi arus kas bulanan berdasarkan % TOP Pelanggan & % TOP Mitra.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('projects.export-cashflow', $project) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition-colors shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                    Export PDF
                                </a>
                                <span class="px-3 py-1 text-xs font-semibold rounded-lg {{ $project->has_cf_negatif ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                                    {{ $project->has_cf_negatif ? '⚠️ Terdapat CF Negatif' : '✅ All CF Positif' }}
                                </span>
                            </div>
                        </div>

                        @if(count($project->cashflows) > 0)
                            <div class="overflow-x-auto border border-gray-800 rounded-lg shadow-sm relative">
                                <table class="table-cashflow min-w-full divide-y divide-gray-300 text-xs font-mono relative">
                                    <thead class="bg-slate-800 text-white font-sans">
                                        <tr>
                                            <th class="px-3 py-2 text-left w-[220px] min-w-[220px] max-w-[220px]">Jangka Waktu (Bulan)</th>
                                            <th class="px-3 py-2 text-right w-[120px] min-w-[120px] max-w-[120px]">Total</th>
                                            @foreach($project->cashflows as $cf)
                                                <th class="px-3 py-2 text-center min-w-[120px] w-[120px]">
                                                    Bulan {{ $cf->month_index }}<br>
                                                    <span class="text-[10px] font-normal text-slate-300">
                                                        {{ $cf->month_date ? \Carbon\Carbon::parse($cf->month_date)->format('M Y') : '-' }}
                                                    </span>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white font-sans">
                                        {{-- SECTION 1: TOP (progress) --}}
                                        <tr class="bg-red-800 text-white font-bold text-xs uppercase">
                                            <td class="p-0 bg-red-800" colspan="{{ count($project->cashflows) + 2 }}">
                                                <div class="px-3 py-2 sticky left-0 w-max">TOP (progress)</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-2 font-semibold text-slate-800">% PROGRESS PEKERJAAN</td>
                                            <td class="px-3 py-2 text-right font-bold bg-slate-100">{{ number_format($project->cashflows->max('pct_progress'), 1, '.', '') }}%</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-2 text-center">{{ number_format($cf->pct_progress, 1, '.', '') }}%</td>
                                            @endforeach
                                        </tr>
                                        <tr class="bg-yellow-50">
                                            <td class="px-3 py-2 font-semibold text-slate-900">% TERM OF PAYMENT PELANGGAN</td>
                                            <td class="px-3 py-2 text-right font-bold text-slate-900 bg-yellow-100">{{ number_format($project->cashflows->sum('pct_top_pelanggan'), 1, '.', '') }}%</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-2 text-center font-bold text-slate-900">{{ number_format($cf->pct_top_pelanggan, 1, '.', '') }}%</td>
                                            @endforeach
                                        </tr>
                                        <tr class="bg-yellow-50/50">
                                            <td class="px-3 py-2 font-semibold text-slate-900">% TERM OF PAYMENT KEPADA MITRA</td>
                                            <td class="px-3 py-2 text-right font-bold text-slate-900 bg-yellow-100">{{ number_format($project->cashflows->sum('pct_top_mitra'), 1, '.', '') }}%</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-2 text-center font-bold text-slate-900">{{ number_format($cf->pct_top_mitra, 1, '.', '') }}%</td>
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
                                            <td class="px-3 py-1.5 text-slate-700 pl-6">Jasa Pelaksanaan konstruksi</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('jasa_konstruksi'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->jasa_konstruksi, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-700 pl-6">Management Fee GSD</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('management_fee'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->management_fee, 0, ',', '.') }}</td>
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
                                            <td class="px-3 py-1.5 text-slate-700 pl-6">Biaya Mitra Pelaksana (Exclude PPN)</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('biaya_mitra'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->biaya_mitra, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>

                                        {{-- SECTION 4: BEBAN LAINNYA --}}
                                        <tr class="bg-slate-100 font-bold text-slate-800 uppercase">
                                            <td class="p-0" colspan="{{ count($project->cashflows) + 2 }}">
                                                <div class="px-3 py-2 sticky left-0 w-max">BEBAN LAINNYA</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">Fee Fasilitas Jaminan</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('fee_jaminan'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->fee_jaminan, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">Admin Fasilitas Jaminan</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('admin_jaminan'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->admin_jaminan, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">Construction Assurance Risk ( CAR)</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('car'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->car, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">Iuran Jasa Konstruksi</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('iuran_jasa'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->iuran_jasa, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">Biaya Pengawasan</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('biaya_pengawasan'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->biaya_pengawasan, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">BOP Project</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('bop_project'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->bop_project, 0, ',', '.') }}</td>
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
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">Pengurangan pph 23</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('pph'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->pph, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr class="bg-slate-100 font-semibold">
                                            <td class="p-0 bg-slate-100" colspan="{{ count($project->cashflows) + 2 }}">
                                                <div class="px-3 py-1.5 sticky left-0 w-max">PPn yang harus dibayarkan</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">PPn Keluaran</td>
                                            <td class="px-3 py-1.5 text-right">Rp {{ number_format($project->cashflows->sum('ppn_keluaran'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->ppn_keluaran, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">PPn Masukan</td>
                                            <td class="px-3 py-1.5 text-right">Rp {{ number_format($project->cashflows->sum('ppn_masukan'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->ppn_masukan, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr class="bg-purple-100">
                                            <td class="px-3 py-1.5 text-purple-900 pl-6 !bg-purple-100 font-semibold">Kredit PPn</td>
                                            <td class="px-3 py-1.5 text-right font-bold text-purple-950 !bg-purple-100">Rp {{ number_format($project->cashflows->sum('kredit_ppn'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right font-bold text-purple-950">Rp {{ number_format($cf->kredit_ppn, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr class="bg-emerald-100 font-bold border-t border-b border-emerald-300">
                                            <td class="px-3 py-2 text-emerald-950 !bg-emerald-100">GROSS MARGIN + PPH</td>
                                            <td class="px-3 py-2 text-right text-emerald-950 font-black !bg-emerald-100">Rp {{ number_format($project->cashflows->sum('gross_margin') - $project->cashflows->sum('pph'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-2 text-right text-emerald-950 font-bold">Rp {{ number_format($cf->gross_margin - $cf->pph, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">Penarikan Pinjaman</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('penarikan_pinjaman'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->penarikan_pinjaman, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">Pembayaran Pokok</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('pembayaran_pokok'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->pembayaran_pokok, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">Biaya Provisi</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('biaya_provisi'), 0, ',', '.') }}</td>
                                            @foreach($project->cashflows as $cf)
                                                <td class="px-3 py-1.5 text-right">Rp {{ number_format($cf->biaya_provisi, 0, ',', '.') }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-1.5 text-slate-600 pl-6">Beban Bunga</td>
                                            <td class="px-3 py-1.5 text-right font-semibold">Rp {{ number_format($project->cashflows->sum('beban_bunga'), 0, ',', '.') }}</td>
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
                                        <tr class="bg-slate-900 text-white font-bold border-t-2 border-slate-900">
                                            <td class="px-3 py-2.5 !bg-slate-900">CASH FLOW KUMULATIF</td>
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
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-6 text-center">
                                <p class="text-sm text-slate-600">Belum ada data cashflow. Klik <strong>Edit Proyek</strong> untuk mengatur persentase TOP.</p>
                            </div>
                        @endif
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
