{{-- Tab Time Schedule & Kurva S --}}
<div x-show="activeTab === 'time_schedule'" 
     x-cloak 
     x-data="timeScheduleComponent()" 
     x-init="init()">
    
    {{-- Top Action Toolbar --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 bg-slate-50 p-4 rounded-2xl border border-slate-200">
        <div>
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="p-1.5 bg-indigo-100 text-indigo-700 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                </span>
                Time Schedule & S-Curve
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Green Row = Actual weight (auto from Weekly Report) • Blue Row = Plan (manual input)
            </p>
        </div>

        <div>
            <!-- Buttons moved -->
        </div>
    </div>

    @if($weeks->isEmpty())
        {{-- Empty State jika belum ada periode minggu --}}
        <div class="p-12 text-center bg-slate-50 border-2 border-dashed border-slate-300 rounded-3xl space-y-4">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="max-w-md mx-auto">
                <h4 class="text-base font-bold text-slate-800">Week Periods Not Yet Created</h4>
                <p class="text-xs text-slate-500 mt-1">
                    Please configure and define your project weeks manually first before filling out the plan and viewing the S-Curve.
                </p>
            </div>
            @if(auth()->user()->canEdit())
                <button type="button" @click="$dispatch('open-manage-weeks')"
                        class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-md transition-all">
                    + Create Week Periods Now
                </button>
            @endif
        </div>
    @else

        {{-- Kurva S Chart Card --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm mb-6 space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                    <span class="text-xs font-bold text-slate-700">S-Curve Chart (Cumulative Plan vs Actual)</span>
                </div>
                <div class="flex items-center space-x-4 text-xs font-semibold">
                    <span class="flex items-center space-x-1.5 text-rose-600">
                        <span class="w-3 h-0.5 bg-rose-500 inline-block"></span>
                        <span>Cumulative Plan</span>
                    </span>
                    <span class="flex items-center space-x-1.5 text-emerald-600">
                        <span class="w-3 h-0.5 bg-emerald-500 inline-block"></span>
                        <span>Cumulative Actual</span>
                    </span>
                </div>
            </div>

            <div class="relative h-72 w-full">
                <canvas id="kurvaSChart"></canvas>
            </div>
        </div>

        {{-- Matriks Time Schedule Table (Excel Style) --}}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto max-h-[750px] overflow-y-auto">
                <table class="w-full border-collapse text-xs text-left" id="timeScheduleMatrix">
                    
                    {{-- Sticky Header --}}
                    <thead class="bg-slate-100 text-slate-700 sticky top-0 z-30 shadow-sm">
                        <tr class="divide-x divide-slate-200 border-b border-slate-300 font-bold uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-2 text-center w-12 sticky left-0 bg-slate-100 z-40">No</th>
                            <th class="py-3 px-3 min-w-[280px] sticky left-12 bg-slate-100 z-40">Work Description</th>
                            <th class="py-3 px-2 text-right w-24 sticky left-[328px] bg-slate-100 z-40">Weight</th>
                            
                            @foreach($weeks as $w)
                                <th class="py-2.5 px-2 text-center min-w-[105px] max-w-[125px]">
                                    <div class="font-bold text-indigo-950">Week {{ $w->week_number }}</div>
                                    <div class="text-[10px] font-normal text-slate-500 tracking-normal">{{ $w->formatted_range }}</div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @php $no = 1; @endphp
                        @foreach($workItems as $mainItem)
                            
                            {{-- 1. MAIN ITEM ROW (Hierarki I, II, III) --}}
                            <tr class="bg-slate-100 font-bold text-slate-900 border-t-2 border-slate-300">
                                <td class="py-2 px-2 text-center sticky left-0 bg-slate-100 z-20">{{ $no++ }}</td>
                                <td class="py-2 px-3 sticky left-12 bg-slate-100 z-20 tracking-wide">{{ $mainItem->name }}</td>
                                <td class="py-2 px-2 text-right sticky left-[328px] bg-slate-100 z-20 text-indigo-900">
                                    {{ number_format($mainItem->base_bobot, 2) }}
                                </td>
                                
                                {{-- Main Item Realisasi Row (calculated live) --}}
                                @foreach($weeks as $w)
                                    <td class="py-1 px-1 text-center bg-emerald-50/70 text-emerald-900 font-semibold border-l border-slate-200">
                                        <span x-text="formatDec(getMainRealisasi({{ $mainItem->id }}, {{ $w->id }}))"></span>
                                    </td>
                                @endforeach
                            </tr>

                            {{-- Main Item Plan Row --}}
                            <tr class="bg-slate-50 text-slate-800 border-b border-slate-200">
                                <td class="py-1 px-2 sticky left-0 bg-slate-50 z-20"></td>
                                <td class="py-1 px-3 text-[11px] text-slate-500 italic sticky left-12 bg-slate-50 z-20 pl-6">
                                    ↳ Plan (Subtotal)
                                </td>
                                <td class="py-1 px-2 sticky left-[328px] bg-slate-50 z-20"></td>
                                
                                @foreach($weeks as $w)
                                    <td class="py-1 px-1 text-center bg-sky-50/70 text-sky-900 font-bold border-l border-slate-200">
                                        <span x-text="formatDec(getMainPlan({{ $mainItem->id }}, {{ $w->id }}))"></span>
                                    </td>
                                @endforeach
                            </tr>

                            {{-- 2. CHILDREN OF MAIN --}}
                            @foreach($mainItem->children as $child)
                                @if($child->type === 'sub')
                                    {{-- SUB CATEGORY ROW --}}
                                    <tr class="bg-slate-50/80 font-semibold text-slate-800 border-t border-slate-200">
                                        <td class="py-1.5 px-2 text-center sticky left-0 bg-slate-50 z-20"></td>
                                        <td class="py-1.5 px-3 sticky left-12 bg-slate-50 z-20 pl-7 text-indigo-950">
                                            • {{ $child->name }}
                                        </td>
                                        <td class="py-1.5 px-2 text-right sticky left-[328px] bg-slate-50 z-20 text-slate-700">
                                            {{ number_format($child->base_bobot, 2) }}
                                        </td>

                                        {{-- Sub Item Realisasi --}}
                                        @foreach($weeks as $w)
                                            <td class="py-1 px-1 text-center bg-emerald-50/40 text-emerald-800 text-[11px] border-l border-slate-200">
                                                <span x-text="formatDec(getSubRealisasi({{ $child->id }}, {{ $w->id }}))"></span>
                                            </td>
                                        @endforeach
                                    </tr>

                                    {{-- Sub Item Plan --}}
                                    <tr class="bg-white text-slate-700 border-b border-slate-200">
                                        <td class="py-1 px-2 sticky left-0 bg-white z-20"></td>
                                        <td class="py-1 px-3 text-[10px] text-slate-400 italic sticky left-12 bg-white z-20 pl-10">
                                            ↳ Plan (Sub)
                                        </td>
                                        <td class="py-1 px-2 sticky left-[328px] bg-white z-20"></td>

                                        @foreach($weeks as $w)
                                            <td class="py-1 px-1 text-center bg-sky-50/40 text-sky-800 text-[11px] font-medium border-l border-slate-200">
                                                <span x-text="formatDec(getSubPlan({{ $child->id }}, {{ $w->id }}))"></span>
                                            </td>
                                        @endforeach
                                    </tr>

                                    {{-- LEAF ITEMS OF SUB --}}
                                    @foreach($child->children as $item)
                                        @include('projects.partials.time-schedule-item-rows', ['item' => $item, 'weeks' => $weeks, 'indent' => 'pl-12'])
                                    @endforeach

                                @else
                                    {{-- DIRECT LEAF ITEM OF MAIN --}}
                                    @include('projects.partials.time-schedule-item-rows', ['item' => $child, 'weeks' => $weeks, 'indent' => 'pl-8'])
                                @endif
                            @endforeach

                        @endforeach
                    </tbody>

                    {{-- Sticky Summary Footer (5 Rows) --}}
                    <tfoot class="sticky bottom-0 z-30 font-bold divide-y divide-slate-300 shadow-lg">
                        
                        {{-- 1. RENCANA (SUM Baris Biru) --}}
                        <tr class="bg-amber-50/90 text-amber-950 border-t-2 border-amber-300">
                            <td colspan="3" class="py-2.5 px-4 text-left uppercase tracking-wider sticky left-0 bg-amber-50 z-40">
                                1. PLAN
                            </td>
                            @foreach($weeks as $w)
                                <td class="py-2.5 px-1 text-center text-amber-900 border-l border-amber-200">
                                    <span x-text="formatDec(getWeekRencana({{ $w->id }}))"></span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- 2. RENCANA KOMULATIF --}}
                        <tr class="bg-amber-100 text-amber-950">
                            <td colspan="3" class="py-2.5 px-4 text-left uppercase tracking-wider sticky left-0 bg-amber-100 z-40">
                                2. CUMULATIVE PLAN
                            </td>
                            @foreach($weeks as $w)
                                <td class="py-2.5 px-1 text-center text-amber-950 font-black border-l border-amber-200">
                                    <span x-text="formatDec(getWeekRencanaKomulatif({{ $w->id }}))"></span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- 3. REALISASI (SUM Baris Hijau) --}}
                        <tr class="bg-emerald-50/90 text-emerald-950">
                            <td colspan="3" class="py-2.5 px-4 text-left uppercase tracking-wider sticky left-0 bg-emerald-50 z-40">
                                3. ACTUAL
                            </td>
                            @foreach($weeks as $w)
                                <td class="py-2.5 px-1 text-center text-emerald-900 border-l border-emerald-200">
                                    <span x-text="formatDecOrDash(getWeekRealisasi({{ $w->id }}))"></span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- 4. REALISASI KOMULATIF --}}
                        <tr class="bg-emerald-100 text-emerald-950">
                            <td colspan="3" class="py-2.5 px-4 text-left uppercase tracking-wider sticky left-0 bg-emerald-100 z-40">
                                4. CUMULATIVE ACTUAL
                            </td>
                            @foreach($weeks as $w)
                                <td class="py-2.5 px-1 text-center text-emerald-950 font-black border-l border-emerald-200">
                                    <span x-text="formatDecOrDash(getWeekRealisasiKomulatif({{ $w->id }}))"></span>
                                </td>
                            @endforeach
                        </tr>

                        {{-- 5. DEVIASI --}}
                        <tr class="bg-slate-900 text-white">
                            <td colspan="3" class="py-2.5 px-4 text-left uppercase tracking-wider sticky left-0 bg-slate-900 z-40">
                                5. DEVIATION
                            </td>
                            @foreach($weeks as $w)
                                <td class="py-2.5 px-1 text-center border-l border-slate-700">
                                    <template x-if="getWeekDeviasi({{ $w->id }}) !== null">
                                        <span :class="getWeekDeviasi({{ $w->id }}) >= 0 ? 'text-emerald-400' : 'text-rose-400 font-black'"
                                              x-text="(getWeekDeviasi({{ $w->id }}) > 0 ? '+' : '') + formatDec(getWeekDeviasi({{ $w->id }}))">
                                        </span>
                                    </template>
                                    <template x-if="getWeekDeviasi({{ $w->id }}) === null">
                                        <span class="text-slate-500">-</span>
                                    </template>
                                </td>
                            @endforeach
                        </tr>

                    </tfoot>

                </table>
            </div>
            
            {{-- Tombol Simpan Rencana --}}
            @if(auth()->user()->canEdit())
                <div class="mt-4 flex justify-end p-4 border-t border-slate-200 bg-slate-50">
                    <button type="button" @click="savePlans()" :disabled="saving"
                            class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white rounded-xl text-sm font-semibold shadow-sm transition-all">
                        <svg x-show="saving" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <svg x-show="!saving" class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        <span x-text="saving ? 'Saving...' : 'Save Plan'"></span>
                    </button>
                </div>
            @endif
        </div>

    @endif

