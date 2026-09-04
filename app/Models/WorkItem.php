<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkItem extends Model
{
    protected $fillable = [
        'project_id',
        'parent_id',
        'type',
        'name',
        'volume',
        'unit',
        'unit_price',
        'total_price',
        'keterangan',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function parent()
    {
        return $this->belongsTo(WorkItem::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(WorkItem::class, 'parent_id');
    }

    public function ganttSchedules(): HasMany
    {
        return $this->hasMany(GanttSchedule::class);
    }

    public function timeSchedulePlans(): HasMany
    {
        return $this->hasMany(TimeSchedulePlan::class);
    }

    public function getBaseBobotAttribute()
    {
        // Total project price is sum of all main items
        $projectTotal = WorkItem::where('project_id', $this->project_id)->where('type', 'main')->get()->sum('total_price');
        
        if ($projectTotal == 0) return 0;
        return ($this->total_price / $projectTotal) * 100;
    }

    public function getTotalPriceAttribute($value)
    {
        if ($value !== null) {
            return $value;
        }

        if ($this->type === 'item') {
            return ($this->volume ?? 0) * ($this->unit_price ?? 0);
        }

        return $this->children->sum('total_price');
    }

    public function weeklyProgresses()
    {
        return $this->hasMany(WeeklyProgress::class);
    }
}
