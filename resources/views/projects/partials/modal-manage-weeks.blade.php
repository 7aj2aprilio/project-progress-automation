{{-- Modal Kelola Periode Minggu Proyek --}}
<div x-data="manageWeeksComponent({{ json_encode($weeks->map(fn($w) => [
    'id' => $w->id,
    'week_number' => $w->week_number,
    'start_date' => $w->start_date ? $w->start_date->format('Y-m-d') : '',
    'end_date' => $w->end_date ? $w->end_date->format('Y-m-d') : '',
    'notes' => $w->notes ?? '',
])) }})"
    x-show="showManageWeeksModal" 
    x-cloak 
    style="display: none;"
    @open-manage-weeks.window="showManageWeeksModal = true"
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true">

    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
         @click="showManageWeeksModal = false"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-slate-200">
            
            {{-- Header --}}
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 px-6 py-5 text-white flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="p-2 bg-indigo-500/20 rounded-lg border border-indigo-400/30">
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold" id="modal-title">Manage Project Week Periods</h3>
                        <p class="text-xs text-slate-300">Define week periods manually (duration does not have to be 7 days)</p>
                    </div>
                </div>
                <button @click="showManageWeeksModal = false" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-4">
                
                {{-- Info Notice --}}
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start space-x-3">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-xs text-amber-800 leading-relaxed">
                        <strong class="font-semibold">Flexible Period Info:</strong><br>
                        Each week row can be set with a free date range (e.g., 3 days, 4 days, or 7 days).<br>
                        When you save, the <strong>Weekly Report template</strong> will automatically sync and the Time Schedule columns will adjust accordingly.
                    </div>
                </div>

                {{-- Table of Weeks --}}
                <div class="border border-slate-200 rounded-xl overflow-hidden max-h-80 overflow-y-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-2.5 text-left font-semibold text-slate-700 w-24">Week No</th>
                                <th class="px-4 py-2.5 text-left font-semibold text-slate-700">Start Date</th>
                                <th class="px-4 py-2.5 text-left font-semibold text-slate-700">End Date</th>
                                <th class="px-4 py-2.5 text-left font-semibold text-slate-700">Notes / Remarks</th>
                                <th class="px-3 py-2.5 text-center font-semibold text-slate-700 w-16">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <template x-for="(w, idx) in weekList" :key="idx">
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-2">
                                        <div class="flex items-center space-x-1">
                                            <span class="text-slate-400 text-xs">W-</span>
                                            <input type="number" min="1" x-model.number="w.week_number" 
                                                   class="w-16 rounded-lg border-slate-300 text-xs font-bold text-center focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2">
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="date" x-model="w.start_date" 
                                               class="w-full rounded-lg border-slate-300 text-xs focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="date" x-model="w.end_date" 
                                               class="w-full rounded-lg border-slate-300 text-xs focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="text" x-model="w.notes" placeholder="Optional (e.g., 4 days)" 
                                               class="w-full rounded-lg border-slate-300 text-xs focus:ring-indigo-500 focus:border-indigo-500 py-1 px-2">
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <button type="button" @click="removeWeek(idx)" 
                                                class="text-rose-500 hover:text-rose-700 p-1.5 rounded-lg hover:bg-rose-50 transition-colors"
                                                title="Delete week row">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            <template x-if="weekList.length === 0">
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-slate-400 italic text-xs">
                                        No week periods added yet. Click the button below to add the first week.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Add Button --}}
                <div class="flex justify-between items-center pt-1">
                    <button type="button" @click="addWeek()" 
                            class="inline-flex items-center px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-colors">
                        <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        + Add Next Week
                    </button>
                    
                    <span class="text-xs text-slate-500 font-medium" x-text="`${weekList.length} Weeks registered`"></span>
                </div>

            </div>

            {{-- Footer --}}
            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-between items-center">
                <button type="button" @click="showManageWeeksModal = false" 
                        class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-100 transition-colors">
                    Close
                </button>
                
                <button type="button" @click="saveWeeks()" :disabled="loading"
                        class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors">
                    <svg x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="loading ? 'Saving...' : 'Save Week Periods'"></span>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
function manageWeeksComponent(initialWeeks) {
    return {
        showManageWeeksModal: false,
        weekList: initialWeeks || [],
        loading: false,

        addWeek() {
            let nextNum = 1;
            let lastEnd = '';
            
            if (this.weekList.length > 0) {
                const maxNum = Math.max(...this.weekList.map(w => w.week_number || 0));
                nextNum = maxNum + 1;
                
                // Try to propose start date = day after last end_date
                const lastWeek = this.weekList[this.weekList.length - 1];
                if (lastWeek.end_date) {
                    const d = new Date(lastWeek.end_date);
                    d.setDate(d.getDate() + 1);
                    lastEnd = d.toISOString().split('T')[0];
                }
            }

            this.weekList.push({
                week_number: nextNum,
                start_date: lastEnd,
                end_date: '',
                notes: ''
            });
        },

        removeWeek(idx) {
            if (confirm('Delete this week row? Time Schedule column data for this week will be deleted.')) {
                this.weekList.splice(idx, 1);
            }
        },

        async saveWeeks() {
            // Validation
            for (let i = 0; i < this.weekList.length; i++) {
                const w = this.weekList[i];
                if (!w.week_number || !w.start_date || !w.end_date) {
                    alert(`Row ${i+1}: Please complete week number, start date, and end date.`);
                    return;
                }
                if (w.start_date > w.end_date) {
                    alert(`Row ${i+1}: End date cannot be before start date.`);
                    return;
                }
            }

            this.loading = true;

            try {
                const res = await fetch("{{ route('projects.weeks.save', $project) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ weeks: this.weekList })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    alert(data.message || 'Week periods saved successfully!');
                    // Reload page to refresh columns and tables
                    window.location.search = '?tab=weekly_reports';
                } else {
                    alert(data.message || 'Failed to save week periods.');
                }
            } catch (err) {
                console.error(err);
                alert('Network error occurred while saving.');
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