</div>

<script>
let kurvaSChartInstance = null;

function initKurvaSChart(canvas, labels, renData, realData) {
    try {
        if (kurvaSChartInstance) {
            kurvaSChartInstance.destroy();
            kurvaSChartInstance = null;
        }
        const existingChart = Chart.getChart(canvas);
        if (existingChart) {
            existingChart.destroy();
        }
        kurvaSChartInstance = new Chart(canvas, {
            type: 'line',
            data: {
                labels: [...labels],
                datasets: [
                    {
                        label: 'Cumulative Plan',
                        data: [...renData],
                        borderColor: '#e11d48',
                        backgroundColor: 'rgba(225, 29, 72, 0.05)',
                        borderWidth: 2.5,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#e11d48',
                        pointHoverRadius: 6,
                        fill: true,
                    },
                    {
                        label: 'Cumulative Actual',
                        data: [...realData],
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.05)',
                        borderWidth: 2.5,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#059669',
                        pointHoverRadius: 6,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    y: {
                        suggestedMin: 0,
                        suggestedMax: 100,
                        ticks: { callback: val => val },
                        grid: { color: '#f1f5f9' }
                    },
                    x: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y !== null ? ctx.parsed.y.toFixed(2) : '-'}`
                        }
                    }
                }
            }
        });
    } catch (err) {
        console.error('INIT CHART ERROR:', err.name, err.message, err.stack);
    }
}

function refreshKurvaSChart(renData, realData) {
    if (!kurvaSChartInstance) return false;
    try {
        kurvaSChartInstance.data.datasets[0].data = [...renData];
        kurvaSChartInstance.data.datasets[1].data = [...realData];
        kurvaSChartInstance.update('none');
        return true;
    } catch (err) {
        console.error('REFRESH CHART ERROR:', err.name, err.message, err.stack);
        return false;
    }
}

function timeScheduleComponent() {
    const rawWeeks = @json($weeks->map(fn($w) => ['id' => $w->id, 'week_number' => $w->week_number, 'label' => 'W-' . $w->week_number])->values());
    const rawPlans = @json($plans->mapWithKeys(fn($p) => [$p->work_item_id . '_' . $p->project_week_id => (float) $p->plan_value])->toArray());
    const rawRealisasi = @json($realisasiMap ?? []);
    const hierarchy = @json($timeScheduleHierarchy ?? []);

    // Pre-initialize all keys in rawPlans to ensure Alpine reactive tracking
    const allItemIds = [];
    hierarchy.forEach(m => {
        m.directItems.forEach(id => allItemIds.push(id));
        m.subs.forEach(s => s.items.forEach(id => allItemIds.push(id)));
    });
    rawWeeks.forEach(w => {
        allItemIds.forEach(id => {
            const k = `${id}_${w.id}`;
            if (rawPlans[k] === undefined) {
                rawPlans[k] = 0;
            }
        });
    });

    return {
        weeks: rawWeeks,
        plans: rawPlans,
        realisasi: rawRealisasi,
        hierarchy: hierarchy,
        saving: false,
        rawInputValues: {},

        init() {
            this.$watch('activeTab', (val) => {
                if (val === 'time_schedule') {
                    this.$nextTick(() => this.renderChart());
                }
            });
            this.$nextTick(() => {
                this.renderChart();
            });
        },

        formatDec(val) {
            if (val === null || val === undefined || isNaN(val)) return '0.00';
            return parseFloat(val).toFixed(2);
        },

        formatDecOrDash(val) {
            if (val === null || val === undefined) return '-';
            return parseFloat(val).toFixed(2);
        },

        // Leaf item accessors
        getItemPlan(itemId, weekId) {
            const key = `${itemId}_${weekId}`;
            if (this.rawInputValues && this.rawInputValues[key] !== undefined) {
                return this.rawInputValues[key];
            }
            return this.plans[key] !== undefined && this.plans[key] !== 0 ? this.plans[key] : '';
        },

        setItemPlan(itemId, weekId, val) {
            const key = `${itemId}_${weekId}`;
            this.rawInputValues[key] = val;

            // Support both comma (,) and dot (.) as decimal separator
            const cleanVal = String(val).replace(',', '.');
            const num = parseFloat(cleanVal);

            this.plans[key] = isNaN(num) ? 0 : num;
            this.updateChart();
        },

        getItemRealisasi(itemId, weekId) {
            if (this.realisasi[itemId] && this.realisasi[itemId][weekId] !== undefined) {
                return this.realisasi[itemId][weekId];
            }
            return null;
        },

        // Subcategory sum
        getSubPlan(subId, weekId) {
            let total = 0;
            for (const m of this.hierarchy) {
                const sub = m.subs.find(s => s.id === subId);
                if (sub) {
                    for (const itemId of sub.items) {
                        const key = `${itemId}_${weekId}`;
                        total += parseFloat(this.plans[key] || 0);
                    }
                    break;
                }
            }
            return total;
        },

        getSubRealisasi(subId, weekId) {
            let total = 0;
            let hasAny = false;
            for (const m of this.hierarchy) {
                const sub = m.subs.find(s => s.id === subId);
                if (sub) {
                    for (const itemId of sub.items) {
                        const r = this.getItemRealisasi(itemId, weekId);
                        if (r !== null) {
                            total += r;
                            hasAny = true;
                        }
                    }
                    break;
                }
            }
            return hasAny ? total : null;
        },

        // Main category sum
        getMainPlan(mainId, weekId) {
            let total = 0;
            const m = this.hierarchy.find(h => h.id === mainId);
            if (m) {
                for (const sub of m.subs) {
                    for (const itemId of sub.items) {
                        const key = `${itemId}_${weekId}`;
                        total += parseFloat(this.plans[key] || 0);
                    }
                }
                for (const itemId of m.directItems) {
                    const key = `${itemId}_${weekId}`;
                    total += parseFloat(this.plans[key] || 0);
                }
            }
            return total;
        },

        getMainRealisasi(mainId, weekId) {
            let total = 0;
            let hasAny = false;
            const m = this.hierarchy.find(h => h.id === mainId);
            if (m) {
                for (const sub of m.subs) {
                    for (const itemId of sub.items) {
                        const r = this.getItemRealisasi(itemId, weekId);
                        if (r !== null) {
                            total += r;
                            hasAny = true;
                        }
                    }
                }
                for (const itemId of m.directItems) {
                    const r = this.getItemRealisasi(itemId, weekId);
                    if (r !== null) {
                        total += r;
                        hasAny = true;
                    }
                }
            }
            return hasAny ? total : null;
        },

        // Footer Summary calculations
        getWeekRencana(weekId) {
            let total = 0;
            for (const m of this.hierarchy) {
                for (const sub of m.subs) {
                    for (const itemId of sub.items) {
                        total += parseFloat(this.plans[`${itemId}_${weekId}`] || 0);
                    }
                }
                for (const itemId of m.directItems) {
                    total += parseFloat(this.plans[`${itemId}_${weekId}`] || 0);
                }
            }
            return total;
        },

        getWeekRencanaKomulatif(weekId) {
            let run = 0;
            for (const w of this.weeks) {
                run += this.getWeekRencana(w.id);
                if (w.id === weekId) break;
            }
            return run;
        },

        getWeekRealisasi(weekId) {
            let total = 0;
            let hasAny = false;
            for (const m of this.hierarchy) {
                for (const sub of m.subs) {
                    for (const itemId of sub.items) {
                        const r = this.getItemRealisasi(itemId, weekId);
                        if (r !== null) {
                            total += r;
                            hasAny = true;
                        }
                    }
                }
                for (const itemId of m.directItems) {
                    const r = this.getItemRealisasi(itemId, weekId);
                    if (r !== null) {
                        total += r;
                        hasAny = true;
                    }
                }
            }
            return hasAny ? total : null;
        },

        getWeekRealisasiKomulatif(weekId) {
            let run = 0;
            let hasAny = false;
            for (const w of this.weeks) {
                const r = this.getWeekRealisasi(w.id);
                if (r !== null) {
                    run += r;
                    hasAny = true;
                }
                if (w.id === weekId) {
                    return r !== null ? run : (hasAny ? run : null);
                }
            }
            return null;
        },

        getWeekDeviasi(weekId) {
            const realKom = this.getWeekRealisasiKomulatif(weekId);
            if (realKom === null) return null;
            const renKom = this.getWeekRencanaKomulatif(weekId);
            return realKom - renKom;
        },

        // Save plans via AJAX
        async savePlans() {
            this.saving = true;
            const payload = [];
            
            for (const key in this.plans) {
                const parts = key.split('_');
                if (parts.length === 2) {
                    payload.push({
                        work_item_id: parseInt(parts[0]),
                        project_week_id: parseInt(parts[1]),
                        plan_value: parseFloat(this.plans[key] || 0)
                    });
                }
            }

            try {
                const res = await fetch("{{ route('projects.time-schedule.plans.save', $project) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ plans: payload })
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    alert('S-Curve Plan saved successfully!');
                } else {
                    alert(data.message || 'Failed to save plan.');
                }
            } catch (err) {
                console.error(err);
                alert('Network error occurred.');
            } finally {
                this.saving = false;
            }
        },

        // Kurva S Chart rendering
        renderChart() {
            const canvas = document.getElementById('kurvaSChart');
            if (!canvas) return;

            // If hidden (e.g. tab not active), wait until tab is shown
            if (canvas.offsetParent === null) return;

            if (typeof Chart === 'undefined') {
                setTimeout(() => this.renderChart(), 100);
                return;
            }

            const labels = this.weeks.map(w => 'Week ' + w.week_number);
            const renData = this.weeks.map(w => this.getWeekRencanaKomulatif(w.id));
            const realData = this.weeks.map(w => this.getWeekRealisasiKomulatif(w.id));

            initKurvaSChart(canvas, labels, renData, realData);
        },

        _chartTimer: null,
        updateChart() {
            clearTimeout(this._chartTimer);
            this._chartTimer = setTimeout(() => {
                const renData = this.weeks.map(w => this.getWeekRencanaKomulatif(w.id));
                const realData = this.weeks.map(w => this.getWeekRealisasiKomulatif(w.id));

                const ok = refreshKurvaSChart(renData, realData);
                if (!ok) {
                    this.renderChart();
                }
            }, 60);
        }
    };
}
</script>
