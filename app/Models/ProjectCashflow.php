<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectCashflow extends Model
{
    use HasFactory;

    protected $table = 'project_cashflows';

    protected $fillable = [
        'project_id',
        'month_index',
        'month_date',
        'pct_progress',
        'pct_top_pelanggan',
        'pct_top_mitra',
        'jasa_konstruksi',
        'management_fee',
        'biaya_mitra',
        'fee_jaminan',
        'admin_jaminan',
        'car',
        'iuran_jasa',
        'biaya_pengawasan',
        'bop_project',
        'cash_in',
        'cash_out',
        'gross_margin',
        'pph',
        'ppn_keluaran',
        'ppn_masukan',
        'kredit_ppn',
        'penarikan_pinjaman',
        'pembayaran_pokok',
        'biaya_provisi',
        'beban_bunga',
        'cash_margin',
        'cash_flow_kumulatif',
    ];

    protected function casts(): array
    {
        return [
            'month_date' => 'date',
            'pct_progress' => 'float',
            'pct_top_pelanggan' => 'float',
            'pct_top_mitra' => 'float',
            'jasa_konstruksi' => 'float',
            'management_fee' => 'float',
            'biaya_mitra' => 'float',
            'fee_jaminan' => 'float',
            'admin_jaminan' => 'float',
            'car' => 'float',
            'iuran_jasa' => 'float',
            'biaya_pengawasan' => 'float',
            'bop_project' => 'float',
            'cash_in' => 'float',
            'cash_out' => 'float',
            'gross_margin' => 'float',
            'pph' => 'float',
            'ppn_keluaran' => 'float',
            'ppn_masukan' => 'float',
            'kredit_ppn' => 'float',
            'penarikan_pinjaman' => 'float',
            'pembayaran_pokok' => 'float',
            'biaya_provisi' => 'float',
            'beban_bunga' => 'float',
            'cash_margin' => 'float',
            'cash_flow_kumulatif' => 'float',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
