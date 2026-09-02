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
        ];

        return view('weekly_reports.project_dashboard', compact('project', 'tabs'));
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

        return redirect()->route('weekly-reports.project-dashboard', $project)->with('success', 'Laporan Mingguan berhasil dibuat.');
    }

    public function show(WeeklyReport $weeklyReport)
    {
        $project = $weeklyReport->project;
        
        // Eager load work items with children
        $workItems = $project->workItems()->whereNull('parent_id')->with('children.children')->get();
        
        $progresses = $weeklyReport->progresses()->pluck('progress_percentage', 'work_item_id')->toArray();
        $previousReport = $project->weeklyReports()->where('week_number', '<', $weeklyReport->week_number)->orderBy('week_number', 'desc')->first();
        $visuals = $weeklyReport->visuals;
        
        return view('weekly_reports.show', compact('weeklyReport', 'project', 'workItems', 'progresses', 'previousReport', 'visuals'));
    }

    public function update(Request $request, WeeklyReport $weeklyReport)
    {
        $request->validate([
            'progress' => 'nullable|array',
            'progress.*' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($request->has('progress')) {
            foreach ($request->input('progress') as $workItemId => $progressPercentage) {
                // Update or create progress for this week and work item
                $weeklyReport->progresses()->updateOrCreate(
                    ['work_item_id' => $workItemId],
                    ['progress_percentage' => $progressPercentage ?? 0]
                );
            }
        }

        return redirect()->route('weekly-reports.project-dashboard', ['project' => $weeklyReport->project_id, 'tab' => 'weekly_reports'])
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

        for ($i = 1; $i <= 8; $i++) {
            $imageKey = 'visual_image_' . $i;
            $titleKey = 'visual_title_' . $i;
            $idKey = 'visual_id_' . $i;
            $deleteKey = 'delete_visual_' . $i;

            if ($request->hasFile($imageKey)) {
                $request->validate([
                    $imageKey => 'image|mimes:jpeg,png,jpg,gif|max:5120',
                ]);
            }

            $visualData = [
                'title' => $request->input($titleKey),
                'position' => $i,
            ];

            if ($request->hasFile($imageKey)) {
                $file = $request->file($imageKey);
                $filename = time() . '_' . $weeklyReport->id . '_' . $i . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/visuals'), $filename);
                $visualData['image_path'] = 'uploads/visuals/' . $filename;
            }

            if ($request->filled($idKey)) {
                // Update existing
                $visual = \App\Models\WeeklyVisual::find($request->input($idKey));
                if ($visual) {
                    if ($request->filled($deleteKey)) {
                        // Handle deletion
                        if ($visual->image_path && file_exists(public_path($visual->image_path))) {
                            unlink(public_path($visual->image_path));
                        }
                        $visual->delete();
                        continue;
                    }
                    $visual->update($visualData);
                }
            } elseif (isset($visualData['image_path'])) {
                // Create new only if there's an image uploaded
                $weeklyReport->visuals()->create($visualData);
            }
        }

        return redirect()->route('weekly-reports.project-dashboard', ['project' => $weeklyReport->project_id, 'tab' => 'weekly_reports'])
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
        
        return $pdf->stream('Laporan_Mingguan_Ke_' . $weeklyReport->week_number . '_' . $project->name . '.pdf');
    }
}
