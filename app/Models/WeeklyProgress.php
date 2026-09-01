<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyProgress extends Model
{
    protected $fillable = [
        'weekly_report_id',
        'work_item_id',
        'progress_percentage',
    ];

    public function report()
    {
        return $this->belongsTo(WeeklyReport::class, 'weekly_report_id');
    }

    public function workItem()
    {
        return $this->belongsTo(WorkItem::class);
    }
}
