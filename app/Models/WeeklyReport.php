<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    protected $fillable = [
        'project_id',
        'week_number',
        'start_date',
        'end_date',
        'logo_left_path',
        'cover_layout',
    ];

    protected $casts = [
        'start_date' => 'date',
        'visual_date_1' => 'date',
        'visual_date_2' => 'date',
        'cover_layout' => 'array',
        'end_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function progresses()
    {
        return $this->hasMany(WeeklyProgress::class);
    }

    public function visuals()
    {
        return $this->hasMany(WeeklyVisual::class);
    }

    public function getTotalRealisasiAttribute()
    {
        $total = 0;
        $progresses = $this->progresses()->with('workItem')->get();
        foreach ($progresses as $progress) {
            if ($progress->workItem) {
                $total += ($progress->workItem->base_bobot * $progress->progress_percentage) / 100;
            }
        }
        return $total;
    }
}
