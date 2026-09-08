<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Services\CashflowCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashflow_calculates_cash_in_when_top_pelanggan_is_set(): void
    {
        $project = Project::create([
            'name' => 'Proyek Uji Cashflow',
            'status' => 'active',
        ]);

        $project->information()->create([
            'estimasi_mulai' => '2026-09-01',
            'estimasi_selesai' => '2027-02-28',
        ]);

        $project->costStructure()->create([
            'biaya_mitra_pelaksana' => 203967799,
        ]);

        $calculator = app(CashflowCalculator::class);
        $calculator->calculate($project);

        $project->cashflows()->where('month_index', 1)->update([
            'pct_top_pelanggan' => 100,
            'pct_top_mitra' => 100,
        ]);

        $calculator->calculate($project->fresh());

        $month1 = $project->cashflows()->where('month_index', 1)->first();

        $this->assertNotNull($month1);
        $this->assertGreaterThan(0, $month1->jasa_konstruksi);
        $this->assertEquals(203967799, round($month1->jasa_konstruksi));
        $this->assertEquals(203967799, round($month1->cash_in));
    }

    public function test_cashflow_preserves_and_saves_manual_management_fee(): void
    {
        $project = Project::create([
            'name' => 'Proyek Uji Management Fee',
            'status' => 'active',
        ]);

        $project->information()->create([
            'estimasi_mulai' => '2026-09-01',
            'estimasi_selesai' => '2027-02-28',
        ]);

        $project->costStructure()->create([
            'biaya_mitra_pelaksana' => 203967799,
        ]);

        $calculator = app(CashflowCalculator::class);
        $calculator->calculate($project);

        // User enters 20,000,000 manual management fee in month 1
        $project->cashflows()->where('month_index', 1)->update([
            'pct_top_pelanggan' => 100,
            'pct_top_mitra' => 100,
            'management_fee' => 20000000,
        ]);

        $calculator->calculate($project->fresh());

        $month1 = $project->cashflows()->where('month_index', 1)->first();

        $this->assertNotNull($month1);
        $this->assertEquals(20000000, round($month1->management_fee));
        $this->assertEquals(203967799, round($month1->jasa_konstruksi));
        $this->assertEquals(223967799, round($month1->cash_in));
    }

    public function test_cashflow_matches_excel_exact_accounting_calculations(): void
    {
        $project = Project::create([
            'name' => 'Proyek Profitability 5 Lokasi',
            'status' => 'active',
        ]);

        $project->information()->create([
            'estimasi_mulai' => '2026-09-01',
            'estimasi_selesai' => '2027-02-28',
            'loan_rate' => 1.65,
        ]);

        $project->costStructure()->create([
            'biaya_mitra_pelaksana' => 203967799,
        ]);

        $project->revenues()->create([
            'name' => 'Management Fee GSD',
            'amount' => 75626951,
            'is_manual' => 1,
        ]);

        $calculator = app(CashflowCalculator::class);
        $calculator->calculate($project);

        $project->cashflows()->where('month_index', 1)->update([
            'pct_top_pelanggan' => 100,
            'pct_top_mitra' => 100,
        ]);

        $calculator->calculate($project->fresh());

        $month0 = $project->cashflows()->where('month_index', 0)->first();
        $month1 = $project->cashflows()->where('month_index', 1)->first();

        // Month 0 assertions (Matching Excel)
        $this->assertEquals(3365469, round($month0->penarikan_pinjaman));
        $this->assertEquals(33655, round($month0->biaya_provisi));
        $this->assertEquals(3331814, round($month0->cash_margin));
        $this->assertEquals(3331814, round($month0->cash_flow_kumulatif));

        // Month 1 assertions
        $this->assertEquals(279594750, round($month1->cash_in));
        $this->assertEquals(203967799, round($month1->jasa_konstruksi));
        $this->assertEquals(75626951, round($month1->management_fee));
        $this->assertEquals(203967799, round($month1->cash_out));
        $this->assertEquals(75626951, round($month1->gross_margin));
        $this->assertEquals(7409261, round($month1->pph));
        $this->assertEquals(3365469, round($month1->pembayaran_pokok));
        $this->assertEquals(30542, round($month1->beban_bunga));
        $this->assertEquals(64821679, round($month1->cash_margin));
        $this->assertEquals(68153493, round($month1->cash_flow_kumulatif));

        // Total assertions
        $this->assertEquals(68153493, round($project->cashflows()->sum('cash_margin')));
        $this->assertEquals(68153493, round($project->fresh()->cashflows->last()->cash_flow_kumulatif));
    }

    public function test_cashflow_matches_excel_bbm_gombel_exact_calculations(): void
    {
        $project = Project::create([
            'name' => 'BBM Gombel',
            'status' => 'active',
        ]);

        $project->information()->create([
            'estimasi_mulai' => '2026-08-25',
            'estimasi_selesai' => '2026-09-25',
            'loan_rate' => 1.65,
        ]);

        $project->costStructure()->create([
            'biaya_mitra_pelaksana' => 36400000,
        ]);

        $project->revenues()->create([
            'name' => 'Management Fee GSD',
            'amount' => 22462000,
            'is_manual' => 1,
        ]);

        $calculator = app(CashflowCalculator::class);
        $calculator->calculate($project);

        $project->cashflows()->where('month_index', 1)->update([
            'pct_top_pelanggan' => 100,
            'pct_top_mitra' => 100,
        ]);

        $calculator->calculate($project->fresh());

        $month0 = $project->cashflows()->where('month_index', 0)->first();
        $month1 = $project->cashflows()->where('month_index', 1)->first();

        // Month 0 assertions (Matching Excel BBM Gombel)
        $this->assertEquals(600600, round($month0->penarikan_pinjaman));
        $this->assertEquals(6006, round($month0->biaya_provisi));
        $this->assertEquals(594594, round($month0->cash_margin));
        $this->assertEquals(594594, round($month0->cash_flow_kumulatif));

        // Month 1 assertions
        $this->assertEquals(58862000, round($month1->cash_in));
        $this->assertEquals(36400000, round($month1->jasa_konstruksi));
        $this->assertEquals(22462000, round($month1->management_fee));
        $this->assertEquals(36400000, round($month1->cash_out));
        $this->assertEquals(22462000, round($month1->gross_margin));
        $this->assertEquals(1559843, round($month1->pph));
        $this->assertEquals(6474820, round($month1->ppn_keluaran));
        $this->assertEquals(4004000, round($month1->ppn_masukan));
        $this->assertEquals(2470820, round($month1->kredit_ppn));
        $this->assertEquals(600600, round($month1->pembayaran_pokok));
        $this->assertEquals(5450, round($month1->beban_bunga));
        $this->assertEquals(20296107, round($month1->cash_margin));
        $this->assertEquals(20890701, round($month1->cash_flow_kumulatif));

        // Total assertions
        $this->assertEquals(20890701, round($project->cashflows()->sum('cash_margin')));
        $this->assertEquals(20890701, round($project->fresh()->cashflows->last()->cash_flow_kumulatif));
    }

    public function test_cashflow_matches_excel_bbm_palapa_kupang_exact_calculations(): void
    {
        $project = Project::create([
            'name' => 'BBM Palapa Kupang',
            'status' => 'active',
        ]);

        $project->information()->create([
            'estimasi_mulai' => '2026-08-25',
            'estimasi_selesai' => '2026-09-25',
            'loan_rate' => 1.65,
        ]);

        $project->costStructure()->create([
            'biaya_mitra_pelaksana' => 21982187,
        ]);

        $project->revenues()->create([
            'name' => 'Management Fee GSD',
            'amount' => 7449063,
            'is_manual' => 1,
        ]);

        $calculator = app(CashflowCalculator::class);
        $calculator->calculate($project);

        $project->cashflows()->where('month_index', 1)->update([
            'pct_top_pelanggan' => 100,
            'pct_top_mitra' => 100,
        ]);

        $calculator->calculate($project->fresh());

        $month0 = $project->cashflows()->where('month_index', 0)->first();
        $month1 = $project->cashflows()->where('month_index', 1)->first();

        // Month 0 assertions
        $this->assertEquals(362706, round($month0->penarikan_pinjaman));
        $this->assertEquals(3627, round($month0->biaya_provisi));
        $this->assertEquals(359079, round($month0->cash_margin));
        $this->assertEquals(359079, round($month0->cash_flow_kumulatif));

        // Month 1 assertions (With integer input from UI, Month 1 is 6179112 and total is 6659871, or with exact float COGS is 6179113 / 6659872)
        $this->assertEquals(29431250, round($month1->cash_in));
        $this->assertEquals(21982187, round($month1->jasa_konstruksi));
        $this->assertEquals(7449063, round($month1->management_fee));
        $this->assertEquals(21982187, round($month1->cash_out));
        $this->assertEquals(7449063, round($month1->gross_margin));
        $this->assertEquals(779928, round($month1->pph));
        $this->assertEquals(3237438, round($month1->ppn_keluaran));
        $this->assertEquals(2418041, round($month1->ppn_masukan));
        $this->assertEquals(819397, round($month1->kredit_ppn));
        $this->assertEquals(362706, round($month1->pembayaran_pokok));
        $this->assertEquals(3292, round($month1->beban_bunga));
        $this->assertEquals(6303137, round($month1->cash_margin));
        $this->assertEquals(6662216, round($month1->cash_flow_kumulatif));
    }

    public function test_can_export_cashflow_pdf(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $project = Project::create([
            'name' => 'Proyek Export PDF',
            'status' => 'active',
        ]);

        $project->information()->create([
            'nama_pelanggan' => 'PT Pelanggan Test',
            'kategori_project' => 'Gedung',
            'lokasi_project' => 'Jakarta',
            'estimasi_mulai' => '2026-09-01',
            'estimasi_selesai' => '2027-02-28',
        ]);

        $project->costStructure()->create([
            'biaya_mitra_pelaksana' => 203967799,
        ]);

        $calculator = app(CashflowCalculator::class);
        $calculator->calculate($project);

        $response = $this->actingAs($user)->get(route('projects.export-cashflow', $project));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
