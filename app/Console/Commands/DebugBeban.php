<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;

class DebugBeban extends Command
{
    protected $signature = 'debug:beban';
    protected $description = 'Debug beban data';

    public function handle()
    {
        $p = Project::first();
        $this->info("=== BEBAN ===");
        $this->info("Count: " . $p->beban()->count());
        foreach ($p->beban as $b) {
            $this->info("  - {$b->name}: {$b->amount} (manual: {$b->is_manual})");
        }

        $this->info("\n=== JAMINAN ===");
        $this->info("Count: " . $p->jaminan()->count());
        foreach ($p->jaminan as $j) {
            $this->info("  - {$j->name}: amount={$j->amount} pct={$j->percentage}");
        }

        $this->info("\n=== REVENUES ===");
        $this->info("Count: " . $p->revenues()->count());
        foreach ($p->revenues as $r) {
            $this->info("  - {$r->name}: {$r->amount} (manual: {$r->is_manual})");
        }

        $this->info("\n=== PAJAK ===");
        $this->info("Count: " . $p->pajak()->count());
        foreach ($p->pajak as $t) {
            $this->info("  - {$t->name}: {$t->amount}");
        }

        $this->info("\n=== PINJAMAN ===");
        $this->info("Count: " . $p->pinjaman()->count());
        foreach ($p->pinjaman as $pj) {
            $this->info("  - {$pj->name}: {$pj->amount}");
        }

        $this->info("\n=== INFO ===");
        $info = $p->information;
        if ($info) {
            $this->info("total_revenue: " . $info->total_revenue);
            $this->info("loan_rate: " . $info->loan_rate);
        }

        $this->info("\n=== COST STRUCTURE ===");
        $cs = $p->costStructure;
        if ($cs) {
            $this->info("biaya_mitra: " . $cs->biaya_mitra_pelaksana);
        }
    }
}
