<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRevenue extends Model
{
    use HasFactory;

    protected $table = 'project_revenues';

    protected $fillable = ['project_id', 'name', 'amount'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
