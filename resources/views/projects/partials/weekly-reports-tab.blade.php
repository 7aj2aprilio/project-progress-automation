<div x-show="activeTab === 'weekly_reports'" x-cloak>
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-slate-800">Laporan Mingguan</h3>
        @if(auth()->user()->canEdit())
            <a href="{{ route('weekly-reports.create', ['project' => $project->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700">
                + Buat Laporan Mingguan
            </a>
        @endif
    </div>
    
    <div class="bg-white border rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minggu Ke</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Realisasi (%)</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($project->weeklyReports as $report)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-sm text-gray-900 font-medium">Minggu ke-{{ $report->week_number }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $report->start_date->format('d M Y') }} - {{ $report->end_date->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ number_format($report->total_realisasi, 2) }}%
                        </td>
                        <td class="px-4 py-3 text-sm text-right font-medium">
                            <a href="{{ route('weekly-reports.show', $report->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Detail & Input Progress</a>
                            <a href="{{ route('weekly-reports.download-pdf', $report->id) }}" target="_blank" class="text-emerald-600 hover:text-emerald-900">
                                Download PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500 italic">Belum ada laporan mingguan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
