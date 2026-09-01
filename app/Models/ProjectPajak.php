<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPajak extends Model
{
    use HasFactory;

    protected $table = 'project_pajak';

    protected $fillable = ['project_id', 'name', 'amount', 'is_manual'];

    protected $casts = [
        'is_manual' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
