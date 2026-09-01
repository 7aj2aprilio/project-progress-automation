<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectInformation extends Model
{
    use HasFactory;

    protected $table = 'project_informations';

    protected $fillable = [
        'project_id',
        'nama_pelanggan',
        'kategori_project',
        'nama_project',
        'lokasi_project',
        'estimasi_mulai',
        'durasi_project',
        'durasi_retensi',
        'pengawasan_konstruksi',
        'tipe_bangunan',
        'top_pembayaran_mitra',
        'top_pembayaran_pelanggan',
    ];

    protected function casts(): array
    {
        return [
            'estimasi_mulai' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
