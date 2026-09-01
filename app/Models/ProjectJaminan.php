<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectJaminan extends Model
{
    use HasFactory;

    protected $table = 'project_jaminan';

    protected $fillable = ['project_id', 'name', 'percentage', 'amount', 'is_manual'];

    protected $casts = [
        'is_manual' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
