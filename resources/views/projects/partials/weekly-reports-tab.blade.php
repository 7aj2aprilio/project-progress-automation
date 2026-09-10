<div x-show="activeTab === 'weekly_reports'" x-cloak x-data="{ expandedReport: null }">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-slate-800">Weekly Reports</h3>
        @if(auth()->user()->canEdit())
            <div class="flex items-center gap-2">
                <button type="button" @click="$dispatch('open-manage-weeks')"
                        class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 rounded-lg text-sm font-semibold shadow-sm transition-all">
                    <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Set Week Periods ({{ $weeks->count() }})
                </button>

                <a href="{{ route('weekly-reports.create', ['project' => $project->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 shadow-sm transition-all">
                    + Create Weekly Report
                </a>
            </div>
        @endif
    </div>
    
    <div class="bg-white border rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-8"></th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Week No</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Actual (%)</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Manage Report</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($project->weeklyReports as $report)
                    <tr class="hover:bg-slate-50 cursor-pointer" @click="expandedReport = expandedReport === {{ $report->id }} ? null : {{ $report->id }}">
                        <td class="px-4 py-3 text-center text-gray-500">
                            <svg class="w-5 h-5 mx-auto transition-transform duration-200" :class="{'rotate-90': expandedReport === {{ $report->id }}}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-900 font-medium">Week {{ $report->week_number }}</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-500">
                            {{ $report->start_date->format('d M Y') }} - {{ $report->end_date->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-500 font-semibold">
                            {{ number_format($report->total_realisasi, 2) }}%
                        </td>
                        <td class="px-4 py-3 text-center text-sm font-medium">
                            <a href="{{ route('weekly-reports.show', $report->id) }}" class="inline-block text-indigo-600 hover:text-indigo-900 mr-3 px-3 py-1 bg-indigo-50 rounded" @click.stop>
                                {{ auth()->user()->canEdit() ? 'Details & Input Progress' : 'View Details' }}
                            </a>
                            <a href="{{ route('weekly-reports.download-pdf', $report->id) }}" class="inline-block text-emerald-600 hover:text-emerald-900 px-3 py-1 bg-emerald-50 rounded" @click.stop>
                                Download PDF
                            </a>
                        </td>
                    </tr>
                    
                    <!-- Dropdown Content (BOQ Details) -->
                    <tr x-show="expandedReport === {{ $report->id }}" x-cloak class="bg-slate-50 border-b border-gray-200">
                        <td colspan="5" class="p-0">
                            <div class="px-12 py-4 shadow-inner" x-data="{ 
                                expandedMains: [], 
                                expandedSubs: [], 
                                toggleMain(id) { 
                                    if(this.expandedMains.includes(id)) this.expandedMains = this.expandedMains.filter(i => i !== id); 
                                    else this.expandedMains.push(id); 
                                }, 
                                toggleSub(id) { 
                                    if(this.expandedSubs.includes(id)) this.expandedSubs = this.expandedSubs.filter(i => i !== id); 
                                    else this.expandedSubs.push(id); 
                                } 
                            }">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">Progress Details (BoQ) - Week {{ $report->week_number }}</h4>
                                @if($report->progresses->count() > 0)
                                    <table class="min-w-full text-sm text-left text-gray-500 bg-white border rounded">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-2 border-b w-1/2">Work Name</th>
                                                <th class="px-4 py-2 border-b text-right">Total Weight</th>
                                                <th class="px-4 py-2 border-b text-right text-indigo-700">Progress (%) Up To This Week</th>
                                                <th class="px-4 py-2 border-b text-right text-emerald-700">Weight Achievement</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($workItems as $mainItem)
                                                @php
                                                    $mBobot = $mainItem->base_bobot;
                                                    $mBobotSD = 0;
                                                    foreach($mainItem->children as $child) {
                                                        if ($child->type === 'sub') {
                                                            foreach($child->children as $c) {
                                                                $prog = $report->progresses->firstWhere('work_item_id', $c->id);
                                                                if ($prog) $mBobotSD += ($c->base_bobot * $prog->progress_percentage) / 100;
                                                            }
                                                        } else {
                                                            $prog = $report->progresses->firstWhere('work_item_id', $child->id);
                                                            if ($prog) $mBobotSD += ($child->base_bobot * $prog->progress_percentage) / 100;
                                                        }
                                                    }
                                                    $mPrestasiSD = $mBobot > 0 ? ($mBobotSD / $mBobot) * 100 : 0;
                                                @endphp
                                                <tr class="border-b bg-gray-100 font-bold cursor-pointer hover:bg-gray-200" @click="toggleMain({{ $mainItem->id }})">
                                                    <td class="px-4 py-2 text-gray-900">
                                                        <svg class="w-4 h-4 inline-block mr-1 text-gray-500 transition-transform" :class="{'rotate-90': expandedMains.includes({{ $mainItem->id }})}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                        {{ $mainItem->name }}
                                                    </td>
                                                    <td class="px-4 py-2 text-right">{{ number_format($mBobot, 2) }}</td>
                                                    <td class="px-4 py-2 text-right font-medium text-indigo-700">{{ number_format($mPrestasiSD, 2) }}%</td>
                                                    <td class="px-4 py-2 text-right text-emerald-700">{{ number_format($mBobotSD, 2) }}</td>
                                                </tr>
                                                @foreach($mainItem->children as $subItem)
                                                    @if($subItem->type === 'sub')
                                                        @php
                                                            $sBobot = $subItem->base_bobot;
                                                            $sBobotSD = 0;
                                                            foreach($subItem->children as $c) {
                                                                $prog = $report->progresses->firstWhere('work_item_id', $c->id);
                                                                if ($prog) $sBobotSD += ($c->base_bobot * $prog->progress_percentage) / 100;
                                                            }
                                                            $sPrestasiSD = $sBobot > 0 ? ($sBobotSD / $sBobot) * 100 : 0;
                                                        @endphp
                                                        <tr x-show="expandedMains.includes({{ $mainItem->id }})" x-cloak class="border-b bg-gray-50 font-semibold cursor-pointer hover:bg-gray-100" @click="toggleSub({{ $subItem->id }})">
                                                            <td class="px-4 py-2 text-gray-800 pl-8">
                                                                <svg class="w-4 h-4 inline-block mr-1 text-gray-400 transition-transform" :class="{'rotate-90': expandedSubs.includes({{ $subItem->id }})}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                                {{ $subItem->name }}
                                                            </td>
                                                            <td class="px-4 py-2 text-right">{{ number_format($sBobot, 2) }}</td>
                                                            <td class="px-4 py-2 text-right font-medium text-indigo-700">{{ number_format($sPrestasiSD, 2) }}%</td>
                                                            <td class="px-4 py-2 text-right text-emerald-700">{{ number_format($sBobotSD, 2) }}</td>
                                                        </tr>
                                                        @foreach($subItem->children as $item)
                                                            @php $prog = $report->progresses->firstWhere('work_item_id', $item->id); $pVal = $prog ? $prog->progress_percentage : 0; @endphp
                                                            <tr x-show="expandedMains.includes({{ $mainItem->id }}) && expandedSubs.includes({{ $subItem->id }})" x-cloak class="border-b hover:bg-gray-50">
                                                                <td class="px-4 py-2 text-gray-600 pl-14">{{ $item->name }}</td>
                                                                <td class="px-4 py-2 text-right">{{ number_format($item->base_bobot, 2) }}</td>
                                                                <td class="px-4 py-2 text-right font-medium text-indigo-700">{{ number_format($pVal, 2) }}%</td>
                                                                <td class="px-4 py-2 text-right font-medium text-emerald-700">{{ number_format(($pVal / 100) * $item->base_bobot, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        @php $prog = $report->progresses->firstWhere('work_item_id', $subItem->id); $pVal = $prog ? $prog->progress_percentage : 0; @endphp
                                                        <tr x-show="expandedMains.includes({{ $mainItem->id }})" x-cloak class="border-b hover:bg-gray-50">
                                                            <td class="px-4 py-2 text-gray-600 pl-8">{{ $subItem->name }}</td>
                                                            <td class="px-4 py-2 text-right">{{ number_format($subItem->base_bobot, 2) }}</td>
                                                            <td class="px-4 py-2 text-right font-medium text-indigo-700">{{ number_format($pVal, 2) }}%</td>
                                                            <td class="px-4 py-2 text-right font-medium text-emerald-700">{{ number_format(($pVal / 100) * $subItem->base_bobot, 2) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-sm text-gray-500 italic py-2">No progress data for this week yet.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500 italic">No weekly reports yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
