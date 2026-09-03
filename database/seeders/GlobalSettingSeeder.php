<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use Illuminate\Database\Seeder;

class GlobalSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'suku_bunga_pertahun' => ['name' => 'Suku Bunga per tahun (%)', 'value' => 10.89],
            'pemodalan_loan' => ['name' => 'Pemodalan: Loan (%)', 'value' => 100],
            'provisi_rate' => ['name' => 'Provisi (%)', 'value' => 1],
            'ppn_rate' => ['name' => 'PPN (%)', 'value' => 12],
            'pph_rate' => ['name' => 'PPH pasal 23 (%)', 'value' => 2.65],
            'loan_rate' => ['name' => 'Besar Pinjaman Rate (%)', 'value' => 1.65],
            'periode_pinjaman' => ['name' => 'Periode Pinjaman (Bulan)', 'value' => 12],
            'feasibility_threshold' => ['name' => 'Threshold Kelayakan (%)', 'value' => 10.89],
        ];

        foreach ($settings as $key => $data) {
            GlobalSetting::updateOrCreate(
                ['key' => $key],
                ['name' => $data['name'], 'value' => $data['value']]
            );
        }
    }
}
