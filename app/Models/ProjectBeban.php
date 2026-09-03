<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectBeban extends Model
{
    use HasFactory;

    protected $table = 'project_beban';

    protected $fillable = ['project_id', 'name', 'amount', 'is_manual'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
