<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\WorkItem;
use Illuminate\Http\Request;

class WorkItemController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:work_items,id',
            'type' => 'required|in:main,sub,item',
            'name' => 'required|string|max:255',
            'volume' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $project->workItems()->create($validated);

        return redirect()->back()->with('success', 'Rincian pekerjaan berhasil ditambahkan.');
    }

    public function update(Request $request, WorkItem $workItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'volume' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'unit_price' => 'nullable|numeric|min:0',
        ]);

        $workItem->update($validated);

        return redirect()->back()->with('success', 'Rincian pekerjaan berhasil diubah.');
    }

    public function destroy(WorkItem $workItem)
    {
        // cascade deletion should handle children if configured in DB, but just in case:
        $workItem->children()->delete();
        $workItem->delete();

        return redirect()->back()->with('success', 'Rincian pekerjaan berhasil dihapus.');
    }
}
