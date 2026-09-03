<div x-show="activeTab === 'jadwal'" style="display: none;">
    <div x-data="{
        ganttData: {{ json_encode($ganttData ?? []) }},
        toggleCell(workItemId, monthYear, week) {
            let key = workItemId + '_' + monthYear + '_' + week;
            let exists = this.ganttData.includes(key);
            
            if(exists) {
                this.ganttData = this.ganttData.filter(k => k !== key);
            } else {
                this.ganttData.push(key);
            }
            
            // Send AJAX request to save
            fetch('{{ route('projects.toggle-gantt', $project) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    work_item_id: workItemId,
                    month_year: monthYear,
                    week: week
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    alert('Gagal menyimpan jadwal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-semibold text-slate-800">Time Schedule (Gantt Chart)</h3>
                <p class="text-sm text-slate-500">Klik kotak pada kolom minggu untuk menandai jadwal pengerjaan masing-masing item.</p>
            </div>
            
            @if(count($projectMonths ?? []) > 0)
                <div class="flex items-center gap-6">
                    <a href="{{ route('projects.gantt.pdf', $project) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:bg-red-500 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export PDF
                    </a>
                </div>
            @endif
        </div>

        @if(count($projectMonths ?? []) === 0)
            <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg flex items-start">
                <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <h4 class="font-bold text-yellow-900">Periode Proyek Belum Diatur</h4>
                    <p class="mt-1">Kolom jadwal belum dapat ditampilkan karena <strong>Estimasi Mulai</strong> atau <strong>Estimasi Selesai</strong> belum diatur.</p>
                    <a href="{{ route('projects.show', $project) }}" class="mt-2 inline-block bg-yellow-100 text-yellow-800 font-semibold px-3 py-1 rounded hover:bg-yellow-200 transition-colors">Lihat Informasi Proyek</a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto border border-slate-300 rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 border-collapse table-fixed">
                    <thead class="bg-[#1E293B] text-white">
                        <tr>
                            <th rowspan="2" class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wider border-r border-slate-600 w-1/4 align-middle sticky left-0 bg-[#1E293B] z-20">
                                PROJECT MANAGEMENT
                            </th>
                            @foreach($projectMonths as $month)
                                <th colspan="{{ $month['weeks_count'] }}" class="px-2 py-2 text-center text-xs font-semibold uppercase tracking-wider border-b border-r border-slate-600">
                                    {{ $month['name'] }}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach($projectMonths as $month)
                                @for($w = 1; $w <= $month['weeks_count']; $w++)
                                    <th class="px-1 py-1 text-center text-xs font-medium border-r border-slate-600 bg-slate-700 w-10">
                                        W{{ $w }}
                                    </th>
                                @endfor
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($workItems as $main)
                            {{-- Main Work --}}
                            <tr class="bg-slate-50 hover:bg-slate-100 group/row">
                                <td class="px-4 py-2 whitespace-nowrap text-sm font-bold text-slate-800 border-r border-slate-200 sticky left-0 bg-slate-50 group-hover/row:bg-slate-100 z-10">
                                    {{ $main->name }}
                                </td>
                                @foreach($projectMonths as $month)
                                    @for($w = 1; $w <= $month['weeks_count']; $w++)
                                        <td class="border-r border-slate-200 p-0 text-center cursor-pointer transition-all duration-200 group"
                                            @click="toggleCell({{ $main->id }}, '{{ $month['key'] }}', {{ $w }})">
                                            <div class="w-full h-8 flex items-center justify-center transition-all duration-200"
                                                 :class="ganttData.includes('{{ $main->id }}_{{ $month['key'] }}_{{ $w }}') ? 'bg-emerald-500 border-y border-emerald-600' : 'group-hover:bg-slate-200/50'">
                                            </div>
                                        </td>
                                    @endfor
                                @endforeach
                            </tr>

                            @foreach($main->children as $sub)
                                @if($sub->type === 'sub')
                                    {{-- Sub Work --}}
                                    <tr class="bg-white hover:bg-slate-50 group/row">
                                        <td class="px-4 py-2 pl-8 whitespace-nowrap text-sm font-semibold text-slate-700 border-r border-slate-200 sticky left-0 bg-white group-hover/row:bg-slate-50 z-10">
                                            {{ $sub->name }}
                                        </td>
                                        @foreach($projectMonths as $month)
                                            @for($w = 1; $w <= $month['weeks_count']; $w++)
                                                <td class="border-r border-slate-200 p-0 text-center cursor-pointer transition-all duration-200 group"
                                                    @click="toggleCell({{ $sub->id }}, '{{ $month['key'] }}', {{ $w }})">
                                                    <div class="w-full h-7 flex items-center justify-center transition-all duration-200"
                                                         :class="ganttData.includes('{{ $sub->id }}_{{ $month['key'] }}_{{ $w }}') ? 'bg-emerald-500 border-y border-emerald-600' : 'group-hover:bg-slate-200/50'">
                                                    </div>
                                                </td>
                                            @endfor
                                        @endforeach
                                    </tr>

                                    @foreach($sub->children as $item)
                                        {{-- Item Work --}}
                                        <tr class="bg-white hover:bg-slate-50 group/row">
                                            <td class="px-4 py-2 pl-12 whitespace-nowrap text-sm text-slate-600 border-r border-slate-200 sticky left-0 bg-white group-hover/row:bg-slate-50 z-10">
                                                - {{ $item->name }}
                                            </td>
                                            @foreach($projectMonths as $month)
                                                @for($w = 1; $w <= $month['weeks_count']; $w++)
                                                    <td class="border-r border-slate-200 p-0 text-center cursor-pointer transition-all duration-200 group"
                                                        @click="toggleCell({{ $item->id }}, '{{ $month['key'] }}', {{ $w }})">
                                                        <div class="w-full h-6 flex items-center justify-center transition-all duration-200"
                                                             :class="ganttData.includes('{{ $item->id }}_{{ $month['key'] }}_{{ $w }}') ? 'bg-emerald-500 border-y border-emerald-600' : 'group-hover:bg-slate-200/50'">
                                                        </div>
                                                    </td>
                                                @endfor
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @else
                                    {{-- Direct Item Work --}}
                                    <tr class="bg-white hover:bg-slate-50 group/row">
                                        <td class="px-4 py-2 pl-8 whitespace-nowrap text-sm text-slate-600 border-r border-slate-200 sticky left-0 bg-white group-hover/row:bg-slate-50 z-10">
                                            - {{ $sub->name }}
                                        </td>
                                        @foreach($projectMonths as $month)
                                            @for($w = 1; $w <= $month['weeks_count']; $w++)
                                                <td class="border-r border-slate-200 p-0 text-center cursor-pointer transition-all duration-200 group"
                                                    @click="toggleCell({{ $sub->id }}, '{{ $month['key'] }}', {{ $w }})">
                                                    <div class="w-full h-6 flex items-center justify-center transition-all duration-200"
                                                         :class="ganttData.includes('{{ $sub->id }}_{{ $month['key'] }}_{{ $w }}') ? 'bg-emerald-500 border-y border-emerald-600' : 'group-hover:bg-slate-200/50'">
                                                    </div>
                                                </td>
                                            @endfor
                                        @endforeach
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
