<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectWeek extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'week_number',
        'start_date',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'week_number' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(TimeSchedulePlan::class);
    }

    public function getFormattedRangeAttribute(): string
    {
        if (!$this->start_date || !$this->end_date) {
            return '';
        }

        if ($this->start_date->format('Y') === $this->end_date->format('Y')) {
            return $this->start_date->format('d M') . ' - ' . $this->end_date->format('d M Y');
        }

        return $this->start_date->format('d M Y') . ' - ' . $this->end_date->format('d M Y');
    }
}
