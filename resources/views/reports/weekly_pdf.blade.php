<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Mingguan</title>
    <style>
        @font-face {
            font-family: 'Arial Black';
            src: url('{{ public_path('fonts/ariblk.ttf') }}') format('truetype');
            font-weight: 900;
            font-style: normal;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }
        .page-break {
            page-break-after: always;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Cover Styles */
        .cover-page {
            text-align: center;
            padding-top: 50px;
        }
        .logos {
            width: 100%;
            margin-bottom: 50px;
        }
        .logos img {
            height: 60px;
        }
        .logo-left { float: left; }
        .logo-right { float: right; }
        .clear { clear: both; }
        .title-section { margin-top: 100px; }
        .title-section h1 { font-size: 24px; margin: 5px 0; }
        .title-section h2 { font-size: 18px; margin: 5px 0; }
        .title-section h3 { font-size: 14px; margin: 5px 0; }
        .periode-section { margin-top: 150px; }
        .periode-section h2 { font-size: 18px; margin: 5px 0; }
        .periode-section h1 { font-size: 36px; margin: 10px 0; }
        .year-section { margin-top: 100px; font-weight: bold; }
        
        .header-info { margin-bottom: 10px; }
        .header-info td { border: none; padding: 2px; font-size: 11px; font-weight: bold;}
    </style>
</head>
<body>

    <!-- PAGE 1: COVER -->
    @php
        $logoLeftPath = $weeklyReport->logo_left_path ? public_path($weeklyReport->logo_left_path) : null;
        if(!$logoLeftPath || !file_exists($logoLeftPath)) {
            $logoLeftPath = public_path('images/placeholder.png');
        }
    @endphp
    
    <div class="cover-page" style="border: 1px solid black; margin: 10px; padding: 20px; height: 900px; position: relative; font-family: Arial, Helvetica, sans-serif;">
        <!-- Header Logos -->
        <div style="width: 100%; height: 80px; margin-bottom: 20px;">
            <img src="{{ $logoLeftPath }}" style="float: left; max-width: 200px; max-height: 80px;">
            <img src="{{ public_path('images/logoTelkom.webp') }}" style="float: right; max-width: 200px; max-height: 80px;">
            <div style="clear: both;"></div>
        </div>

        <!-- Text Project -->
        <div style="text-align: center; font-weight: bold; line-height: 1.4; margin-top: 50px;">
            <div style="font-size: 16px; margin-bottom: 20px;">PEKERJAAN</div>
            <div style="font-size: 22px; margin-bottom: 20px;">{{ strtoupper($project->name) }}</div>
            <div style="font-size: 14px;">{{ strtoupper($project->information->lokasi_project ?? 'LOKASI') }}</div>
        </div>

        <!-- Text Report -->
        <div style="text-align: center; font-weight: bold; line-height: 1.2; margin-top: 80px;">
            <div style="font-family: 'Arial Black', Arial, Helvetica, sans-serif; font-size: 28px; font-weight: 900; margin-bottom: 10px;">LAPORAN MINGGUAN</div>
            <div style="font-family: 'Arial Black', Arial, Helvetica, sans-serif; font-size: 18px; font-weight: 900;">(WEEKLY REPORT)</div>
        </div>
        
        <!-- Text Period -->
        <div style="position: absolute; bottom: 70px; left: 0; width: 100%; text-align: center; font-weight: bold; line-height: 1.3;">
            <div style="font-size: 12px; margin-bottom: 5px;">PERIODE :</div>
            <div style="font-size: 14px; margin-bottom: 10px;">MINGGU KE</div>
            <div style="font-family: 'Arial Black', Arial, Helvetica, sans-serif; font-size: 30px; font-weight: 900; margin-bottom: 15px;">{{ $weeklyReport->week_number }}</div>
            <div style="font-size: 14px;">{{ \Carbon\Carbon::parse($weeklyReport->start_date)->isoFormat('DD MMMM Y') }} &nbsp; hingga &nbsp; {{ \Carbon\Carbon::parse($weeklyReport->end_date)->isoFormat('DD MMMM Y') }}</div>
        </div>
        
        <!-- Year -->
        <div style="position: absolute; bottom: 40px; left: 0; width: 100%; text-align: center; font-weight: bold; font-size: 12px;">
            {{ $weeklyReport->start_date->format('Y') }}
        </div>
    </div>

    <div class="page-break"></div>

    <!-- PAGE 2: REKAPITULASI -->
    <div>
        <table class="header-info">
            <tr>
                <td width="15%">PROGRES PEKERJAAN</td>
                <td></td>
                <td width="15%">Week</td>
                <td width="2%">:</td>
                <td width="15%">{{ $weeklyReport->week_number }}</td>
            </tr>
            <tr>
                <td colspan="2">{{ strtoupper($project->name) }}</td>
                <td>Mulai</td>
                <td>:</td>
                <td>{{ $weeklyReport->start_date->format('d/m/y') }}</td>
            </tr>
            <tr>
                <td colspan="2">{{ $project->information->lokasi_project ?? '-' }}</td>
                <td>Hingga</td>
                <td>:</td>
                <td>{{ $weeklyReport->end_date->format('d/m/y') }}</td>
            </tr>
        </table>

        <table>
            <thead>
                <tr class="text-center font-bold" style="background-color: #f0f0f0;">
                    <th rowspan="2" width="5%">NO</th>
                    <th rowspan="2" width="45%">ITEM PEKERJAAN</th>
                    <th rowspan="2" width="10%">BOBOT</th>
                    <th colspan="2">MINGGU LALU</th>
                    <th colspan="2">MINGGU INI</th>
                    <th colspan="2">S/D MINGGU INI</th>
                </tr>
                <tr class="text-center font-bold" style="background-color: #f0f0f0;">
                    <th>PRESTASI</th>
                    <th>BOBOT %</th>
                    <th>PRESTASI</th>
                    <th>BOBOT %</th>
                    <th>PRESTASI</th>
                    <th>BOBOT %</th>
                </tr>
                <tr class="text-center">
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                    <td>9</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalBobot = 0;
                    $totalPrestasiLalu = 0;
                    $totalBobotLalu = 0;
                    $totalPrestasiIni = 0;
                    $totalBobotIni = 0;
                    $totalPrestasiSD = 0;
                    $totalBobotSD = 0;
                @endphp
                @foreach($workItems as $mainIndex => $mainItem)
                    @if($mainIndex > 0)
                        <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                    @endif
                    @php
                        $mBobot = $mainItem->base_bobot;
                        $mBobotLalu = 0;
                        $mBobotSD = 0;
                        
                        foreach($mainItem->children as $child) {
                            if ($child->type === 'sub') {
                                foreach($child->children as $item) {
                                    $mBobotLalu += ($item->base_bobot * ($previousProgresses[$item->id] ?? 0)) / 100;
                                    $mBobotSD += ($item->base_bobot * ($progresses[$item->id] ?? 0)) / 100;
                                }
                            } else {
                                $mBobotLalu += ($child->base_bobot * ($previousProgresses[$child->id] ?? 0)) / 100;
                                $mBobotSD += ($child->base_bobot * ($progresses[$child->id] ?? 0)) / 100;
                            }
                        }
                        
                        $mBobotIni = max(0, $mBobotSD - $mBobotLalu);
                        $mPrestasiLalu = $mBobot > 0 ? ($mBobotLalu / $mBobot) * 100 : 0;
                        $mPrestasiSD = $mBobot > 0 ? ($mBobotSD / $mBobot) * 100 : 0;
                        $mPrestasiIni = max(0, $mPrestasiSD - $mPrestasiLalu);

                        $totalBobot += $mBobot;
                        $totalBobotLalu += $mBobotLalu;
                        $totalBobotIni += $mBobotIni;
                        $totalBobotSD += $mBobotSD;
                    @endphp
                    <tr class="font-bold">
                        <td class="text-center">{{ App\Helpers\FormatHelper::romawi($mainIndex + 1) }}</td>
                        <td>{{ strtoupper($mainItem->name) }}</td>
                        <td class="text-right">{{ number_format($mBobot, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($mPrestasiLalu, 2, ',', '.') }}%</td>
                        <td class="text-right">{{ number_format($mBobotLalu, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($mPrestasiIni, 2, ',', '.') }}%</td>
                        <td class="text-right">{{ number_format($mBobotIni, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($mPrestasiSD, 2, ',', '.') }}%</td>
                        <td class="text-right">{{ number_format($mBobotSD, 2, ',', '.') }}</td>
                    </tr>
                    @foreach($mainItem->children as $subIndex => $subItem)
                        @if($subItem->type === 'sub')
                            @if($subIndex > 0)
                                <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            @endif
                            @php
                                $bobot = $subItem->base_bobot;
                                $bobotLalu = 0;
                                $bobotSD = 0;
                                
                                if ($subItem->type === 'sub') {
                                    foreach($subItem->children as $child) {
                                        $cBobot = $child->base_bobot;
                                        $cPLalu = $previousProgresses[$child->id] ?? 0;
                                        $cPSD = $progresses[$child->id] ?? 0;
                                        $bobotLalu += ($cBobot * $cPLalu) / 100;
                                        $bobotSD += ($cBobot * $cPSD) / 100;
                                    }
                                } else {
                                    $cPLalu = $previousProgresses[$subItem->id] ?? 0;
                                    $cPSD = $progresses[$subItem->id] ?? 0;
                                    $bobotLalu = ($bobot * $cPLalu) / 100;
                                    $bobotSD = ($bobot * $cPSD) / 100;
                                }
                                
                                $bobotIni = max(0, $bobotSD - $bobotLalu);
                                $prestasiLalu = $bobot > 0 ? ($bobotLalu / $bobot) * 100 : 0;
                                $prestasiSD = $bobot > 0 ? ($bobotSD / $bobot) * 100 : 0;
                                $prestasiIni = max(0, $prestasiSD - $prestasiLalu);

                                $prestasiIni = max(0, $prestasiSD - $prestasiLalu);
                            @endphp
                            <tr>
                                <td class="text-center">{{ App\Helpers\FormatHelper::romawi($mainIndex + 1) }}.{{ chr(65 + $subIndex) }}</td>
                                <td>{{ $subItem->name }}</td>
                                <td class="text-right">{{ number_format($bobot, 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($prestasiLalu, 2, ',', '.') }}%</td>
                                <td class="text-right">{{ number_format($bobotLalu, 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($prestasiIni, 2, ',', '.') }}%</td>
                                <td class="text-right">{{ number_format($bobotIni, 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($prestasiSD, 2, ',', '.') }}%</td>
                                <td class="text-right">{{ number_format($bobotSD, 2, ',', '.') }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-bold">
                    <td colspan="2" class="text-right">TOTAL</td>
                    <td class="text-right">{{ number_format($totalBobot, 2, ',', '.') }}</td>
                    <td class="text-right"></td>
                    <td class="text-right">{{ number_format($totalBobotLalu, 2, ',', '.') }}</td>
                    <td class="text-right"></td>
                    <td class="text-right">{{ number_format($totalBobotIni, 2, ',', '.') }}</td>
                    <td class="text-right"></td>
                    <td class="text-right">{{ number_format($totalBobotSD, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Summary Section (Deviasi) -->
        <br><br>
        <table style="width: 40%; margin: 0 auto; border: none;">
            <tr>
                <td style="border: none; text-align: right;">Rencana Minggu ini</td>
                <td style="border: none; width: 10px;">=</td>
                <td style="border: none; text-align: left;">{{ number_format($rencanaMingguIni, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: none; text-align: right; font-weight: bold;">Rencana Kumulatif</td>
                <td style="border: none;">=</td>
                <td style="border: none; text-align: left;">{{ number_format($rencanaKumulatif, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: none; text-align: right;">Realisasi Minggu ini</td>
                <td style="border: none;">=</td>
                <td style="border: none; text-align: left;">{{ number_format($totalBobotIni, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: none; text-align: right; font-weight: bold;">Realisasi Kumulatif</td>
                <td style="border: none;">=</td>
                <td style="border: none; text-align: left;">{{ number_format($totalBobotSD, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: none; text-align: right;">Deviasi</td>
                <td style="border: none;">=</td>
                @php $deviasi = $totalBobotSD - $rencanaKumulatif; @endphp
                <td style="border: none; text-align: left;" class="{{ $deviasi >= 0 ? 'text-green' : 'text-red' }}">
                    {{ ($deviasi > 0 ? '+' : '') . number_format($deviasi, 2, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- PAGE 3: RINCIAN PEKERJAAN -->
    <div>
        <table class="header-info">
            <tr>
                <td width="15%">PROGRES PEKERJAAN</td>
                <td></td>
                <td width="15%">Week</td>
                <td width="2%">:</td>
                <td width="15%">{{ $weeklyReport->week_number }}</td>
            </tr>
            <tr>
                <td colspan="2">{{ strtoupper($project->name) }}</td>
                <td>Mulai</td>
                <td>:</td>
                <td>{{ $weeklyReport->start_date->format('d/m/y') }}</td>
            </tr>
            <tr>
                <td colspan="2">{{ $project->information->lokasi_project ?? '-' }}</td>
                <td>Hingga</td>
                <td>:</td>
                <td>{{ $weeklyReport->end_date->format('d/m/y') }}</td>
            </tr>
        </table>

        <table>
            <thead>
                <tr class="text-center font-bold" style="background-color: #f0f0f0;">
                    <th rowspan="2" width="5%">NO</th>
                    <th rowspan="2" width="45%">ITEM PEKERJAAN</th>
                    <th rowspan="2" width="10%">BOBOT</th>
                    <th colspan="2">MINGGU LALU</th>
                    <th colspan="2">MINGGU INI</th>
                    <th colspan="2">S/D MINGGU INI</th>
                </tr>
                <tr class="text-center font-bold" style="background-color: #f0f0f0;">
                    <th>PRESTASI</th>
                    <th>BOBOT %</th>
                    <th>PRESTASI</th>
                    <th>BOBOT %</th>
                    <th>PRESTASI</th>
                    <th>BOBOT %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($workItems as $mainIndex => $mainItem)
                    @if($mainIndex > 0)
                        <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                    @endif
                    @php
                        $mBobot = $mainItem->base_bobot;
                        $mBobotLalu = 0;
                        $mBobotSD = 0;
                        
                        foreach($mainItem->children as $child) {
                            if ($child->type === 'sub') {
                                foreach($child->children as $item) {
                                    $mBobotLalu += ($item->base_bobot * ($previousProgresses[$item->id] ?? 0)) / 100;
                                    $mBobotSD += ($item->base_bobot * ($progresses[$item->id] ?? 0)) / 100;
                                }
                            } else {
                                $mBobotLalu += ($child->base_bobot * ($previousProgresses[$child->id] ?? 0)) / 100;
                                $mBobotSD += ($child->base_bobot * ($progresses[$child->id] ?? 0)) / 100;
                            }
                        }
                        
                        $mBobotIni = max(0, $mBobotSD - $mBobotLalu);
                        $mPrestasiLalu = $mBobot > 0 ? ($mBobotLalu / $mBobot) * 100 : 0;
                        $mPrestasiSD = $mBobot > 0 ? ($mBobotSD / $mBobot) * 100 : 0;
                        $mPrestasiIni = max(0, $mPrestasiSD - $mPrestasiLalu);
                    @endphp
                    <tr class="font-bold bg-gray-100">
                        <td class="text-center">{{ App\Helpers\FormatHelper::romawi($mainIndex + 1) }}</td>
                        <td>{{ strtoupper($mainItem->name) }}</td>
                        <td class="text-right">{{ number_format($mBobot, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($mPrestasiLalu, 2, ',', '.') }}%</td>
                        <td class="text-right">{{ number_format($mBobotLalu, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($mPrestasiIni, 2, ',', '.') }}%</td>
                        <td class="text-right">{{ number_format($mBobotIni, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($mPrestasiSD, 2, ',', '.') }}%</td>
                        <td class="text-right">{{ number_format($mBobotSD, 2, ',', '.') }}</td>
                    </tr>
                    @foreach($mainItem->children as $subIndex => $subItem)
                        @if($subItem->type === 'sub')
                            @if($subIndex > 0)
                                <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                            @endif
                            @php
                                $sBobot = $subItem->base_bobot;
                                $sBobotLalu = 0;
                                $sBobotSD = 0;
                                
                                foreach($subItem->children as $item) {
                                    $sBobotLalu += ($item->base_bobot * ($previousProgresses[$item->id] ?? 0)) / 100;
                                    $sBobotSD += ($item->base_bobot * ($progresses[$item->id] ?? 0)) / 100;
                                }
                                
                                $sBobotIni = max(0, $sBobotSD - $sBobotLalu);
                                $sPrestasiLalu = $sBobot > 0 ? ($sBobotLalu / $sBobot) * 100 : 0;
                                $sPrestasiSD = $sBobot > 0 ? ($sBobotSD / $sBobot) * 100 : 0;
                                $sPrestasiIni = max(0, $sPrestasiSD - $sPrestasiLalu);
                            @endphp
                            <tr class="font-bold bg-gray-50">
                                <td></td>
                                <td>{{ $subItem->name }}</td>
                                <td class="text-right">{{ number_format($sBobot, 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($sPrestasiLalu, 2, ',', '.') }}%</td>
                                <td class="text-right">{{ number_format($sBobotLalu, 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($sPrestasiIni, 2, ',', '.') }}%</td>
                                <td class="text-right">{{ number_format($sBobotIni, 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($sPrestasiSD, 2, ',', '.') }}%</td>
                                <td class="text-right">{{ number_format($sBobotSD, 2, ',', '.') }}</td>
                            </tr>
                            @foreach($subItem->children as $itemIndex => $item)
                                @php
                                    $bobot = $item->base_bobot;
                                    $prestasiSD = $progresses[$item->id] ?? 0;
                                    $prestasiLalu = $previousProgresses[$item->id] ?? 0;
                                    $prestasiIni = max(0, $prestasiSD - $prestasiLalu);
                                    
                                    $bobotLalu = ($bobot * $prestasiLalu) / 100;
                                    $bobotSD = ($bobot * $prestasiSD) / 100;
                                    $bobotIni = max(0, $bobotSD - $bobotLalu);
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $itemIndex + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-right">{{ number_format($bobot, 2, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($prestasiLalu, 2, ',', '.') }}%</td>
                                    <td class="text-right">{{ number_format($bobotLalu, 2, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($prestasiIni, 2, ',', '.') }}%</td>
                                    <td class="text-right">{{ number_format($bobotIni, 2, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($prestasiSD, 2, ',', '.') }}%</td>
                                    <td class="text-right">{{ number_format($bobotSD, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @else
                            @php
                                $item = $subItem; // Direct item
                                $bobot = $item->base_bobot;
                                $prestasiSD = $progresses[$item->id] ?? 0;
                                $prestasiLalu = $previousProgresses[$item->id] ?? 0;
                                $prestasiIni = max(0, $prestasiSD - $prestasiLalu);
                                
                                $bobotLalu = ($bobot * $prestasiLalu) / 100;
                                $bobotSD = ($bobot * $prestasiSD) / 100;
                                $bobotIni = max(0, $bobotSD - $bobotLalu);
                            @endphp
                            <tr>
                                <td class="text-center">{{ $subIndex + 1 }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="text-right">{{ number_format($bobot, 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($prestasiLalu, 2, ',', '.') }}%</td>
                                <td class="text-right">{{ number_format($bobotLalu, 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($prestasiIni, 2, ',', '.') }}%</td>
                                <td class="text-right">{{ number_format($bobotIni, 2, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($prestasiSD, 2, ',', '.') }}%</td>
                                <td class="text-right">{{ number_format($bobotSD, 2, ',', '.') }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- PAGE 4: KURVA S -->
    <div>
        <table class="header-info" style="border: 1px solid black; width: 100%; margin-bottom: 20px;">
            <tr>
                <td colspan="4" class="text-center font-bold" style="border: 1px solid black; font-size: 14px;">GRAFIK KURVA S</td>
            </tr>
            <tr>
                <td colspan="2" style="border: 1px solid black; font-weight: bold;">
                    {{ strtoupper($project->name) }}
                </td>
                <td style="border: 1px solid black; width: 15%;">MINGGU :</td>
                <td style="border: 1px solid black; width: 15%; text-align: center;">{{ $weeklyReport->week_number }}</td>
            </tr>
        </table>

        <!-- Time Schedule Table -->
        <div style="font-size: 7px; margin-bottom: 20px; width: 100%;">
            @php 
                // We already have $allWeeks, $allPlans, $allReports, $realisasiMap, $summary from the controller logic
                // But wait! We named them differently in WeeklyReportController
                // In WeeklyReportController:
                // $weeks is for the current week slice, $allWeeks is all weeks
                // We should pass them or map them here for the partial
            @endphp
            @include('projects.pdf.partials.s_curve_table', [
                'weeks' => $allWeeks ?? $project->weeks()->orderBy('week_number')->get(),
                'plans' => $allPlans ?? $project->timeSchedulePlans()->get()->keyBy(fn($p) => $p->work_item_id . '_' . $p->project_week_id),
                'realisasiMap' => $fullRealisasiMap ?? [],
                'summary' => $fullSummary ?? []
            ])
        </div>

        @if(isset($chartBase64) && $chartBase64)
            <div style="margin-top: 40px; text-align: center; border: 1px solid #cbd5e1; padding: 20px; background-color: #f8fafc; border-radius: 8px;">
                <img src="{{ $chartBase64 }}" alt="S-Curve Chart" style="max-width: 100%; height: auto;">
            </div>
        @endif
    </div>

    <div class="page-break"></div>

    <!-- PAGE 5: VISUAL -->
    @php
        $visualsList = $weeklyReport->visuals()->orderBy('position')->get();
        $visualChunks = $visualsList->chunk(8);
        if ($visualChunks->isEmpty()) {
            $visualChunks = collect([collect([])]); // Ensure at least one page if no visuals
        }
    @endphp

    @foreach($visualChunks as $pageIndex => $chunk)
        @if($pageIndex > 0)
            <div class="page-break"></div>
        @endif
        <div>
            <table class="header-info" style="border: 1px solid black; width: 100%; margin-bottom: 20px;">
                <tr>
                    <td colspan="4" class="text-center font-bold" style="border: 1px solid black; font-size: 14px;">LAPORAN VISUAL {{ $pageIndex > 0 ? '(Lanjutan ' . $pageIndex . ')' : '' }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="border: 1px solid black; font-weight: bold;">
                        {{ strtoupper($project->name) }}
                    </td>
                    <td style="border: 1px solid black; width: 15%;">MINGGU :</td>
                    <td style="border: 1px solid black; width: 15%; text-align: center; font-weight: bold;">{{ $weeklyReport->week_number }}</td>
                </tr>
                <tr>
                    <td style="width: 15%;">LOKASI</td>
                    <td>: {{ $project->information->lokasi_project ?? '-' }}</td>
                    <td colspan="2" rowspan="2" style="border: 1px solid black;">
                        {{ $weeklyReport->start_date->format('d F Y') }}<br>
                        {{ $weeklyReport->end_date->format('d F Y') }}
                    </td>
                </tr>
                <tr>
                    <td>TAHUN</td>
                    <td>: {{ $weeklyReport->start_date->format('Y') }}</td>
                </tr>
            </table>

            <!-- Visual Grid 4x2 (Dynamic) -->
            <table style="border: none; width: 100%;">
                @php $rows = $chunk->chunk(2); @endphp
                @foreach($rows as $row)
                    <tr>
                        @foreach($row as $v)
                            <td style="border: none; padding: 5px; width: 50%; text-align: center; vertical-align: top;">
                                <div style="border: 2px solid black; padding: 5px;">
                                    <div style="height: 180px; text-align: center;">
                                        @if($v->image_path && file_exists(public_path($v->image_path)))
                                            <img src="{{ public_path($v->image_path) }}" style="max-width: 100%; max-height: 180px; object-fit: contain;">
                                        @endif
                                    </div>
                                    <div style="margin-top: 5px; font-weight: bold; font-size: 10px; min-height: 15px;">
                                        {{ $v->title }}
                                    </div>
                                </div>
                            </td>
                        @endforeach
                        @if($row->count() == 1)
                            <td style="border: none; padding: 5px; width: 50%;"></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
    @endforeach

</body>
</html>
