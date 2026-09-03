<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\WeeklyReport;
use App\Models\WeeklyProgress;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class WeeklyReportController extends Controller
{
    public function projects()
    {
        $projects = Project::with(['information'])->orderBy('created_at', 'desc')->get();
        return view('weekly_reports.index', compact('projects'));
    }

    public function projectDashboard(Project $project)
    {
        $tabs = [
            'boq' => '1. Rincian Pekerjaan (BoQ)',
            'weekly_reports' => '2. Laporan Mingguan',
            'jadwal' => '3. Time Schedule (Gantt Chart)',
        ];

        // Eager load work items for hierarchical display
        $workItems = $project->workItems()->whereNull('parent_id')->with('children.children')->get();

        // Eager load weeklyReports and their progresses
        $project->load(['weeklyReports' => function($query) {
            $query->orderBy('week_number', 'asc');
        }, 'weeklyReports.progresses', 'ganttSchedules']);

        // Format gantt schedules for easy checking in view
        $ganttData = $project->ganttSchedules->map(function($s) {
            return $s->work_item_id . '_' . $s->month_year . '_' . $s->week;
        })->toArray();

        // Calculate timeline (months and weeks)
        $projectMonths = [];
        $startDate = $project->information && $project->information->estimasi_mulai ? \Carbon\Carbon::parse($project->information->estimasi_mulai)->startOfDay() : null;
        $endDate = $project->information && $project->information->estimasi_selesai ? \Carbon\Carbon::parse($project->information->estimasi_selesai)->endOfDay() : null;
        
        if ($startDate && $endDate && $endDate->greaterThanOrEqualTo($startDate)) {
            // Get all months involved
            $period = \Carbon\CarbonPeriod::create($startDate->copy()->startOfMonth(), '1 month', $endDate->copy()->startOfMonth());
            
            foreach ($period as $month) {
                // Calculate weeks based on physical calendar placement (Sunday as start of week)
                $firstDay = $month->copy()->startOfMonth();
                $lastDay = $month->copy()->endOfMonth();
                
                // dayOfWeek returns 0 for Sunday, 1 for Monday... 6 for Saturday.
                $offset = $firstDay->dayOfWeek; 
                $totalDays = $lastDay->day + $offset;
                
                $weeksCount = ceil($totalDays / 7);
                
                if ($weeksCount > 0) {
                    $projectMonths[] = [
                        'key' => $month->format('Y-m'),
                        'name' => $month->format('F Y'),
                        'weeks_count' => $weeksCount,
                    ];
                }
            }
        }

        return view('weekly_reports.project_dashboard', compact('project', 'tabs', 'workItems', 'projectMonths', 'ganttData'));
    }

    public function create(Project $project)
    {
        return view('weekly_reports.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'week_number' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $report = $project->weeklyReports()->create($validated);

        return redirect()->route('weekly-reports.project-dashboard', ['project' => $project->id, 'tab' => 'weekly_reports'])->with('success', 'Laporan Mingguan berhasil dibuat.');
    }

    public function show(WeeklyReport $weeklyReport)
    {
        $project = $weeklyReport->project;
        
        // Eager load work items with children
        $workItems = $project->workItems()->whereNull('parent_id')->with('children.children')->get();
        
        $progresses = $weeklyReport->progresses()->pluck('progress_percentage', 'work_item_id')->toArray();
        $previousReport = $project->weeklyReports()->where('week_number', '<', $weeklyReport->week_number)->orderBy('week_number', 'desc')->first();
        $previousProgresses = $previousReport ? $previousReport->progresses()->pluck('progress_percentage', 'work_item_id')->toArray() : [];
        $visuals = $weeklyReport->visuals;
        
        return view('weekly_reports.show', compact('weeklyReport', 'project', 'workItems', 'progresses', 'previousReport', 'previousProgresses', 'visuals'));
    }

    public function update(Request $request, WeeklyReport $weeklyReport)
    {
        $request->validate([
            'progress' => 'nullable|array',
            'progress.*' => 'nullable|numeric|min:0|max:100',
        ]);

        $project = $weeklyReport->project;
        $previousReport = $project->weeklyReports()->where('week_number', '<', $weeklyReport->week_number)->orderBy('week_number', 'desc')->first();
        $previousProgresses = $previousReport ? $previousReport->progresses()->pluck('progress_percentage', 'work_item_id')->toArray() : [];

        if ($request->has('progress')) {
            foreach ($request->input('progress') as $workItemId => $progressPercentage) {
                $progressPercentage = $progressPercentage === null ? 0 : (float) $progressPercentage;
                $prev = $previousProgresses[$workItemId] ?? 0;
                
                if ($progressPercentage < $prev) {
                    return back()->withErrors(['progress.' . $workItemId => 'Progress tidak boleh lebih kecil dari minggu sebelumnya (' . $prev . '%).'])->withInput();
                }

                // Update or create progress for this week and work item
                $weeklyReport->progresses()->updateOrCreate(
                    ['work_item_id' => $workItemId],
                    ['progress_percentage' => $progressPercentage]
                );
            }
        }

        return redirect()->route('weekly-reports.show', ['weeklyReport' => $weeklyReport->id, 'tab' => 'progress'])
            ->with('success', 'Progress berhasil disimpan.');
    }

    public function storeVisuals(Request $request, WeeklyReport $weeklyReport)
    {
        // Handle Logo Kiri
        if ($request->hasFile('logo_left')) {
            $request->validate([
                'logo_left' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            ]);
            
            if ($weeklyReport->logo_left_path && file_exists(public_path($weeklyReport->logo_left_path))) {
                unlink(public_path($weeklyReport->logo_left_path));
            }
            
            $file = $request->file('logo_left');
            $filename = time() . '_logo_' . $weeklyReport->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logos'), $filename);
            $weeklyReport->update(['logo_left_path' => 'uploads/logos/' . $filename]);
        } elseif ($request->filled('delete_logo') && $weeklyReport->logo_left_path) {
            if (file_exists(public_path($weeklyReport->logo_left_path))) {
                unlink(public_path($weeklyReport->logo_left_path));
            }
            $weeklyReport->update(['logo_left_path' => null]);
        }

        // Update existing visuals
        if ($request->has('existing_visual_ids')) {
            foreach ($request->input('existing_visual_ids') as $id) {
                $visual = \App\Models\WeeklyVisual::find($id);
                if ($visual && $visual->weekly_report_id == $weeklyReport->id) {
                    if ($request->has("delete_visuals.{$id}")) {
                        if ($visual->image_path && file_exists(public_path($visual->image_path))) {
                            unlink(public_path($visual->image_path));
                        }
                        $visual->delete();
                        continue;
                    }

                    $updateData = [];
                    if ($request->has("existing_visual_titles.{$id}")) {
                        $updateData['title'] = $request->input("existing_visual_titles.{$id}");
                    }

                    if ($request->hasFile("existing_visual_images.{$id}")) {
                        $request->validate([
                            "existing_visual_images.{$id}" => 'image|mimes:jpeg,png,jpg,gif|max:5120',
                        ]);
                        $file = $request->file("existing_visual_images.{$id}");
                        $filename = time() . '_' . $weeklyReport->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('uploads/visuals'), $filename);
                        
                        if ($visual->image_path && file_exists(public_path($visual->image_path))) {
                            unlink(public_path($visual->image_path));
                        }
                        $updateData['image_path'] = 'uploads/visuals/' . $filename;
                    }

                    if (!empty($updateData)) {
                        $visual->update($updateData);
                    }
                }
            }
        }

        // Add new visuals
        if ($request->hasFile('new_visual_images')) {
            $newImages = $request->file('new_visual_images');
            $newTitles = $request->input('new_visual_titles', []);
            
            foreach ($newImages as $index => $file) {
                $request->validate([
                    "new_visual_images.{$index}" => 'image|mimes:jpeg,png,jpg,gif|max:5120',
                ]);
                
                $filename = time() . '_' . $weeklyReport->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/visuals'), $filename);
                
                $weeklyReport->visuals()->create([
                    'title' => $newTitles[$index] ?? '',
                    'image_path' => 'uploads/visuals/' . $filename,
                    'position' => 0,
                ]);
            }
        }

        // Re-order positions to ensure they are sequential
        $currentVisuals = $weeklyReport->visuals()->orderBy('id')->get();
        foreach ($currentVisuals as $i => $visual) {
            $visual->update(['position' => $i + 1]);
        }

        return redirect()->route('weekly-reports.show', ['weeklyReport' => $weeklyReport->id, 'tab' => 'visual'])
            ->with('success', 'Laporan visual berhasil disimpan.');
    }

    public function saveCoverLayout(Request $request, WeeklyReport $weeklyReport)
    {
        $request->validate([
            'cover_layout' => 'required|array',
        ]);

        $weeklyReport->update([
            'cover_layout' => $request->input('cover_layout'),
        ]);

        return response()->json(['success' => true, 'message' => 'Cover layout saved successfully.']);
    }

    public function downloadPdf(WeeklyReport $weeklyReport)
    {
        $project = $weeklyReport->project;
        $workItems = $project->workItems()->whereNull('parent_id')->with('children.children')->get();
        $previousReport = $project->weeklyReports()->where('week_number', '<', $weeklyReport->week_number)->orderBy('week_number', 'desc')->first();

        $progresses = $weeklyReport->progresses()->pluck('progress_percentage', 'work_item_id')->toArray();
        $previousProgresses = $previousReport ? $previousReport->progresses()->pluck('progress_percentage', 'work_item_id')->toArray() : [];
        $visuals = $weeklyReport->visuals->keyBy('position');

        // Calculate actual percentages before rendering
        $pdf = Pdf::loadView('reports.weekly_pdf', compact('weeklyReport', 'project', 'workItems', 'previousReport', 'progresses', 'previousProgresses', 'visuals'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('Laporan_Mingguan_Ke_' . $weeklyReport->week_number . '_' . $project->name . '.pdf');
    }

    public function exportGanttPdf(Project $project)
    {
        $workItems = $project->workItems()->whereNull('parent_id')->with('children.children')->get();
        
        $ganttData = $project->ganttSchedules->map(function($s) {
            return $s->work_item_id . '_' . $s->month_year . '_' . $s->week;
        })->toArray();

        $projectMonths = [];
        $startDate = $project->information && $project->information->estimasi_mulai ? \Carbon\Carbon::parse($project->information->estimasi_mulai)->startOfDay() : null;
        $endDate = $project->information && $project->information->estimasi_selesai ? \Carbon\Carbon::parse($project->information->estimasi_selesai)->endOfDay() : null;
        
        if ($startDate && $endDate && $endDate->greaterThanOrEqualTo($startDate)) {
            $period = \Carbon\CarbonPeriod::create($startDate->copy()->startOfMonth(), '1 month', $endDate->copy()->startOfMonth());
            foreach ($period as $month) {
                $firstDay = $month->copy()->startOfMonth();
                $lastDay = $month->copy()->endOfMonth();
                $offset = $firstDay->dayOfWeek; 
                $totalDays = $lastDay->day + $offset;
                $weeksCount = ceil($totalDays / 7);
                if ($weeksCount > 0) {
                    $projectMonths[] = [
                        'key' => $month->format('Y-m'),
                        'name' => $month->format('F Y'),
                        'weeks_count' => $weeksCount,
                    ];
                }
            }
        }

        $pdf = Pdf::loadView('projects.pdf.gantt', compact('project', 'workItems', 'projectMonths', 'ganttData'));
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Time_Schedule_' . $project->name . '.pdf');
    }
}
