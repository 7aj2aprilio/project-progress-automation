<table>
    <thead>
        <tr>
            <th style="width: 25px; border: 1px solid black;">NO</th>
            <th style="width: 180px; border: 1px solid black;" class="text-left">URAIAN PEKERJAAN</th>
            <th style="width: 45px; border: 1px solid black;" class="text-right">BOBOT</th>
            @foreach($weeks as $w)
                <th style="border: 1px solid black;">
                    W-{{ $w->week_number }}<br>
                    <span style="font-size: 7px; font-weight: normal;">{{ $w->start_date ? $w->start_date->format('d/m') : '' }}-{{ $w->end_date ? $w->end_date->format('d/m') : '' }}</span>
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach($workItems as $mainItem)
            
            {{-- MAIN ITEM: RENCANA (TOP) --}}
            <tr class="main-row">
                <td rowspan="2" class="text-center" style="vertical-align: middle; border: 1px solid black; font-weight: bold; font-size: 8px;">{{ $no++ }}</td>
                <td rowspan="2" class="text-left" style="vertical-align: middle; border: 1px solid black; font-weight: bold; font-size: 8px;">{{ strtoupper($mainItem->name) }}</td>
                <td rowspan="2" class="text-right" style="vertical-align: middle; border: 1px solid black; font-weight: bold; font-size: 8px;">{{ number_format($mainItem->base_bobot, 2) }}</td>
                
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
                    <td class="text-center" style="border: 1px solid black; border-bottom: none; color: #475569;">{{ $sumPlan > 0 ? number_format($sumPlan, 2) : '-' }}</td>
                @endforeach
            </tr>
            
            {{-- MAIN ITEM: REALISASI (BOTTOM) --}}
            <tr class="main-row">
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
                    <td class="text-center" style="border: 1px solid black; border-top: none;">{{ $hasReal ? number_format($sumReal, 2) : '-' }}</td>
                @endforeach
            </tr>

            {{-- CHILDREN (ONLY SUB ITEMS) --}}
            @foreach($mainItem->children as $child)
                @if($child->type === 'sub')
                    {{-- SUB ITEM: RENCANA (TOP) --}}
                    <tr class="sub-row">
                        <td rowspan="2" style="border: 1px solid black;"></td>
                        <td rowspan="2" class="text-left pl-1" style="vertical-align: middle; border: 1px solid black; font-weight: bold; font-size: 8px;">• {{ strtoupper($child->name) }}</td>
                        <td rowspan="2" class="text-right" style="vertical-align: middle; border: 1px solid black; font-weight: bold; font-size: 8px;">{{ number_format($child->base_bobot, 2) }}</td>
                        
                        @foreach($weeks as $w)
                            @php
                                $subPlan = 0;
                                foreach($child->children as $leaf) {
                                    $k = $leaf->id . '_' . $w->id;
                                    $subPlan += isset($plans[$k]) ? (float) $plans[$k]->plan_value : 0;
                                }
                            @endphp
                            <td class="text-center" style="border: 1px solid black; border-bottom: none; color: #475569;">{{ $subPlan > 0 ? number_format($subPlan, 2) : '-' }}</td>
                        @endforeach
                    </tr>

                    {{-- SUB ITEM: REALISASI (BOTTOM) --}}
                    <tr class="sub-row">
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
                            <td class="text-center" style="border: 1px solid black; border-top: none;">{{ $hasSubReal ? number_format($subReal, 2) : '-' }}</td>
                        @endforeach
                    </tr>
                @endif
            @endforeach

        @endforeach
    </tbody>
    <tfoot>
        {{-- 1. RENCANA --}}
        <tr class="footer-rencana" style="font-weight: bold;">
            <td colspan="3" class="text-left" style="border: 1px solid black;">1. RENCANA</td>
            @foreach($weeks as $w)
                <td class="text-center" style="border: 1px solid black;">{{ number_format($summary['rencana'][$w->id] ?? 0, 2) }}</td>
            @endforeach
        </tr>

        {{-- 2. RENCANA KOMULATIF --}}
        <tr class="footer-ren-kom" style="font-weight: bold;">
            <td colspan="3" class="text-left" style="border: 1px solid black;">2. RENCANA KOMULATIF</td>
            @foreach($weeks as $w)
                <td class="text-center" style="border: 1px solid black;">{{ number_format($summary['rencana_komulatif'][$w->id] ?? 0, 2) }}</td>
            @endforeach
        </tr>

        {{-- 3. REALISASI --}}
        <tr class="footer-realisasi" style="font-weight: bold;">
            <td colspan="3" class="text-left" style="border: 1px solid black;">3. REALISASI</td>
            @foreach($weeks as $w)
                <td class="text-center" style="border: 1px solid black;">
                    {{ isset($summary['realisasi'][$w->id]) && $summary['realisasi'][$w->id] !== null ? number_format($summary['realisasi'][$w->id], 2) : '-' }}
                </td>
            @endforeach
        </tr>

        {{-- 4. REALISASI KOMULATIF --}}
        <tr class="footer-real-kom" style="font-weight: bold;">
            <td colspan="3" class="text-left" style="border: 1px solid black;">4. REALISASI KOMULATIF</td>
            @foreach($weeks as $w)
                <td class="text-center" style="border: 1px solid black;">
                    {{ isset($summary['realisasi_komulatif'][$w->id]) && $summary['realisasi_komulatif'][$w->id] !== null ? number_format($summary['realisasi_komulatif'][$w->id], 2) : '-' }}
                </td>
            @endforeach
        </tr>

        {{-- 5. DEVIASI --}}
        <tr class="footer-deviasi" style="font-weight: bold;">
            <td colspan="3" class="text-left" style="border: 1px solid black;">5. DEVIASI</td>
            @foreach($weeks as $w)
                @php $dev = $summary['deviasi'][$w->id] ?? null; @endphp
                <td class="text-center" style="border: 1px solid black;">
                    @if($dev !== null)
                        <span>
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
