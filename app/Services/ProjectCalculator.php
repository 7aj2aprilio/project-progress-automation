<?php

namespace App\Services;

use App\Models\Project;
use App\Models\GlobalSetting;

class ProjectCalculator
{
    public function __construct(
        protected CashflowCalculator $cashflowCalculator
    ) {}

    /**
     * Recalculate all dynamic fields for a project.
     * Respects is_manual flags — only overwrites non-manual fields.
     */
    public function calculate(Project $project): void
    {
        $project->load([
            'information', 'costStructure', 'revenues',
            'jaminan', 'pajak', 'pinjaman', 'beban'
        ]);

        $this->calculateGuarantees($project);
        $this->calculateLoans($project);
        $this->calculateTaxes($project);

        // Recalculate Cashflow Schedule
        $this->cashflowCalculator->calculate($project);
    }

    protected function calculateGuarantees(Project $project): void
    {
        $totalRevenue = $project->total_revenue;

        foreach ($project->jaminan as $jam) {
            if (! $jam->is_manual && $jam->percentage !== null) {
                $jam->amount = $totalRevenue * ($jam->percentage / 100);
                $jam->save();
            }
        }
    }

    protected function calculateLoans(Project $project): void
    {
        $costMitra = (float) ($project->costStructure->biaya_mitra_pelaksana ?? 0);
        $totalRevenue = (float) $project->total_revenue;
        $loanBase = ($totalRevenue > 0) ? $totalRevenue : $costMitra;
        
        // Loan rate is per-project, defaults to 1.65% if not set
        $loanRate = (float) ($project->information->loan_rate ?? 1.65);
        
        // Other rates are global
        $provisiRate = (float) GlobalSetting::getValue('provisi_rate', 1);

        // Find Besar Pinjaman value for formulas
        $besarPinjamanValue = 0;
        foreach ($project->pinjaman as $pinj) {
            if (stripos($pinj->name, 'Besar Pinjaman') !== false) {
                if (! $pinj->is_manual) {
                    $pinj->amount = round($loanBase * ($loanRate / 100));
                    $pinj->save();
                }
                $besarPinjamanValue = (float) $pinj->amount;
                break;
            }
        }

        // Calculate Provisi and Bunga based on Besar Pinjaman
        foreach ($project->pinjaman as $pinj) {
            if (stripos($pinj->name, 'Biaya Provisi') !== false) {
                if (! $pinj->is_manual) {
                    $pinj->amount = round($besarPinjamanValue * ($provisiRate / 100));
                    $pinj->save();
                }
            } elseif (stripos($pinj->name, 'Bunga Pinjaman') !== false) {
                if (! $pinj->is_manual) {
                    $sukuBungaPertahun = (float) GlobalSetting::getValue('suku_bunga_pertahun', 10.89);
                    $monthlyRate = ($sukuBungaPertahun / 12) / 100;
                    $pinj->amount = round($monthlyRate * 1 * $besarPinjamanValue);
                    $pinj->save();
                }
            }
        }
    }

    protected function calculateTaxes(Project $project): void
    {
        $totalRevenue = $project->total_revenue;
        $costMitra = (float) ($project->costStructure->biaya_mitra_pelaksana ?? 0);

        $ppnRate = (float) GlobalSetting::getValue('ppn_rate', 12);
        $pphRate = (float) GlobalSetting::getValue('pph_rate', 2.65);

        foreach ($project->pajak as $tax) {
            if ($tax->is_manual) continue;

            $name = strtolower($tax->name);
            
            if (str_contains($name, 'pph') || str_contains($name, 'pasal 23')) {
                // PPh Pasal 23 = Total Revenue × PPh rate (2.65%)
                $tax->amount = round($totalRevenue * ($pphRate / 100));
                $tax->save();
            } elseif (str_contains($name, 'keluaran')) {
                // PPN Keluaran = 11/12 * PPN rate * Total Revenue
                $tax->amount = round((11 / 12) * ($ppnRate / 100) * $totalRevenue);
                $tax->save();
            } elseif (str_contains($name, 'masukan')) {
                // PPN Masukan = 11/12 * PPN rate * Biaya Mitra
                $tax->amount = round((11 / 12) * ($ppnRate / 100) * $costMitra);
                $tax->save();
            } elseif (str_contains($name, 'kredit')) {
                // Kredit PPN = PPN Keluaran - PPN Masukan
                $ppnKel = round((11 / 12) * ($ppnRate / 100) * $totalRevenue);
                $ppnMas = round((11 / 12) * ($ppnRate / 100) * $costMitra);
                $tax->amount = $ppnKel - $ppnMas;
                $tax->save();
            }
        }
    }
}
