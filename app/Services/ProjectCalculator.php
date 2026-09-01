<?php

namespace App\Services;

use App\Models\Project;
use App\Models\GlobalSetting;

class ProjectCalculator
{
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
        $costMitra = $project->costStructure->biaya_mitra_pelaksana ?? 0;
        
        // Loan rate is per-project, defaults to 1.65% if not set
        $loanRate = $project->information->loan_rate ?? 1.65;
        
        // Other rates are global
        $provisiRate = GlobalSetting::getValue('provisi_rate', 1);
        $sukuBungaPerbulan = GlobalSetting::getValue('suku_bunga_perbulan', 1);

        // Find Besar Pinjaman value for formulas
        $besarPinjamanValue = 0;
        foreach ($project->pinjaman as $pinj) {
            if (stripos($pinj->name, 'Besar Pinjaman') !== false) {
                if (! $pinj->is_manual) {
                    $pinj->amount = $costMitra * ($loanRate / 100);
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
                    $pinj->amount = $besarPinjamanValue * ($provisiRate / 100);
                    $pinj->save();
                }
            } elseif (stripos($pinj->name, 'Bunga Pinjaman') !== false) {
                if (! $pinj->is_manual) {
                    $sukuBungaPertahun = GlobalSetting::getValue('suku_bunga_pertahun', 12);
                    $monthlyRate = $sukuBungaPertahun / 12 / 100;
                    $pinj->amount = $monthlyRate * 1 * $besarPinjamanValue;
                    $pinj->save();
                }
            }
        }
    }

    protected function calculateTaxes(Project $project): void
    {
        $totalRevenue = $project->total_revenue;
        $totalCost = $project->total_cost;

        $ppnRate = GlobalSetting::getValue('ppn_rate', 11);
        $pphRate = GlobalSetting::getValue('pph_rate', 2);

        foreach ($project->pajak as $tax) {
            if ($tax->is_manual) continue;

            $name = strtolower($tax->name);
            
            if (str_contains($name, 'pph') || str_contains($name, 'pasal 23')) {
                // PPh Pasal 23 = Total Revenue × PPh rate
                $tax->amount = $totalRevenue * ($pphRate / 100);
                $tax->save();
            } elseif (str_contains($name, 'keluaran')) {
                // PPN Keluaran = 11/12 * PPN rate * Total Revenue
                $tax->amount = (11 / 12) * ($ppnRate / 100) * $totalRevenue;
                $tax->save();
            } elseif (str_contains($name, 'masukan')) {
                // PPN Masukan = 11/12 * PPN rate * Total Cost
                $tax->amount = (11 / 12) * ($ppnRate / 100) * $totalCost;
                $tax->save();
            }
        }
    }
}
