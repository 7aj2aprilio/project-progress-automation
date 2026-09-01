<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Mingguan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
    <div class="cover-page">
        <div class="logos">
            @if($weeklyReport->logo_left_path)
                <img src="{{ public_path($weeklyReport->logo_left_path) }}" class="logo-left">
            @else
                <!-- Fallback logo if any -->
                <div class="logo-left" style="width:100px; height:60px;"></div>
            @endif
            <img src="{{ public_path('images/logoTelkom.webp') }}" class="logo-right">
            <div class="clear"></div>
        </div>

        <div class="title-section">
            <h3>PEKERJAAN</h3>
            <h1>{{ strtoupper($project->name) }}</h1>
            <br>
            <h3>{{ strtoupper($project->information->lokasi_project ?? 'LOKASI') }}</h3>
            <br><br><br>
            <h1>LAPORAN MINGGUAN</h1>
            <h3>(WEEKLY REPORT)</h3>
        </div>

        <div class="periode-section">
            <h2>PERIODE :<br>MINGGU KE</h2>
            <h1>{{ $weeklyReport->week_number }}</h1>
            <h2>{{ $weeklyReport->start_date->format('d F Y') }} hingga {{ $weeklyReport->end_date->format('d F Y') }}</h2>
        </div>

        <div class="year-section">
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
                <td style="border: none; text-align: left;">0,00</td>
            </tr>
            <tr>
                <td style="border: none; text-align: right; font-weight: bold;">Rencana Kumulatif</td>
                <td style="border: none;">=</td>
                <td style="border: none; text-align: left;">0,00</td>
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
                <td style="border: none; text-align: left;">0,00</td>
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

    <!-- PAGE 4: VISUAL -->
    <div>
        <table class="header-info" style="border: 1px solid black; width: 100%; margin-bottom: 20px;">
            <tr>
                <td colspan="4" class="text-center font-bold" style="border: 1px solid black; font-size: 14px;">LAPORAN VISUAL</td>
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

        <!-- Visual Grid 4x2 -->
        <table style="border: none;">
            @php $vIndex = 1; @endphp
            @for($i=0; $i<4; $i++)
                <tr>
                    @for($j=0; $j<2; $j++)
                        @php $v = $visuals[$vIndex] ?? null; @endphp
                        <td style="border: none; padding: 5px; width: 50%; text-align: center;">
                            <div style="border: 2px solid black; padding: 5px;">
                                <div style="height: 180px; background-color: #eee;">
                                    @if($v && $v->image_path)
                                        <img src="{{ public_path($v->image_path) }}" style="max-width: 100%; max-height: 180px; object-fit: contain;">
                                    @else
                                        <!-- No Image -->
                                    @endif
                                </div>
                                <div style="margin-top: 5px; font-weight: bold; font-size: 9px;">
                                    {{ $v ? $v->title : 'Visual ' . $vIndex }}
                                </div>
                            </div>
                        </td>
                        @php $vIndex++; @endphp
                    @endfor
                </tr>
            @endfor
        </table>
    </div>

</body>
</html>
