<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    // ── Static Modules (1:1) ──────

    public function information(): HasOne
    {
        return $this->hasOne(ProjectInformation::class);
    }

    public function assumption(): HasOne
    {
        return $this->hasOne(Assumption::class);
    }

    public function costStructure(): HasOne
    {
        return $this->hasOne(CostStructure::class);
    }

    // ── Dynamic Modules (1:N) ──────

    public function beban(): HasMany
    {
        return $this->hasMany(ProjectBeban::class);
    }

    public function jaminan(): HasMany
    {
        return $this->hasMany(ProjectJaminan::class);
    }

    public function revenues(): HasMany
    {
        return $this->hasMany(ProjectRevenue::class);
    }

    public function pajak(): HasMany
    {
        return $this->hasMany(ProjectPajak::class);
    }

    public function pinjaman()
    {
        return $this->hasMany(ProjectPinjaman::class);
    }

    public function workItems()
    {
        return $this->hasMany(WorkItem::class);
    }

    public function weeklyReports()
    {
        return $this->hasMany(WeeklyReport::class);
    }

    public function ganttSchedules(): HasMany
    {
        return $this->hasMany(GanttSchedule::class);
    }

    // ── Computed Helpers ──────────────────────────────

    public function getTotalRevenueAttribute(): float
    {
        if ($this->revenues()->count() > 0) {
            return (float) $this->revenues()->sum('amount');
        }

        $info = $this->information;
        if ($info && $info->total_revenue !== null && $info->total_revenue > 0) {
            return (float) $info->total_revenue;
        }

        return 0.0;
    }

    public function getTotalCostAttribute(): float
    {
        $costMitra = $this->costStructure->biaya_mitra_pelaksana ?? 0;
        $totalBeban = $this->beban()->sum('amount');

        return (float) ($costMitra + $totalBeban);
    }

    // Aliased to Gross Margin for backward compatibility
    public function getEstimasiLabaAttribute(): float
    {
        return $this->gross_margin;
    }

    // --- NEW KELAYAKAN ATTRIBUTES ---

    public function getGrossMarginAttribute(): float
    {
        return $this->total_revenue - $this->total_cost;
    }

    public function getGrossMarginPercentageAttribute(): float
    {
        if ($this->total_revenue <= 0) return 0;
        return ($this->gross_margin / $this->total_revenue) * 100;
    }

    public function getTotalPphAttribute(): float
    {
        return (float) $this->pajak()
            ->where(function($query) {
                $query->where('name', 'like', '%PPh%')
                      ->orWhere('name', 'like', '%Pasal 23%');
            })->sum('amount');
    }

    public function getGrossMarginPphAttribute(): float
    {
        return $this->gross_margin - $this->total_pph;
    }

    public function getGrossMarginPphPercentageAttribute(): float
    {
        if ($this->total_revenue <= 0) return 0;
        return ($this->gross_margin_pph / $this->total_revenue) * 100;
    }

    public function getProvisiAttribute(): float
    {
        return (float) $this->pinjaman()
            ->where('name', 'like', '%Provisi%')->sum('amount');
    }

    public function getBungaPinjamanAttribute(): float
    {
        return (float) $this->pinjaman()
            ->where('name', 'like', '%Bunga%')->sum('amount');
    }

    public function getNetIncomeAttribute(): float
    {
        return $this->gross_margin_pph - $this->provisi - $this->bunga_pinjaman;
    }

    public function getNetIncomePercentageAttribute(): float
    {
        if ($this->total_revenue <= 0) return 0;
        return ($this->net_income / $this->total_revenue) * 100;
    }

    public function getKreditPpnAttribute(): float
    {
        $ppnKeluaran = (float) $this->pajak()->where('name', 'like', '%Keluaran%')->sum('amount');
        $ppnMasukan = (float) $this->pajak()->where('name', 'like', '%Masukan%')->sum('amount');
        return $ppnKeluaran - $ppnMasukan;
    }

    public function getNetCashFlowAttribute(): float
    {
        return $this->net_income - $this->kredit_ppn;
    }

    public function getRevenueInclPpnAttribute(): float
    {
        $ppnKeluaran = (float) $this->pajak()->where('name', 'like', '%Keluaran%')->sum('amount');
        return $this->total_revenue + $ppnKeluaran;
    }

    public function getKelayakanAttribute(): string
    {
        $threshold = (float) GlobalSetting::getValue('feasibility_threshold', 10.89);
        
        if ($this->total_revenue <= 0) {
            return 'N/A';
        }

        return $this->net_income_percentage > $threshold ? 'Layak' : 'Tidak Layak';
    }

    public function getRevenueGsdPercentageAttribute(): float
    {
        return $this->net_income_percentage; // Deprecated conceptually, alias to net income %
    }
}
