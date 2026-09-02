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
                @if(auth()->user()->canEdit())
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
                            <td class="px-4 py-2 text-center border-r border-gray-300">0</td>
                            <td class="px-4 py-2 text-center font-semibold">All CF {{ $project->net_cash_flow > 0 ? 'Positif' : 'Negatif' }}</td>
                        </tr>
                        <tr class="bg-green-100 border-y-2 border-gray-800">
                            <td class="px-4 py-3 font-bold text-slate-900 border-r border-gray-800 text-center uppercase" colspan="1">KESIMPULAN</td>
                            <td class="px-4 py-3 text-center font-bold text-slate-900" colspan="2">
                                {{ $project->kelayakan }} dengan Net Income sebesar {{ number_format($project->net_income_percentage, 2, ',', '.') }}%
                            </td>
                        </tr>
                        <tr class="bg-white border-b-2 border-gray-800">
                            <td class="px-4 py-2 font-bold text-slate-900 border-r border-gray-800">REVENUE INCL PPN</td>
                            <td class="px-4 py-2 text-center font-bold border-r border-gray-800">11/12*{{ \App\Models\GlobalSetting::getValue('ppn_rate', 11) }}%</td>
                            <td class="px-4 py-2 text-right font-bold text-slate-900">Rp {{ number_format($project->revenue_incl_ppn, 0, ',', '.') }}</td>
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

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
