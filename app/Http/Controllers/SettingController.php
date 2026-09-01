<?php

namespace App\Http\Controllers;

use App\Models\GlobalSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'suku_bunga_pertahun' => GlobalSetting::getValue('suku_bunga_pertahun', 12),
            'suku_bunga_perbulan' => GlobalSetting::getValue('suku_bunga_perbulan', 1),
            'pemodalan_loan' => GlobalSetting::getValue('pemodalan_loan', 100),
            'provisi_rate' => GlobalSetting::getValue('provisi_rate', 1),
            'ppn_rate' => GlobalSetting::getValue('ppn_rate', 11),
            'pph_rate' => GlobalSetting::getValue('pph_rate', 2),
            'feasibility_threshold' => GlobalSetting::getValue('feasibility_threshold', 10.89),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'suku_bunga_pertahun' => 'required|numeric',
            'suku_bunga_perbulan' => 'required|numeric',
            'pemodalan_loan' => 'required|numeric',
            'provisi_rate' => 'required|numeric',
            'ppn_rate' => 'required|numeric',
            'pph_rate' => 'required|numeric',
            'feasibility_threshold' => 'required|numeric',
        ]);

        $names = [
            'suku_bunga_pertahun' => 'Suku Bunga per tahun (%)',
            'suku_bunga_perbulan' => 'Suku Bunga per bulan (%)',
            'pemodalan_loan' => 'Pemodalan: Loan (%)',
            'provisi_rate' => 'Provisi (%)',
            'ppn_rate' => 'PPN (%)',
            'pph_rate' => 'PPH pasal 23 (%)',
            'feasibility_threshold' => 'Threshold Kelayakan (%)',
        ];

        foreach ($data as $key => $value) {
            GlobalSetting::updateOrCreate(
                ['key' => $key],
                ['name' => $names[$key], 'value' => $value]
            );
        }

        return back()->with('success', 'Pengaturan global berhasil disimpan.');
    }
}
