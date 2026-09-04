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

    public function cashflows(): HasMany
    {
        return $this->hasMany(ProjectCashflow::class)->orderBy('month_index');
    }

    public function weeks(): HasMany
    {
        return $this->hasMany(ProjectWeek::class)->orderBy('week_number');
    }

    public function timeSchedulePlans(): HasMany
    {
        return $this->hasMany(TimeSchedulePlan::class);
    }

    // ── Computed Helpers ──────────────────────────────

    // ── Computed Helpers (Derived from CASHFLOW PROJECT as Primary Source) ────

    public function getTotalRevenueAttribute(): float
    {
        if ($this->cashflows()->count() > 0 && ($cfSum = (float) $this->cashflows()->sum('cash_in')) > 0) {
            return $cfSum;
        }

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
        if ($this->cashflows()->count() > 0 && ($cfSum = (float) $this->cashflows()->sum('cash_out')) > 0) {
            return $cfSum;
        }

        $costMitra = $this->costStructure->biaya_mitra_pelaksana ?? 0;
        $totalBeban = $this->beban()->sum('amount');

        return (float) ($costMitra + $totalBeban);
    }

    // Aliased to Gross Margin for backward compatibility
    public function getEstimasiLabaAttribute(): float
    {
        return $this->gross_margin;
    }

    // --- KELAYAKAN ATTRIBUTES (PULLED FROM CASHFLOW PROJECT) ---

    public function getGrossMarginAttribute(): float
    {
        if ($this->cashflows()->count() > 0 && $this->cashflows()->sum('cash_in') > 0) {
            return (float) $this->cashflows()->sum('gross_margin');
        }

        return $this->total_revenue - $this->total_cost;
    }

    public function getGrossMarginPercentageAttribute(): float
    {
        if ($this->total_revenue <= 0) return 0;
        return ($this->gross_margin / $this->total_revenue) * 100;
    }

    public function getTotalPphAttribute(): float
    {
        if ($this->cashflows()->count() > 0 && $this->cashflows()->sum('cash_in') > 0) {
            return (float) $this->cashflows()->sum('pph');
        }

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
        if ($this->cashflows()->count() > 0 && $this->cashflows()->sum('cash_in') > 0) {
            return (float) $this->cashflows()->sum('biaya_provisi');
        }

        return (float) $this->pinjaman()
            ->where('name', 'like', '%Provisi%')->sum('amount');
    }

    public function getBungaPinjamanAttribute(): float
    {
        if ($this->cashflows()->count() > 0 && $this->cashflows()->sum('cash_in') > 0) {
            return (float) $this->cashflows()->sum('beban_bunga');
        }

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
        if ($this->cashflows()->count() > 0 && $this->cashflows()->sum('cash_in') > 0) {
            return (float) $this->cashflows()->sum('kredit_ppn');
        }

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
        $ppnKeluaran = $this->cashflows()->count() > 0 ? (float) $this->cashflows()->sum('ppn_keluaran') : (float) $this->pajak()->where('name', 'like', '%Keluaran%')->sum('amount');
        return $this->total_revenue + $ppnKeluaran;
    }

    public function getHasCfNegatifAttribute(): bool
    {
        return $this->cashflows()->where('cash_flow_kumulatif', '<', 0)->exists();
    }

    public function getKelayakanAttribute(): string
    {
        $threshold = (float) GlobalSetting::getValue('feasibility_threshold', 10.89);
        
        if ($this->total_revenue <= 0) {
            return 'N/A';
        }

        if ($this->has_cf_negatif) {
            return 'Tidak Layak';
        }

        return $this->net_income_percentage >= $threshold ? 'Layak' : 'Tidak Layak';
    }

    public function getKesimpulanKelayakanDetailAttribute(): string
    {
        $threshold = (float) GlobalSetting::getValue('feasibility_threshold', 10.89);

        if ($this->total_revenue <= 0) {
            return 'Belum ada data revenue';
        }

        if ($this->has_cf_negatif) {
            return 'Tidak Layak dengan Terdapat CF Negatif';
        }

        if ($this->net_income_percentage < $threshold) {
            return 'Tidak Layak dengan Net Income dibawah treshold (' . number_format($threshold, 2) . '%)';
        }

        return 'Layak dengan Net Income sebesar ' . number_format($this->net_income_percentage, 2) . '%';
    }

    public function getRevenueGsdPercentageAttribute(): float
    {
        return $this->net_income_percentage; // Deprecated conceptually, alias to net income %
    }
}
