<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectWeek;
use App\Models\TimeSchedulePlan;
use App\Models\WeeklyReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TimeScheduleController extends Controller
{
    /**
     * Save/update user-defined project weeks.
     * Also auto-generates/syncs draft WeeklyReport templates for those weeks safely.
     */
    public function saveWeeks(Request $request, Project $project)
    {
        $request->validate([
            'weeks' => 'present|array',
            'weeks.*.week_number' => 'required|integer|min:1',
            'weeks.*.start_date' => 'required|date',
            'weeks.*.end_date' => 'required|date|after_or_equal:weeks.*.start_date',
            'weeks.*.notes' => 'nullable|string|max:255',
        ]);

        $submittedWeekNumbers = [];

        foreach ($request->input('weeks', []) as $w) {
            $weekNumber = (int) $w['week_number'];
            $submittedWeekNumbers[] = $weekNumber;

            // 1. Update or create ProjectWeek
            ProjectWeek::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'week_number' => $weekNumber,
                ],
                [
                    'start_date' => $w['start_date'],
                    'end_date' => $w['end_date'],
                    'notes' => $w['notes'] ?? null,
                ]
            );

            // 2. Safely auto-generate/sync draft WeeklyReport
            $report = WeeklyReport::firstOrCreate(
                [
                    'project_id' => $project->id,
                    'week_number' => $weekNumber,
                ],
                [
                    'start_date' => $w['start_date'],
                    'end_date' => $w['end_date'],
                ]
            );

            // Keep dates synced if already exists
            if ($report->start_date->format('Y-m-d') !== $w['start_date'] || $report->end_date->format('Y-m-d') !== $w['end_date']) {
                $report->update([
                    'start_date' => $w['start_date'],
                    'end_date' => $w['end_date'],
                ]);
            }
        }

        // 3. Handle removed weeks
        $deletedWeeks = ProjectWeek::where('project_id', $project->id)
            ->whereNotIn('week_number', $submittedWeekNumbers)
            ->get();

        foreach ($deletedWeeks as $delWeek) {
            // Delete corresponding WeeklyReport ONLY if empty (no progress & no visuals)
            $rep = WeeklyReport::where('project_id', $project->id)
                ->where('week_number', $delWeek->week_number)
                ->first();

            if ($rep && $rep->progresses()->count() === 0 && $rep->visuals()->count() === 0) {
                $rep->delete();
            }

            $delWeek->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Periode minggu proyek dan template laporan mingguan berhasil disimpan!',
        ]);
    }

    /**
     * Batch save plan values (baris biru).
     */
    public function savePlans(Request $request, Project $project)
    {
        $request->validate([
            'plans' => 'present|array',
            'plans.*.work_item_id' => 'required|exists:work_items,id',
            'plans.*.project_week_id' => 'required|exists:project_weeks,id',
            'plans.*.plan_value' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($request->input('plans', []) as $item) {
            TimeSchedulePlan::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'work_item_id' => $item['work_item_id'],
                    'project_week_id' => $item['project_week_id'],
                ],
                [
                    'plan_value' => (float) ($item['plan_value'] ?? 0),
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Nilai rencana (plan) berhasil disimpan.',
        ]);
    }

    /**
     * Export Time Schedule & Kurva S table to Landscape PDF.
     */
    public function exportPdf(Project $project)
    {
        $workItems = $project->workItems()->whereNull('parent_id')->with('children.children')->get();
        $weeks = $project->weeks()->orderBy('week_number')->get();
        
        $plans = $project->timeSchedulePlans()
            ->get()
            ->keyBy(fn($p) => $p->work_item_id . '_' . $p->project_week_id);

        // Build realisasi map from WeeklyReports
        $realisasiMap = [];
        $weeklyReports = $project->weeklyReports()->with(['progresses.workItem'])->get()->keyBy('week_number');

        foreach ($weeks as $week) {
            $report = $weeklyReports->get($week->week_number);
            if ($report) {
                foreach ($report->progresses as $prog) {
                    if ($prog->workItem) {
                        $bobotVal = ($prog->progress_percentage * $prog->workItem->base_bobot) / 100;
                        $realisasiMap[$prog->work_item_id][$week->id] = $bobotVal;
                    }
                }
            }
        }

        // Calculate summary rows per week
        $summary = [
            'rencana' => [],
            'rencana_komulatif' => [],
            'realisasi' => [],
            'realisasi_komulatif' => [],
            'deviasi' => [],
        ];

        $runRencana = 0;
        $runRealisasi = 0;

        foreach ($weeks as $week) {
            // Plan total for this week = sum of plans for all leaf items
            $weekPlanTotal = 0;
            foreach ($project->workItems as $wi) {
                if ($wi->type === 'item' || ($wi->type === 'main' && $wi->children->isEmpty())) {
                    $key = $wi->id . '_' . $week->id;
                    $weekPlanTotal += isset($plans[$key]) ? (float) $plans[$key]->plan_value : 0;
                }
            }
            $runRencana += $weekPlanTotal;
            $summary['rencana'][$week->id] = $weekPlanTotal;
            $summary['rencana_komulatif'][$week->id] = $runRencana;

            // Realisasi total for this week
            $hasReport = $weeklyReports->has($week->week_number);
            if ($hasReport) {
                $weekRealTotal = 0;
                foreach ($project->workItems as $wi) {
                    if ($wi->type === 'item' || ($wi->type === 'main' && $wi->children->isEmpty())) {
                        $weekRealTotal += $realisasiMap[$wi->id][$week->id] ?? 0;
                    }
                }
                $runRealisasi += $weekRealTotal;
                $summary['realisasi'][$week->id] = $weekRealTotal;
                $summary['realisasi_komulatif'][$week->id] = $runRealisasi;
                $summary['deviasi'][$week->id] = $runRealisasi - $runRencana;
            } else {
                $summary['realisasi'][$week->id] = null;
                $summary['realisasi_komulatif'][$week->id] = null;
                $summary['deviasi'][$week->id] = null;
            }
        }

        $pdf = Pdf::loadView('projects.pdf.time_schedule', compact(
            'project',
            'workItems',
            'weeks',
            'plans',
            'realisasiMap',
            'summary'
        ));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Time_Schedule_Kurva_S_' . Str::slug($project->name) . '.pdf');
    }
}
