<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$project = App\Models\Project::where('name', 'Proyek Gedung Kantor ABC')->first();
$items = App\Models\WorkItem::where('project_id', $project->id)->get();

echo "Total Items: " . $items->where('type', 'item')->count() . "\n";
echo "Persiapan: " . $items->where('name', 'PEKERJAAN PERSIAPAN')->first()->total_price . "\n";
echo "Staff: " . $items->where('name', 'PEKERJAAN RUANG STAFF & RESEPSIONIS')->first()->total_price . "\n";
echo "VIP: " . $items->where('name', 'PEKERJAAN RUANG VIP')->first()->total_price . "\n";
echo "Rapat: " . $items->where('name', 'PEKERJAAN RUANG RAPAT UMUM')->first()->total_price . "\n";
echo "Director: " . $items->where('name', 'PEKERJAAN RUANG EXECUTIVE DIRECTOR')->first()->total_price . "\n";
echo "Interior: " . $items->where('name', 'PEKERJAAN FIT OUT INTERIOR DAN ARSITEKTUR')->first()->total_price . "\n";
echo "ME: " . $items->where('name', 'PEKERJAAN MEKANIKAL ELEKTRIKAL')->first()->total_price . "\n";
echo "Total: " . $items->where('type', 'main')->sum('total_price') . "\n";
