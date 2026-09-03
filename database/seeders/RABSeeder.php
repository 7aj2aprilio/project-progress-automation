<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\WorkItem;
use Illuminate\Support\Facades\DB;

class RABSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            $project = Project::firstOrCreate(
                ['name' => 'PEMBUATAN INTERIOR KANTOR REGIONAL III  PT ASDP INDONESIA FERRY'],
                [
                    'status' => 'active'
                ]
            );

            $project->information()->updateOrCreate([], [
                'nama_pelanggan' => 'PT ASDP INDONESIA FERRY',
                'nama_project' => 'PEMBUATAN INTERIOR KANTOR REGIONAL III  PT ASDP INDONESIA FERRY',
                'estimasi_mulai' => now(),
                'estimasi_selesai' => now()->addMonths(3),
            ]);

            // Optional: Clear existing work items for this project to avoid duplicates if run multiple times
            WorkItem::where('project_id', $project->id)->delete();

            // I. PEKERJAAN PERSIAPAN
            $persiapan = WorkItem::create([
                'project_id' => $project->id,
                'parent_id' => null,
                'type' => 'main',
                'name' => 'PEKERJAAN PERSIAPAN',
                'total_price' => 55415000,
            ]);

            $this->createItem($project->id, $persiapan->id, 'Marking dan pengukuran ulang', 250, 'm2', 12500, 3125000);
            $this->createItem($project->id, $persiapan->id, 'Mobilisasi dan demobilisasi (meliputi material & tenaga kerja)', 1, 'ls', 5000000, 5000000);
            $this->createItem($project->id, $persiapan->id, 'Biaya Air dan Listrik selama pekerjaan', 2, 'bulan', 4000000, 8000000);
            $this->createItem($project->id, $persiapan->id, 'Biaya pengamanan barang di lokasi', 2, 'bulan', 3450000, 6900000);
            $this->createItem($project->id, $persiapan->id, 'Biaya Kebersihan proyek', 2, 'bulan', 3300000, 6600000);
            $this->createItem($project->id, $persiapan->id, 'Pengangkutan/Pembuangan puing bekas bongkaran ; satuan mobil truck engkel', 5, 'rit', 1350000, 6750000);
            $this->createItem($project->id, $persiapan->id, 'Peralatan dan Material Bantu (Scafolding, terpal Plastik, exhaust/blower dll )', 1, 'ls', 3540000, 3540000);
            $this->createItem($project->id, $persiapan->id, 'Hard copy As Built drawing dan approval material', 1, 'Ls', 8000000, 8000000);
            $this->createItem($project->id, $persiapan->id, 'Deposit Gedung', 1, 'Ls', 7500000, 7500000);

            // II. PEKERJAAN FIT OUT INTERIOR DAN ARSITEKTUR
            $interior = WorkItem::create([
                'project_id' => $project->id,
                'parent_id' => null,
                'type' => 'main',
                'name' => 'PEKERJAAN FIT OUT INTERIOR DAN ARSITEKTUR',
                'total_price' => 401788996,
            ]);

            // II.A. PEKERJAAN RUANG STAFF & RESEPSIONIS
            $staff = WorkItem::create([
                'project_id' => $project->id,
                'parent_id' => $interior->id,
                'type' => 'sub',
                'name' => 'PEKERJAAN RUANG STAFF & RESEPSIONIS',
                'total_price' => 209206744,
            ]);
            $this->createItem($project->id, $staff->id, 'Screeding & levelling lantai', 186.41, 'm2', 129000, 24046503);
            $this->createItem($project->id, $staff->id, 'Pengadaan dan pasang penutup lantai Carpet Flowing Plank', 186.41, 'm2', 706000, 131603342);
            $this->createItem($project->id, $staff->id, 'Pek Finishing Dinding dan Partisi', 96.52, 'm2', 45000, 4343310, 'Material : Dulux');
            $this->createItem($project->id, $staff->id, 'Pengadaan dan pasang plint lantai', 34.55, 'm\'', 87000, 3005589, 'Material : HPL tinggi 10 cm x tebal 1 cm');
            $this->createItem($project->id, $staff->id, 'Pek Signed ASDP Uk. 135 x 100 cm', 1, 'unit', 5207000, 5207000);
            $this->createItem($project->id, $staff->id, 'Backdrop Resepsionis', 1, 'Ls', 21856000, 21856000, '4680 X 2820 mm (COS)');
            $this->createItem($project->id, $staff->id, 'Dry Pantry Workcafé', 1, 'Ls', 19145000, 19145000, "2500 X 600 X 2820 mm (COS); Top table + Backsplash solid surface (ST1); Dinding lapis keramik (CRT1); Adjuster plywood 9 mm fin. HPL (LM7); Body cabinet plywood 20 mm fin. HPL (LM7) + PVC edging; Pintu cabinet plywood 20 mm fin. HPL (LM7) + PVC edging; Pintu cabinet + drawer bawah plywood 20 mm fin. HPL ex. Taco TH889FC French Tan Oak (LM4) + PVC edging; Skirting cabinet bawah plywood fin. HPL (LM4); Inside melaminto; Signage on furniture (SGN5); 6 Power outlet; Sesuai gambar detail");

            // II.B. PEKERJAAN RUANG VIP
            $vip = WorkItem::create([
                'project_id' => $project->id,
                'parent_id' => $interior->id,
                'type' => 'sub',
                'name' => 'PEKERJAAN RUANG VIP',
                'total_price' => 62278684,
            ]);
            $this->createItem($project->id, $vip->id, 'Screeding & levelling lantai', 20.12, 'm2', 129000, 2595996);
            $this->createItem($project->id, $vip->id, 'Pengadaan dan pasang penutup lantai Carpet Flowing Plank', 20.12, 'm2', 706000, 14207544, "Material : ex kencana arid - Interface");
            $this->createItem($project->id, $vip->id, 'Pengadaan & pasang Partisi 2 muka', 34.58, 'm2', 310000, 10719490, "Material Penutup : Gypsum 12 mm ex Jayaboard/setara; Rangka Metal Stud; Stopping angle, control joint; Jarak modul minimal 600x600 mm");
            $this->createItem($project->id, $vip->id, 'Pek Finishing Dinding dan Partisi', 34.58, 'm2', 45000, 1556055, "Material : Dulux Brilliant White");
            $this->createItem($project->id, $vip->id, 'Pengadaan dan pasang plint lantai', 11.78, 'm\'', 87000, 1024599, "Material : HPL . Ex Taco 10 x 1");
            $this->createItem($project->id, $vip->id, 'Backdrop TV', 1, 'Ls', 12972000, 12972000, "2500 X 400 X 2820 mm (COS); 6 Power outlet; Sesuai gambar detail");
            $this->createItem($project->id, $vip->id, 'Pengadaan & pasang Pintu Kaca', 1, 'Unit', 7051000, 7051000, "spesifikasi Hardware: Daun pintu dan jendela kaca tempered 12 mm ex. Asahimas uk. 210x100 cm + Sandblast motif lokal, tingkat keburaman 80% (ditempel pada kaca bagian dalam)");
            $this->createItem($project->id, $vip->id, 'Pengadaan & pasang Jendela Kaca Rangka Aluminium', 4, 'm2', 3038000, 12152000, "spesifikasi Hardware: Kusen Aluminium 4\" finish powder coating warna hitam; Sandblast motif lokal, tingkat keburaman 80% (ditempel pada kaca bagian dalam)");

            // II.C. PEKERJAAN RUANG RAPAT UMUM
            $rapat = WorkItem::create([
                'project_id' => $project->id,
                'parent_id' => $interior->id,
                'type' => 'sub',
                'name' => 'PEKERJAAN RUANG RAPAT UMUM',
                'total_price' => 64385830,
            ]);
            $this->createItem($project->id, $rapat->id, 'Screeding & levelling lantai', 20.12, 'm2', 129000, 2595996);
            $this->createItem($project->id, $rapat->id, 'Pengadaan dan pasang penutup lantai Carpet Flowing Plank', 20.12, 'm2', 706000, 14207544, "Material : ex kencana arid - Interface");
            $this->createItem($project->id, $rapat->id, 'Pengadaan & pasang Partisi 2 muka', 10.01, 'm2', 310000, 3104340, "Material Penutup : Gypsum 12 mm ex Jayaboard/setara; Rangka Metal Stud; Stopping angle, control joint; Jarak modul minimal 600x600 mm");
            $this->createItem($project->id, $rapat->id, 'Pek Finishing Dinding dan Partisi', 34.85, 'm2', 45000, 1568115, "Material : Dulux Brilliant White");
            $this->createItem($project->id, $rapat->id, 'Pengadaan dan pasang plint lantai', 3.21, 'm', 87000, 278835, "Material : HPL . Ex Taco 10 x 1");
            $this->createItem($project->id, $rapat->id, 'Backdrop Ruang Rapat', 1, 'ls', 23428000, 23428000, "4300 X 250 X 2820 mm; Plywood 12 mm; Rangka metal stud + Insulasi Rockwool Density 80 Slab to Slab; 1 Frame TV; include 2 TV Power, 2 HDMI, 2 Data; Naad 3 mm; Sesuai gambar detail");
            $this->createItem($project->id, $rapat->id, 'Pengadaan & pasang Pintu Kaca', 1, 'Unit', 7051000, 7051000, "spesifikasi Hardware: Daun pintu dan jendela kaca tempered 12 mm ex. Asahimas uk. 210x100 cm + Sandblast motif lokal, tingkat keburaman 80% (ditempel pada kaca bagian dalam)");
            $this->createItem($project->id, $rapat->id, 'Pengadaan & pasang Jendela Kaca Rangka Aluminium', 4, 'm2', 3038000, 12152000, "spesifikasi Hardware: Kusen Aluminium 4\" finish powder coating warna hitam; Sandblast motif lokal, tingkat keburaman 80% (ditempel pada kaca bagian dalam)");

            // II.D. PEKERJAAN RUANG EXECUTIVE DIRECTOR
            $director = WorkItem::create([
                'project_id' => $project->id,
                'parent_id' => $interior->id,
                'type' => 'sub',
                'name' => 'PEKERJAAN RUANG EXECUTIVE DIRECTOR',
                'total_price' => 65917738,
            ]);
            $this->createItem($project->id, $director->id, 'Screeding & levelling lantai', 23.35, 'm2', 129000, 3011505);
            $this->createItem($project->id, $director->id, 'Pengadaan dan pasang penutup lantai Carpet Flowing Plank', 23.35, 'm2', 706000, 16481570, "Material : ex kencana arid - Interface");
            $this->createItem($project->id, $director->id, 'Pengadaan & pasang Partisi 2 muka', 24.42, 'm2', 310000, 7570820, "Material Penutup : Gypsum 12 mm ex Jayaboard/setara; Rangka Metal Stud; Stopping angle, control joint; Jarak modul minimal 600x600 mm");
            $this->createItem($project->id, $director->id, 'Pek Finishing Dinding dan Partisi', 34.94, 'm2', 322000, 11251002, "Material : Ex. Starwall, Bravo, Macth");
            $this->createItem($project->id, $director->id, 'Pengadaan dan pasang plint lantai', 11.87, 'm\'', 87000, 1032951, "Material : HPL . Ex Taco 10 x 1");
            $this->createItem($project->id, $director->id, 'Pengadaan & pasang Pintu Kaca', 1, 'Unit', 7051000, 7051000, "spesifikasi Hardware: Daun pintu dan jendela kaca tempered 12 mm ex. Asahimas uk. 210x100 cm + Sandblast motif lokal, tingkat keburaman 80% (ditempel pada kaca bagian dalam)");
            $this->createItem($project->id, $director->id, 'Pengadaan & pasang Jendela Kaca Rangka Aluminium', 2.16, 'm2', 3038000, 6546890, "spesifikasi Hardware: Kusen Aluminium 4\" finish powder coating warna hitam; Sandblast motif lokal, tingkat keburaman 80% (ditempel pada kaca bagian dalam)");
            $this->createItem($project->id, $director->id, 'Backdrop TV', 1, 'Ls', 12972000, 12972000, "2500 X 400 X 2820 mm (COS); 6 Power outlet; Sesuai gambar detail");

            // III. PEKERJAAN MEKANIKAL ELEKTRIKAL
            $me = WorkItem::create([
                'project_id' => $project->id,
                'parent_id' => null,
                'type' => 'main',
                'name' => 'PEKERJAAN MEKANIKAL ELEKTRIKAL',
                'total_price' => 136796004,
            ]);

            // III.A. PEKERJAAN ELEKTRIKAL
            $elektrikal = WorkItem::create([
                'project_id' => $project->id,
                'parent_id' => $me->id,
                'type' => 'sub',
                'name' => 'PEKERJAAN ELEKTRIKAL',
                'total_price' => 134680000,
            ]);
            $this->createItem($project->id, $elektrikal->id, 'Penambahan MCB switch 10A / 1 phase', 1, 'unit', 322000, 322000);
            $this->createItem($project->id, $elektrikal->id, 'Kabel power tipe NYY uk. …..', 200, 'm\'', 325500, 65100000);
            $this->createItem($project->id, $elektrikal->id, 'Downlight LED Min 1300lm, 6.000K, 14W ex. Phillips', 60, 'titik', 249500, 14970000, "PEKERJAAN INSTALASI DAN ARMATUR");
            $this->createItem($project->id, $elektrikal->id, 'Downlight LED Min 1300lm, 6.000K, 14W +Nicad Battery 2 Jam Back-Up', 6, 'titik', 341500, 2049000);
            $this->createItem($project->id, $elektrikal->id, 'Saklar Single', 3, 'titik', 90100, 270300);
            $this->createItem($project->id, $elektrikal->id, 'Saklar Double', 3, 'titik', 477300, 1431900);
            $this->createItem($project->id, $elektrikal->id, 'Stop Kontak Dinding 100W double ex. Broco', 18, 'titik', 69000, 1242000);
            $this->createItem($project->id, $elektrikal->id, 'Stop Kontak TV 100W ex. Broco', 3, 'titik', 73600, 220800);
            $this->createItem($project->id, $elektrikal->id, 'Stop Kontak Meja 100W double ex. Broco', 102, 'titik', 69000, 7038000);
            $this->createItem($project->id, $elektrikal->id, 'Titik instalasi Penerangan dengan kabel 4 Besar NYM 3x2,5mm2 in Conduit 20mm', 32, 'titik', 271200, 8678400);
            $this->createItem($project->id, $elektrikal->id, 'Titik instalasi stop kontak dengan kabel 4 Besar NYM 3x2,5mm2 in Conduit 20mm', 123, 'titik', 271200, 33357600);

            // III.B. PEKERJAAN EXHAUST FAN
            $exhaust = WorkItem::create([
                'project_id' => $project->id,
                'parent_id' => $me->id,
                'type' => 'sub',
                'name' => 'PEKERJAAN EXHAUST FAN',
                'total_price' => 2116004,
            ]);
            $this->createItem($project->id, $exhaust->id, 'Pengadaan dan pemasangan exhaust fan', 2, 'titik', 1058002, 2116004);
            
            echo "Seeding completed successfully.\n";
        });
    }

    private function createItem($projectId, $parentId, $name, $volume, $unit, $price, $totalPrice, $keterangan = null)
    {
        return WorkItem::create([
            'project_id' => $projectId,
            'parent_id' => $parentId,
            'type' => 'item',
            'name' => $name,
            'volume' => $volume,
            'unit' => $unit,
            'unit_price' => $price,
            'total_price' => $totalPrice,
            'keterangan' => $keterangan,
        ]);
    }
}
