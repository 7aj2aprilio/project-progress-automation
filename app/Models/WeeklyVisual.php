<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyVisual extends Model
{
    protected $fillable = [
        'weekly_report_id',
        'image_path',
        'title',
        'position',
    ];

    public function report()
    {
        return $this->belongsTo(WeeklyReport::class, 'weekly_report_id');
    }
}
