<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'suku_bunga_pertahun',
        'suku_bunga_perbulan',
        'suku_bunga_perbulan_is_manual',
        'pemodalan',
        'provisi',
        'ppn',
        'pph_23',
        'periode_pinjaman',
    ];

    protected function casts(): array
    {
        return [
            'suku_bunga_perbulan_is_manual' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
