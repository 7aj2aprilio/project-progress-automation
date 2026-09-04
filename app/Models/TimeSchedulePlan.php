<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeSchedulePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'work_item_id',
        'project_week_id',
        'plan_value',
    ];

    protected $casts = [
        'plan_value' => 'float',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function workItem(): BelongsTo
    {
        return $this->belongsTo(WorkItem::class);
    }

    public function week(): BelongsTo
    {
        return $this->belongsTo(ProjectWeek::class, 'project_week_id');
    }
}
