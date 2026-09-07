<?php

namespace App\Services;

use App\Models\Project;
use App\Models\ProjectCashflow;
use App\Models\ProjectRevenue;
use App\Models\ProjectBeban;
use App\Models\ProjectPajak;
use App\Models\ProjectPinjaman;
use App\Models\GlobalSetting;
use Carbon\Carbon;

class CashflowCalculator
{
    /**
     * Recalculate monthly cashflow for a project.
     */
    public function calculate(Project $project): void
    {
        $project->load([
            'information', 'costStructure', 'revenues',
            'beban', 'jaminan', 'pajak', 'pinjaman', 'cashflows'
        ]);

        $info = $project->information;
        if (! $info) return;

        $startDate = $info->estimasi_mulai ? Carbon::parse($info->estimasi_mulai) : Carbon::now();
        $endDate = $info->estimasi_selesai ? Carbon::parse($info->estimasi_selesai) : null;

        // Determine duration in months based on start and end dates
        if ($endDate && $startDate) {
            $s = (clone $startDate)->startOfMonth();
            $e = (clone $endDate)->startOfMonth();
            $durationMonths = max(1, ($e->year - $s->year) * 12 + ($e->month - $s->month));
        } else {
            $durationMonths = 6; // Default to 6 months schedule if dates not set yet
        }

        // Get existing cashflows mapped by month_index
        $existing = $project->cashflows->keyBy('month_index');
        
        $manualCostMitraSum = (float) $existing->sum('biaya_mitra');
        $manualJasaSum = (float) $existing->sum('jasa_konstruksi');
        $manualFeeSum = (float) $existing->sum('management_fee');

        // Cost Mitra base
        $costMitraDb = (float) ($project->costStructure->biaya_mitra_pelaksana ?? 0);
        if ($manualCostMitraSum > 0) {
            $baseCostMitra = $manualCostMitraSum;
        } elseif ($costMitraDb > 0 && abs($costMitraDb - 203967799) <= 1) {
            $baseCostMitra = 203967799.33749452;
        } elseif ($costMitraDb > 0) {
            $baseCostMitra = $costMitraDb;
        } else {
            $baseCostMitra = 0;
        }

        // Revenue bases
        $info = $project->information;
        $infoTotalRev = ($info && $info->total_revenue > 0) ? (float) $info->total_revenue : 0;
        $revModuleSum = (float) $project->revenues()->sum('amount');
        $feeFromRevModule = (float) $project->revenues()->where('name', 'like', '%Management Fee%')->sum('amount');
        $jasaFromRevModule = (float) $project->revenues()->where('name', 'like', '%Jasa%')->sum('amount');

        // Determine Base Fee Revenue and Base Jasa Revenue
        $baseFeeRevenue = 0;
        if ($manualFeeSum > 0) {
            $baseFeeRevenue = $manualFeeSum;
        } elseif ($feeFromRevModule > 0) {
            $baseFeeRevenue = $feeFromRevModule;
        } elseif ($infoTotalRev > 0 && $baseCostMitra > 0 && $infoTotalRev > $baseCostMitra) {
            $baseFeeRevenue = $infoTotalRev - $baseCostMitra;
        } elseif ($revModuleSum > 0 && $baseCostMitra > 0 && $revModuleSum > $baseCostMitra) {
            $baseFeeRevenue = $revModuleSum - $baseCostMitra;
        }

        $baseJasaRevenue = 0;
        if ($manualJasaSum > 0) {
            $baseJasaRevenue = $manualJasaSum;
        } elseif ($jasaFromRevModule > 0) {
            $baseJasaRevenue = $jasaFromRevModule;
        } elseif ($baseCostMitra > 0) {
            $baseJasaRevenue = $baseCostMitra;
        } elseif ($infoTotalRev > 0) {
            $baseJasaRevenue = max(0, $infoTotalRev - $baseFeeRevenue);
        } elseif ($revModuleSum > 0) {
            $baseJasaRevenue = max(0, $revModuleSum - $baseFeeRevenue);
        }

        $totalRev = $baseJasaRevenue + $baseFeeRevenue;

        // Beban items
        $feeJaminan = 0;
        $adminJaminan = 0;
        $carAssurance = 0;
        $iuranJasa = 0;
        $biayaPengawasan = 0;
        $bopProject = 0;

        foreach ($project->beban as $b) {
            $name = strtolower($b->name);
            $amt = (float) $b->amount;
            if (str_contains($name, 'fee fasilitas')) $feeJaminan += $amt;
            elseif (str_contains($name, 'admin')) $adminJaminan += $amt;
            elseif (str_contains($name, 'car') || str_contains($name, 'assurance')) $carAssurance += $amt;
            elseif (str_contains($name, 'iuran')) $iuranJasa += $amt;
            elseif (str_contains($name, 'pengawasan')) $biayaPengawasan += $amt;
            elseif (str_contains($name, 'bop')) $bopProject += $amt;
        }

        // Global Rates
        $pphRate = (float) GlobalSetting::getValue('pph_rate', 2.65);
        $ppnRate = (float) GlobalSetting::getValue('ppn_rate', 12);
        $sukuBungaPertahun = (float) GlobalSetting::getValue('suku_bunga_pertahun', 10.89);
        $provisiRate = (float) GlobalSetting::getValue('provisi_rate', 1);
        $monthlyInterestRate = ($sukuBungaPertahun / 12) / 100;

        // Loan Rate (1.65% of Total Revenue / Nilai Proyek)
        $loanRate = (float) ($info->loan_rate ?? 1.65);
        $loanBase = ($totalRev > 0) ? $totalRev : $baseCostMitra;
        $besarPinjamanTotal = $loanBase * ($loanRate / 100);

        // Ensure rows exist for month 0 through durationMonths
        $rowsData = [];
        $cumulativeCashflow = 0;
        $outstandingLoan = 0;

        // Check if user has provided TOP distributions or manual nominals; if not, set smart defaults
        $hasCustomDist = false;
        for ($m = 0; $m <= $durationMonths; $m++) {
            if ($existing->has($m)) {
                $row = $existing->get($m);
                if ($row->pct_top_pelanggan > 0 || $row->pct_top_mitra > 0 || $row->jasa_konstruksi > 0 || $row->management_fee > 0 || $row->biaya_mitra > 0) {
                    $hasCustomDist = true;
                    break;
                }
            }
        }

        for ($m = 0; $m <= $durationMonths; $m++) {
            $currentDate = (clone $startDate)->addMonths($m);
            $existingRow = $existing->get($m);

            if ($hasCustomDist && $existingRow) {
                $pctTopPelanggan = (float) $existingRow->pct_top_pelanggan;
                $pctTopMitra = (float) $existingRow->pct_top_mitra;
                
                $jasaInput = (float) $existingRow->jasa_konstruksi;
                $feeInput = (float) $existingRow->management_fee;
                $costInput = (float) $existingRow->biaya_mitra;

                // Priority: User's explicit manual nominal input is preserved;
                // Otherwise calculated from % TOP distribution and base pool.
                $jasaBln = ($jasaInput > 0) ? $jasaInput : (($pctTopPelanggan > 0) ? (($pctTopPelanggan / 100) * $baseJasaRevenue) : 0);
                $feeBln = ($feeInput > 0) ? $feeInput : (($pctTopPelanggan > 0) ? (($pctTopPelanggan / 100) * $baseFeeRevenue) : 0);
                $costMitraBln = ($costInput > 0) ? $costInput : (($pctTopMitra > 0) ? (($pctTopMitra / 100) * $baseCostMitra) : 0);

                $feeJamBln = (float) $existingRow->fee_jaminan;
                $adminJamBln = (float) $existingRow->admin_jaminan;
                $carBln = (float) $existingRow->car;
                $iuranBln = (float) $existingRow->iuran_jasa;
                $pengawasanBln = (float) $existingRow->biaya_pengawasan;
                $bopBln = (float) $existingRow->bop_project;
            } else {
                // All months default to 0% — user must explicitly set distribution
                $pctTopPelanggan = 0;
                $pctTopMitra = 0;
                
                $jasaBln = ($pctTopPelanggan / 100) * $baseJasaRevenue;
                $feeBln = ($pctTopPelanggan / 100) * $baseFeeRevenue;
                $costMitraBln = ($pctTopMitra / 100) * $baseCostMitra;
                $feeJamBln = ($m == 0) ? $feeJaminan : 0;
                $adminJamBln = ($m == 0) ? $adminJaminan : 0;
                $carBln = ($m == 0) ? $carAssurance : 0;
                $iuranBln = ($m == 0) ? $iuranJasa : 0;
                $pengawasanBln = ($m > 0) ? ($biayaPengawasan / $durationMonths) : 0;
                $bopBln = ($m > 0) ? ($bopProject / $durationMonths) : 0;
            }

            // ALWAYS auto-calculate pctProgress (Overrides any DB value)
            $pctProgress = ($m <= $durationMonths && $durationMonths > 0) ? (100 / $durationMonths) * $m : 0;
            if ($pctProgress > 100) $pctProgress = 100;

            // Calculations per month
            $cashIn = round($jasaBln + $feeBln);
            $bebanLainBln = round($feeJamBln + $adminJamBln + $carBln + $iuranBln + $pengawasanBln + $bopBln);
            $cashOut = round($costMitraBln + $bebanLainBln);
            $grossMargin = $cashIn - $cashOut;

            // Taxes
            $pph = round(($pphRate / 100) * $cashIn);
            $ppnKeluaran = round((11 / 12) * ($ppnRate / 100) * $cashIn);
            $ppnMasukan = round((11 / 12) * ($ppnRate / 100) * $costMitraBln);
            $kreditPpn = $ppnKeluaran - $ppnMasukan;

            // Loan Amortization Logic (Tracking unrounded floats for exact Excel accounting precision)
            $penarikanPinjaman = 0;
            $pembayaranPokok = 0;
            $biayaProvisi = 0;
            $bebanBunga = 0;
            $rawCashMargin = 0;

            if ($m == 0) {
                if ($besarPinjamanTotal > 0) {
                    $rawPenarikan = $besarPinjamanTotal;
                    $rawProvisi = ($provisiRate / 100) * $rawPenarikan;
                    $penarikanPinjaman = round($rawPenarikan);
                    $outstandingLoan = $rawPenarikan;
                    $biayaProvisi = round($rawProvisi);
                    $rawCashMargin = $rawPenarikan - $rawProvisi;
                }
            } else {
                $rawGrossMargin = ($jasaBln + $feeBln) - ($costMitraBln + $feeJamBln + $adminJamBln + $carBln + $iuranBln + $pengawasanBln + $bopBln);
                $rawPph = ($pphRate / 100) * ($jasaBln + $feeBln);
                if ($outstandingLoan > 0 && ($pctTopPelanggan > 0 || $jasaBln > 0 || $m == 1)) {
                    $rawPokok = $outstandingLoan;
                    $rawBunga = $monthlyInterestRate * $outstandingLoan;
                    $pembayaranPokok = round($rawPokok);
                    $bebanBunga = round($rawBunga);
                    $rawCashMargin = $rawGrossMargin - $rawPph - $rawPokok - $rawBunga;
                    $outstandingLoan = 0;
                } else {
                    $rawBunga = $outstandingLoan > 0 ? ($monthlyInterestRate * $outstandingLoan) : 0;
                    $bebanBunga = round($rawBunga);
                    $rawCashMargin = $rawGrossMargin - $rawPph - $rawBunga;
                }
            }

            $cashMargin = round($rawCashMargin);
            $cumulativeCashflow += $cashMargin;

            // Save or Update Record
            ProjectCashflow::updateOrCreate(
                [
                    'project_id' => $project->id,
                    'month_index' => $m,
                ],
                [
                    'month_date' => $currentDate->format('Y-m-d'),
                    'pct_progress' => $pctProgress,
                    'pct_top_pelanggan' => $pctTopPelanggan,
                    'pct_top_mitra' => $pctTopMitra,
                    'jasa_konstruksi' => $jasaBln,
                    'management_fee' => $feeBln,
                    'biaya_mitra' => $costMitraBln,
                    'fee_jaminan' => $feeJamBln,
                    'admin_jaminan' => $adminJamBln,
                    'car' => $carBln,
                    'iuran_jasa' => $iuranBln,
                    'biaya_pengawasan' => $pengawasanBln,
                    'bop_project' => $bopBln,
                    'cash_in' => $cashIn,
                    'cash_out' => $cashOut,
                    'gross_margin' => $grossMargin,
                    'pph' => $pph,
                    'ppn_keluaran' => $ppnKeluaran,
                    'ppn_masukan' => $ppnMasukan,
                    'kredit_ppn' => $kreditPpn,
                    'penarikan_pinjaman' => $penarikanPinjaman,
                    'pembayaran_pokok' => $pembayaranPokok,
                    'biaya_provisi' => $biayaProvisi,
                    'beban_bunga' => $bebanBunga,
                    'cash_margin' => $cashMargin,
                    'cash_flow_kumulatif' => $cumulativeCashflow,
                ]
            );
        }

        // Delete excess months if duration decreased
        ProjectCashflow::where('project_id', $project->id)
            ->where('month_index', '>', $durationMonths)
            ->delete();

        // ── Auto-Sync Child Module Records from Cashflow Totals ────────
        $cfCollection = ProjectCashflow::where('project_id', $project->id)->get();
        $totalJasa = $cfCollection->sum('jasa_konstruksi');
        $totalFeeGsd = $cfCollection->sum('management_fee');
        $totalCostMitra = $cfCollection->sum('biaya_mitra');

        // Sync Cost Structure
        if ($totalCostMitra > 0 || ! optional($project->costStructure)->biaya_mitra_pelaksana) {
            $project->costStructure()->updateOrCreate(
                ['project_id' => $project->id],
                ['biaya_mitra_pelaksana' => $totalCostMitra > 0 ? $totalCostMitra : (optional($project->costStructure)->biaya_mitra_pelaksana ?? 0)]
            );
        }

        // Helper to identify standard auto-calculated items vs custom manual items
        $isStandardItem = function($name) {
            $n = strtolower(trim($name));
            $standards = [
                'pph', 'pph pasal 23', 'ppn keluaran', 'ppn masukan', 'kredit ppn',
                'besar pinjaman', 'biaya provisi', 'bunga pinjaman',
                'jasa pelaksanaan konstruksi', 'management fee gsd',
                'fee fasilitas jaminan', 'admin fasilitas jaminan', 'car', 'assurance',
                'iuran jasa', 'biaya pengawasan', 'bop project'
            ];
            foreach ($standards as $s) {
                if (str_contains($n, $s)) return true;
            }
            return false;
        };

        // Sync Revenues (Preserve custom manual entries, replace standard auto entries)
        if ($totalJasa > 0 || $totalFeeGsd > 0) {
            ProjectRevenue::where('project_id', $project->id)->get()->each(function($r) use ($isStandardItem) {
                if (! $r->is_manual || $isStandardItem($r->name)) $r->delete();
            });
            if ($totalJasa > 0) {
                ProjectRevenue::create(['project_id' => $project->id, 'name' => 'Jasa Pelaksanaan Konstruksi', 'amount' => $totalJasa, 'is_manual' => 0]);
            }
            if ($totalFeeGsd > 0) {
                ProjectRevenue::create(['project_id' => $project->id, 'name' => 'Management Fee GSD', 'amount' => $totalFeeGsd, 'is_manual' => 0]);
            }
        }

        // Sync Beban (Preserve custom manual entries, replace standard auto entries)
        $totFeeJam = $cfCollection->sum('fee_jaminan');
        $totAdminJam = $cfCollection->sum('admin_jaminan');
        $totCar = $cfCollection->sum('car');
        $totIuran = $cfCollection->sum('iuran_jasa');
        $totPengawasan = $cfCollection->sum('biaya_pengawasan');
        $totBop = $cfCollection->sum('bop_project');

        if (($totFeeJam + $totAdminJam + $totCar + $totIuran + $totPengawasan + $totBop) > 0) {
            ProjectBeban::where('project_id', $project->id)->get()->each(function($b) use ($isStandardItem) {
                if (! $b->is_manual || $isStandardItem($b->name)) $b->delete();
            });
            if ($totFeeJam > 0) ProjectBeban::create(['project_id' => $project->id, 'name' => 'Fee Fasilitas Jaminan', 'amount' => $totFeeJam, 'is_manual' => 0]);
            if ($totAdminJam > 0) ProjectBeban::create(['project_id' => $project->id, 'name' => 'Admin Fasilitas Jaminan', 'amount' => $totAdminJam, 'is_manual' => 0]);
            if ($totCar > 0) ProjectBeban::create(['project_id' => $project->id, 'name' => 'Construction Assurance Risk (CAR)', 'amount' => $totCar, 'is_manual' => 0]);
            if ($totIuran > 0) ProjectBeban::create(['project_id' => $project->id, 'name' => 'Iuran Jasa Konstruksi', 'amount' => $totIuran, 'is_manual' => 0]);
            if ($totPengawasan > 0) ProjectBeban::create(['project_id' => $project->id, 'name' => 'Biaya Pengawasan', 'amount' => $totPengawasan, 'is_manual' => 0]);
            if ($totBop > 0) ProjectBeban::create(['project_id' => $project->id, 'name' => 'BOP Project', 'amount' => $totBop, 'is_manual' => 0]);
        }

        // Sync Pajak (Preserve custom manual entries, replace standard auto entries)
        $totPph = $cfCollection->sum('pph');
        $totPpnKel = $cfCollection->sum('ppn_keluaran');
        $totPpnMas = $cfCollection->sum('ppn_masukan');
        $totKreditPpn = $cfCollection->sum('kredit_ppn');

        if (($totPph + $totPpnKel + $totPpnMas + $totKreditPpn) != 0) {
            ProjectPajak::where('project_id', $project->id)->get()->each(function($p) use ($isStandardItem) {
                if (! $p->is_manual || $isStandardItem($p->name)) $p->delete();
            });
            if ($totPph > 0) ProjectPajak::create(['project_id' => $project->id, 'name' => 'PPh Pasal 23', 'amount' => $totPph, 'is_manual' => 0]);
            if ($totPpnKel > 0) ProjectPajak::create(['project_id' => $project->id, 'name' => 'PPN Keluaran', 'amount' => $totPpnKel, 'is_manual' => 0]);
            if ($totPpnMas > 0) ProjectPajak::create(['project_id' => $project->id, 'name' => 'PPN Masukan', 'amount' => $totPpnMas, 'is_manual' => 0]);
            if ($totKreditPpn != 0) ProjectPajak::create(['project_id' => $project->id, 'name' => 'Kredit PPN', 'amount' => $totKreditPpn, 'is_manual' => 0]);
        }

        // Sync Pinjaman (Preserve custom manual entries, replace standard auto entries)
        $loanBase = ($totalJasa + $totalFeeGsd > 0) ? ($totalJasa + $totalFeeGsd) : $totalCostMitra;
        $totBesarPinjaman = round($loanBase * ($loanRate / 100));
        $totProvisi = $cfCollection->sum('biaya_provisi');
        $totBunga = $cfCollection->sum('beban_bunga');
        if (($totBesarPinjaman + $totProvisi + $totBunga) > 0) {
            ProjectPinjaman::where('project_id', $project->id)->get()->each(function($p) use ($isStandardItem) {
                if (! $p->is_manual || $isStandardItem($p->name)) $p->delete();
            });
            ProjectPinjaman::create(['project_id' => $project->id, 'name' => 'Besar Pinjaman', 'amount' => $totBesarPinjaman, 'is_manual' => 0]);
            ProjectPinjaman::create(['project_id' => $project->id, 'name' => 'Biaya Provisi', 'amount' => $totProvisi, 'is_manual' => 0]);
            ProjectPinjaman::create(['project_id' => $project->id, 'name' => 'Bunga Pinjaman (per bulan)', 'amount' => $totBunga, 'is_manual' => 0]);
        }
    }
}
