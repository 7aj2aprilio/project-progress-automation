<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Time Schedule & Kurva S - {{ $project->name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
        }
        .header h2 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
            color: #0f172a;
        }
        .header p {
            margin: 3px 0 0 0;
            font-size: 9px;
            color: #475569;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 0.5px solid #94a3b8;
            padding: 2.5px 3px;
            word-wrap: break-word;
        }
        th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
        }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .main-row { background-color: #e2e8f0; font-weight: bold; }
        .sub-row { background-color: #f8fafc; font-weight: bold; }
        .pl-1 { padding-left: 8px; }
        .pl-2 { padding-left: 16px; }

        .bg-hijau { background-color: #ecfdf5; color: #065f46; }
        .bg-biru { background-color: #f0f9ff; color: #0369a1; }
        
        .footer-rencana { background-color: #fef3c7; font-weight: bold; }
        .footer-ren-kom { background-color: #fde68a; font-weight: bold; }
        .footer-realisasi { background-color: #d1fae5; font-weight: bold; }
        .footer-real-kom { background-color: #a7f3d0; font-weight: bold; }
        .footer-deviasi { background-color: #0f172a; color: #ffffff; font-weight: bold; }

        .text-green { color: #10b981; }
        .text-red { color: #f43f5e; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>TIME SCHEDULE & REKAPITULASI KURVA S</h2>
        <p>Proyek: <strong>{{ $project->name }}</strong> | Dicetak pada: {{ date('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">NO</th>
                <th style="width: 180px;" class="text-left">URAIAN PEKERJAAN</th>
                <th style="width: 45px;" class="text-right">BOBOT</th>
                @foreach($weeks as $w)
                    <th>
                        W-{{ $w->week_number }}<br>
                        <span style="font-size: 7px; font-weight: normal;">{{ $w->start_date ? $w->start_date->format('d/m') : '' }}-{{ $w->end_date ? $w->end_date->format('d/m') : '' }}</span>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($workItems as $mainItem)
                
                {{-- MAIN ITEM REALISASI --}}
                <tr class="main-row">
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-left">{{ $mainItem->name }}</td>
                    <td class="text-right">{{ number_format($mainItem->base_bobot, 2) }}</td>
                    @foreach($weeks as $w)
                        @php
                            $sumReal = 0;
                            $hasReal = false;
                            foreach($mainItem->children as $child) {
                                if ($child->type === 'sub') {
                                    foreach($child->children as $leaf) {
                                        if (isset($realisasiMap[$leaf->id][$w->id])) {
                                            $sumReal += $realisasiMap[$leaf->id][$w->id];
                                            $hasReal = true;
                                        }
                                    }
                                } else {
                                    if (isset($realisasiMap[$child->id][$w->id])) {
                                        $sumReal += $realisasiMap[$child->id][$w->id];
                                        $hasReal = true;
                                    }
                                }
                            }
                        @endphp
                        <td class="text-center bg-hijau">{{ $hasReal ? number_format($sumReal, 2) : '-' }}</td>
                    @endforeach
                </tr>

                {{-- MAIN ITEM RENCANA --}}
                <tr style="background-color: #f1f5f9;">
                    <td></td>
                    <td class="text-left pl-1" style="font-style: italic; color: #64748b;">↳ Rencana (Subtotal)</td>
                    <td></td>
                    @foreach($weeks as $w)
                        @php
                            $sumPlan = 0;
                            foreach($mainItem->children as $child) {
                                if ($child->type === 'sub') {
                                    foreach($child->children as $leaf) {
                                        $k = $leaf->id . '_' . $w->id;
                                        $sumPlan += isset($plans[$k]) ? (float) $plans[$k]->plan_value : 0;
                                    }
                                } else {
                                    $k = $child->id . '_' . $w->id;
                                    $sumPlan += isset($plans[$k]) ? (float) $plans[$k]->plan_value : 0;
                                }
                            }
                        @endphp
                        <td class="text-center bg-biru font-bold">{{ $sumPlan > 0 ? number_format($sumPlan, 2) : '-' }}</td>
                    @endforeach
                </tr>

                {{-- CHILDREN --}}
                @foreach($mainItem->children as $child)
                    @if($child->type === 'sub')
                        {{-- SUB ITEM REALISASI --}}
                        <tr class="sub-row">
                            <td></td>
                            <td class="text-left pl-1">• {{ $child->name }}</td>
                            <td class="text-right">{{ number_format($child->base_bobot, 2) }}</td>
                            @foreach($weeks as $w)
                                @php
                                    $subReal = 0;
                                    $hasSubReal = false;
                                    foreach($child->children as $leaf) {
                                        if (isset($realisasiMap[$leaf->id][$w->id])) {
                                            $subReal += $realisasiMap[$leaf->id][$w->id];
                                            $hasSubReal = true;
                                        }
                                    }
                                @endphp
                                <td class="text-center bg-hijau">{{ $hasSubReal ? number_format($subReal, 2) : '-' }}</td>
                            @endforeach
                        </tr>

                        {{-- SUB ITEM RENCANA --}}
                        <tr>
                            <td></td>
                            <td class="text-left pl-2" style="font-size: 7px; color: #94a3b8; font-style: italic;">↳ Rencana (Sub)</td>
                            <td></td>
                            @foreach($weeks as $w)
                                @php
                                    $subPlan = 0;
                                    foreach($child->children as $leaf) {
                                        $k = $leaf->id . '_' . $w->id;
                                        $subPlan += isset($plans[$k]) ? (float) $plans[$k]->plan_value : 0;
                                    }
                                @endphp
                                <td class="text-center bg-biru">{{ $subPlan > 0 ? number_format($subPlan, 2) : '-' }}</td>
                            @endforeach
                        </tr>

                        {{-- LEAF ITEMS OF SUB --}}
                        @foreach($child->children as $leaf)
                            <tr>
                                <td></td>
                                <td class="text-left pl-2">{{ $leaf->name }}</td>
                                <td class="text-right">{{ number_format($leaf->base_bobot, 2) }}</td>
                                @foreach($weeks as $w)
                                    <td class="text-center bg-hijau">
                                        {{ isset($realisasiMap[$leaf->id][$w->id]) ? number_format($realisasiMap[$leaf->id][$w->id], 2) : '-' }}
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-left pl-2" style="font-size: 7px; color: #94a3b8; font-style: italic;">↳ Rencana</td>
                                <td></td>
                                @foreach($weeks as $w)
                                    @php $k = $leaf->id . '_' . $w->id; @endphp
                                    <td class="text-center bg-biru">
                                        {{ isset($plans[$k]) && $plans[$k]->plan_value > 0 ? number_format($plans[$k]->plan_value, 2) : '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                    @else
                        {{-- DIRECT LEAF ITEM --}}
                        <tr>
                            <td></td>
                            <td class="text-left pl-1">{{ $child->name }}</td>
                            <td class="text-right">{{ number_format($child->base_bobot, 2) }}</td>
                            @foreach($weeks as $w)
                                <td class="text-center bg-hijau">
                                    {{ isset($realisasiMap[$child->id][$w->id]) ? number_format($realisasiMap[$child->id][$w->id], 2) : '-' }}
                                </td>
                            @endforeach
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-left pl-1" style="font-size: 7px; color: #94a3b8; font-style: italic;">↳ Rencana</td>
                            <td></td>
                            @foreach($weeks as $w)
                                @php $k = $child->id . '_' . $w->id; @endphp
                                <td class="text-center bg-biru">
                                    {{ isset($plans[$k]) && $plans[$k]->plan_value > 0 ? number_format($plans[$k]->plan_value, 2) : '-' }}
                                </td>
                            @endforeach
                        </tr>
                    @endif
                @endforeach

            @endforeach
        </tbody>
        <tfoot>
            {{-- 1. RENCANA --}}
            <tr class="footer-rencana">
                <td colspan="3" class="text-left">1. RENCANA</td>
                @foreach($weeks as $w)
                    <td class="text-center">{{ number_format($summary['rencana'][$w->id] ?? 0, 2) }}</td>
                @endforeach
            </tr>

            {{-- 2. RENCANA KOMULATIF --}}
            <tr class="footer-ren-kom">
                <td colspan="3" class="text-left">2. RENCANA KOMULATIF</td>
                @foreach($weeks as $w)
                    <td class="text-center">{{ number_format($summary['rencana_komulatif'][$w->id] ?? 0, 2) }}</td>
                @endforeach
            </tr>

            {{-- 3. REALISASI --}}
            <tr class="footer-realisasi">
                <td colspan="3" class="text-left">3. REALISASI</td>
                @foreach($weeks as $w)
                    <td class="text-center">
                        {{ isset($summary['realisasi'][$w->id]) && $summary['realisasi'][$w->id] !== null ? number_format($summary['realisasi'][$w->id], 2) : '-' }}
                    </td>
                @endforeach
            </tr>

            {{-- 4. REALISASI KOMULATIF --}}
            <tr class="footer-real-kom">
                <td colspan="3" class="text-left">4. REALISASI KOMULATIF</td>
                @foreach($weeks as $w)
                    <td class="text-center">
                        {{ isset($summary['realisasi_komulatif'][$w->id]) && $summary['realisasi_komulatif'][$w->id] !== null ? number_format($summary['realisasi_komulatif'][$w->id], 2) : '-' }}
                    </td>
                @endforeach
            </tr>

            {{-- 5. DEVIASI --}}
            <tr class="footer-deviasi">
                <td colspan="3" class="text-left">5. DEVIASI</td>
                @foreach($weeks as $w)
                    @php $dev = $summary['deviasi'][$w->id] ?? null; @endphp
                    <td class="text-center">
                        @if($dev !== null)
                            <span class="{{ $dev >= 0 ? 'text-green' : 'text-red' }}">
                                {{ ($dev > 0 ? '+' : '') . number_format($dev, 2) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                @endforeach
            </tr>
        </tfoot>
    </table>
</body>
</html>
