<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ProjectCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectCalculator $calculator
    ) {}

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'status' => 'draft',
        ]);

        // Create empty fixed child records
        $project->information()->create(['project_id' => $project->id]);
        $project->assumption()->create(['project_id' => $project->id]);
        $project->costStructure()->create(['project_id' => $project->id]);

        return redirect()->route('projects.edit', $project)
            ->with('success', 'Proyek berhasil dibuat! Silakan isi detailnya.');
    }

    public function show(Project $project)
    {
        $project->load([
            'information', 'assumption', 'costStructure',
            'beban', 'jaminan', 'revenues', 'pajak', 'pinjaman'
        ]);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $project->load([
            'information', 'assumption', 'costStructure',
            'beban', 'jaminan', 'revenues', 'pajak', 'pinjaman'
        ]);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:draft,active,completed',
        ]);

        DB::transaction(function () use ($request, $project) {
            $project->update($request->only('name', 'status'));

            // ── Fixed Modules ────────────
            $infoData = $request->input('information', []);
            if ($request->input('revenue_mode') === 'rincian') {
                $infoData['total_revenue'] = null;
            }
            $project->information->update($infoData);
            
            $project->assumption->update($request->input('assumption', []));
            $project->costStructure->update($request->input('cost_structure', []));

            // ── Dynamic Modules ────────────
            
            // Beban
            $project->beban()->delete();
            $bebanData = $request->input('beban', []);
            foreach ($bebanData as $b) {
                if (!empty($b['name'])) {
                    $project->beban()->create([
                        'name' => $b['name'],
                        'amount' => $b['amount'] ?: 0,
                    ]);
                }
            }

            // Jaminan
            $project->jaminan()->delete();
            $jaminanData = $request->input('jaminan', []);
            foreach ($jaminanData as $j) {
                if (!empty($j['name'])) {
                    $isManual = !empty($j['amount']);
                    $project->jaminan()->create([
                        'name' => $j['name'],
                        'percentage' => $j['percentage'] ?: null,
                        'amount' => $j['amount'] ?: null,
                        'is_manual' => $isManual,
                    ]);
                }
            }

            // Revenues
            $project->revenues()->delete();
            if ($request->input('revenue_mode') !== 'total') {
                $revenueData = $request->input('revenues', []);
                foreach ($revenueData as $r) {
                    if (!empty($r['name'])) {
                        $project->revenues()->create([
                            'name' => $r['name'],
                            'amount' => $r['amount'] ?: 0,
                        ]);
                    }
                }
            }

            // Pajak
            $project->pajak()->delete();
            $pajakData = $request->input('pajak', []);
            foreach ($pajakData as $p) {
                if (!empty($p['name'])) {
                    $isManual = !empty($p['amount']);
                    $project->pajak()->create([
                        'name' => $p['name'],
                        'amount' => $p['amount'] ?: null,
                        'is_manual' => $isManual,
                    ]);
                }
            }

            // Pinjaman
            $project->pinjaman()->delete();
            $pinjamanData = $request->input('pinjaman', []);
            foreach ($pinjamanData as $p) {
                if (!empty($p['name'])) {
                    $isManual = !empty($p['amount']);
                    $project->pinjaman()->create([
                        'name' => $p['name'],
                        'amount' => $p['amount'] ?: null,
                        'is_manual' => $isManual,
                    ]);
                }
            }

            // Recalculate dynamic values
            $project->refresh();
            $this->calculator->calculate($project);
        });

        return redirect()->route('projects.show', $project)
            ->with('success', 'Data proyek berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Proyek berhasil dihapus!');
    }

    public function toggleGantt(Request $request, Project $project)
    {
        $request->validate([
            'work_item_id' => 'required|exists:work_items,id',
            'month_year' => 'required|string',
            'week' => 'required|integer|min:1|max:5',
        ]);

        $schedule = \App\Models\GanttSchedule::where('project_id', $project->id)
            ->where('work_item_id', $request->work_item_id)
            ->where('month_year', $request->month_year)
            ->where('week', $request->week)
            ->first();

        if ($schedule) {
            $schedule->delete();
            return response()->json(['status' => 'removed']);
        } else {
            \App\Models\GanttSchedule::create([
                'project_id' => $project->id,
                'work_item_id' => $request->work_item_id,
                'month_year' => $request->month_year,
                'week' => $request->week,
            ]);
            return response()->json(['status' => 'added']);
        }
    }
}
