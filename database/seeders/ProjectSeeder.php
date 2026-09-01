<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Services\ProjectCalculator;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $project = Project::create([
            'name' => 'Proyek Gedung Kantor ABC',
            'status' => 'active',
        ]);

        $project->information()->create([
            'nama_pelanggan' => 'PT. ABC Indonesia',
            'kategori_project' => 'Gedung Kantor',
            'nama_project' => 'Pembangunan Gedung Kantor ABC',
            'lokasi_project' => 'Jakarta Selatan',
            'estimasi_mulai' => '2024-03-01',
            'durasi_project' => 12,
            'durasi_retensi' => 6,
            'pengawasan_konstruksi' => 'Sendiri',
            'tipe_bangunan' => 'Gedung Bertingkat',
            'top_pembayaran_mitra' => 'Termin 30 hari',
            'top_pembayaran_pelanggan' => 'Progress bulanan',
            'total_revenue' => 7000000000, // 7 M
        ]);

        $project->assumption()->create([
            'suku_bunga_pertahun' => 12.00,
            'pemodalan' => 100,
            'periode_pinjaman' => 12,
        ]);

        $project->costStructure()->create([
            'biaya_mitra_pelaksana' => 5000000000, // 5 M
        ]);

        $project->beban()->createMany([
            ['name' => 'Fee Fasilitas Jaminan', 'amount' => 50000000],
            ['name' => 'Admin Fasilitas Jaminan', 'amount' => 10000000],
            ['name' => 'Construction Assurance Risk (CAR)', 'amount' => 25000000],
            ['name' => 'Iuran Jasa Konstruksi', 'amount' => 15000000],
            ['name' => 'Biaya Pengawasan', 'amount' => 100000000],
            ['name' => 'BOP Project', 'amount' => 75000000],
        ]);

        $project->jaminan()->createMany([
            ['name' => 'Jaminan Uang Muka', 'percentage' => 10],
            ['name' => 'Jaminan Penawaran', 'percentage' => 2],
            ['name' => 'Jaminan Pelaksanaan', 'percentage' => 5],
            ['name' => 'Jaminan Pemeliharaan', 'percentage' => 5],
        ]);

        $project->revenues()->createMany([
            ['name' => 'Jasa Pelaksanaan Konstruksi', 'amount' => 6500000000],
            ['name' => 'Management Fee GSD', 'amount' => 500000000],
        ]);

        $project->pajak()->createMany([
            ['name' => 'PPh Pasal 23'],
            ['name' => 'PPN Keluaran'],
            ['name' => 'PPN Masukan'],
        ]);

        $project->pinjaman()->createMany([
            ['name' => 'Besar Pinjaman'],
            ['name' => 'Biaya Provisi'],
            ['name' => 'Bunga Pinjaman (per bulan)'],
        ]);

        // Run calculator to populate dynamic fields
        $calculator = new ProjectCalculator();
        $project->refresh();
        $calculator->calculate($project);
    }
}
