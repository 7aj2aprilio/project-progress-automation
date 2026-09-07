<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cashflow Project - {{ $project->name }}</title>
    <style>
        @page {
            margin: 15px 20px;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8.5px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #0f172a;
        }
        .header h3 {
            margin: 3px 0 0 0;
            font-size: 11px;
            color: #475569;
            font-weight: normal;
        }
        .section-title {
            font-weight: bold;
            font-size: 10.5px;
            background-color: #0f172a;
            color: white;
            padding: 4px 8px;
            margin-top: 10px;
            margin-bottom: 6px;
            border-radius: 3px;
        }
        
        /* Kesimpulan Table */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #cbd5e1;
            padding: 3.5px 7px;
            text-align: left;
        }
        .summary-table th {
            background-color: #f1f5f9;
            font-weight: bold;
            text-align: center;
        }
        .summary-table .text-right { text-align: right; }
        .summary-table .text-center { text-align: center; }
        .summary-table .font-bold { font-weight: bold; }
        .summary-table .bg-blue { background-color: #dbeafe; }
        .summary-table .bg-green { background-color: #d1fae5; }
        .summary-table .bg-red { background-color: #fee2e2; color: #991b1b; }
        .summary-table .bg-dark { background-color: #0f172a; color: white; }
        
        /* Cashflow Table */
        .cf-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5px;
        }
        .cf-table th, .cf-table td {
            border: 1px solid #cbd5e1;
            padding: 2.5px 3.5px;
            line-height: 1.15;
        }
        .cf-table thead th {
            background-color: #0f172a;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            font-size: 8px;
        }
        .cf-table thead th.th-total {
            background-color: #1e293b;
        }
        .cf-table .text-right { text-align: right; }
        .cf-table .text-center { text-align: center; }
        .cf-table .font-bold { font-weight: bold; }
        .cf-table .font-black { font-weight: 900; }
        
        /* Row background highlights */
        .cf-table .bg-top-header { background-color: #991b1b; color: #ffffff; font-weight: bold; text-transform: uppercase; }
        .cf-table .bg-yellow-light { background-color: #fefce8; }
        .cf-table .bg-yellow-dark { background-color: #fef08a; }
        .cf-table .bg-cashin { background-color: #e2e8f0; font-weight: bold; }
        .cf-table .bg-beban-header { background-color: #f1f5f9; font-weight: bold; text-transform: uppercase; color: #1e293b; }
        .cf-table .bg-gross-margin { background-color: #1e293b; color: #ffffff; font-weight: bold; }
        .cf-table .bg-ppn-header { background-color: #f1f5f9; font-weight: bold; color: #334155; }
        .cf-table .bg-kredit-ppn { background-color: #f3e8ff; color: #581c87; font-weight: bold; }
        .cf-table .bg-gross-pph { background-color: #d1fae5; color: #064e3b; font-weight: bold; }
        .cf-table .bg-cash-margin { background-color: #e0e7ff; color: #1e1b4b; font-weight: bold; }
        .cf-table .bg-cf-kumulatif { background-color: #0f172a; color: #ffffff; font-weight: bold; }
        .cf-table .text-kumulatif-pos { color: #34d399; font-weight: bold; }
        .cf-table .text-kumulatif-neg { color: #f87171; font-weight: bold; }
        .cf-table .pl-3 { padding-left: 10px; }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Cashflow Project: {{ $project->name }}</h1>
        <h3>{{ $project->information->nama_pelanggan ?? '-' }} | Kategori: {{ $project->information->kategori_project ?? '-' }} | Lokasi: {{ $project->information->lokasi_project ?? '-' }}</h3>
    </div>

    <div class="section-title">KESIMPULAN ANALISIS KELAYAKAN PROJECT</div>
    <table class="summary-table">
        <tbody>
            <tr>
                <td class="font-bold" style="width: 35%;">REVENUE</td>
                <td style="width: 15%;"></td>
                <td class="text-right font-bold" style="width: 50%;">Rp {{ number_format($project->total_revenue, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold">TOTAL BEBAN / CASH OUT</td>
                <td></td>
                <td class="text-right font-bold">Rp {{ number_format($project->total_cost, 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-blue font-bold">
                <td>GROSS MARGIN / EBIT</td>
                <td class="text-center">{{ number_format($project->gross_margin_percentage, 2, ',', '.') }}%</td>
                <td class="text-right">Rp {{ number_format($project->gross_margin, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pengurangan PPh 23</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($project->total_pph, 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-green font-bold">
                <td>GROSS MARGIN + PPH</td>
                <td class="text-center">{{ number_format($project->gross_margin_pph_percentage, 2, ',', '.') }}%</td>
                <td class="text-right">Rp {{ number_format($project->gross_margin_pph, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Biaya Provisi</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($project->provisi, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Beban Bunga Pinjaman</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($project->bunga_pinjaman, 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-blue font-bold">
                <td>NET INCOME</td>
                <td class="text-center">{{ number_format($project->net_income_percentage, 2, ',', '.') }}%</td>
                <td class="text-right">Rp {{ number_format($project->net_income, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kredit PPN</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($project->kredit_ppn, 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-blue font-bold">
                <td>NET CASH FLOW</td>
                <td class="text-center">{{ $project->net_cash_flow > 0 ? 'Cashflow Positif' : 'Cashflow Negatif' }}</td>
                <td class="text-right">Rp {{ number_format($project->net_cash_flow, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>CF Kumulatif Negatif Selama Kontrak</td>
                <td class="text-center">{{ $project->has_cf_negatif ? 1 : 0 }}</td>
                <td class="text-center font-bold {{ $project->has_cf_negatif ? 'bg-red' : 'bg-green' }}">
                    {{ $project->has_cf_negatif ? '⚠️ Terdapat CF Negatif' : '✅ All CF Positif' }}
                </td>
            </tr>
            <tr class="{{ $project->kelayakan === 'Layak' ? 'bg-green' : 'bg-red' }} font-bold">
                <td class="text-center">KESIMPULAN KELAYAKAN</td>
                <td class="text-center" colspan="2">
                    {{ $project->kesimpulan_kelayakan_detail }}
                </td>
            </tr>
            <tr>
                <td class="font-bold">REVENUE INCL PPN</td>
                <td class="text-center font-bold">11/12 × {{ \App\Models\GlobalSetting::getValue('ppn_rate', 12) }}%</td>
                <td class="text-right font-bold">Rp {{ number_format($project->revenue_incl_ppn, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">TABEL CASHFLOW PROJECT BULANAN</div>
    
    @if(count($project->cashflows) > 0)
    <table class="cf-table">
        <thead>
            <tr>
                <th style="width: 170px; text-align: left;">Jangka Waktu (Bulan)</th>
                <th class="th-total" style="width: 85px; text-align: right;">Total</th>
                @foreach($project->cashflows as $cf)
                    <th>
                        Bulan {{ $cf->month_index }}<br>
                        <span style="font-size: 6.5px; font-weight: normal; color: #94a3b8;">
                            {{ $cf->month_date ? \Carbon\Carbon::parse($cf->month_date)->format('M Y') : '-' }}
                        </span>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- SECTION 1: TOP (progress) --}}
            <tr class="bg-top-header">
                <td colspan="{{ count($project->cashflows) + 2 }}">TOP (progress)</td>
            </tr>
            <tr>
                <td>% PROGRESS PEKERJAAN</td>
                <td class="text-right font-bold">{{ number_format($project->cashflows->max('pct_progress'), 1, '.', '') }}%</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-center">{{ number_format($cf->pct_progress, 1, '.', '') }}%</td>
                @endforeach
            </tr>
            <tr class="bg-yellow-light">
                <td class="font-bold">% TERM OF PAYMENT PELANGGAN</td>
                <td class="text-right font-bold bg-yellow-dark">{{ number_format($project->cashflows->sum('pct_top_pelanggan'), 1, '.', '') }}%</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-center font-bold">{{ number_format($cf->pct_top_pelanggan, 1, '.', '') }}%</td>
                @endforeach
            </tr>
            <tr class="bg-yellow-light">
                <td class="font-bold">% TERM OF PAYMENT KEPADA MITRA</td>
                <td class="text-right font-bold bg-yellow-dark">{{ number_format($project->cashflows->sum('pct_top_mitra'), 1, '.', '') }}%</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-center font-bold">{{ number_format($cf->pct_top_mitra, 1, '.', '') }}%</td>
                @endforeach
            </tr>

            {{-- SECTION 2: REVENUE STREAM / CASH IN --}}
            <tr class="bg-cashin">
                <td>REVENUE STREAM / CASH IN</td>
                <td class="text-right font-black">Rp {{ number_format($project->cashflows->sum('cash_in'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right font-bold">Rp {{ number_format($cf->cash_in, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">Jasa Pelaksanaan konstruksi</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('jasa_konstruksi'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->jasa_konstruksi, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">Management Fee GSD</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('management_fee'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->management_fee, 0, ',', '.') }}</td>
                @endforeach
            </tr>

            {{-- SECTION 3: COST STRUCTURE / CASH OUT --}}
            <tr class="bg-cashin">
                <td>COST STRUCTURE / CASH OUT</td>
                <td class="text-right font-black">Rp {{ number_format($project->cashflows->sum('cash_out'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right font-bold">Rp {{ number_format($cf->cash_out, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">Biaya Mitra Pelaksana (Exclude PPN)</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('biaya_mitra'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->biaya_mitra, 0, ',', '.') }}</td>
                @endforeach
            </tr>

            {{-- SECTION 4: BEBAN LAINNYA --}}
            <tr class="bg-beban-header">
                <td colspan="{{ count($project->cashflows) + 2 }}">BEBAN LAINNYA</td>
            </tr>
            <tr>
                <td class="pl-3">Fee Fasilitas Jaminan</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('fee_jaminan'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->fee_jaminan, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">Admin Fasilitas Jaminan</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('admin_jaminan'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->admin_jaminan, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">Construction Assurance Risk ( CAR)</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('car'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->car, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">Iuran Jasa Konstruksi</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('iuran_jasa'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->iuran_jasa, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">Biaya Pengawasan</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('biaya_pengawasan'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->biaya_pengawasan, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">BOP Project</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('bop_project'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->bop_project, 0, ',', '.') }}</td>
                @endforeach
            </tr>

            {{-- SECTION 5: GROSS MARGIN / EBIT --}}
            <tr class="bg-gross-margin">
                <td>GROSS MARGIN / EBIT</td>
                <td class="text-right font-black">Rp {{ number_format($project->cashflows->sum('gross_margin'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right font-bold">Rp {{ number_format($cf->gross_margin, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">Pengurangan pph 23</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('pph'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->pph, 0, ',', '.') }}</td>
                @endforeach
            </tr>

            {{-- SECTION 6: PPN --}}
            <tr class="bg-ppn-header">
                <td colspan="{{ count($project->cashflows) + 2 }}">PPn yang harus dibayarkan</td>
            </tr>
            <tr>
                <td class="pl-3">PPn Keluaran</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('ppn_keluaran'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->ppn_keluaran, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">PPn Masukan</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('ppn_masukan'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->ppn_masukan, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr class="bg-kredit-ppn">
                <td class="pl-3">Kredit PPn</td>
                <td class="text-right font-black">Rp {{ number_format($project->cashflows->sum('kredit_ppn'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right font-bold">Rp {{ number_format($cf->kredit_ppn, 0, ',', '.') }}</td>
                @endforeach
            </tr>

            {{-- SECTION 7: GROSS MARGIN + PPH --}}
            <tr class="bg-gross-pph">
                <td>GROSS MARGIN + PPH</td>
                <td class="text-right font-black">Rp {{ number_format($project->cashflows->sum('gross_margin') - $project->cashflows->sum('pph'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right font-bold">Rp {{ number_format($cf->gross_margin - $cf->pph, 0, ',', '.') }}</td>
                @endforeach
            </tr>

            {{-- SECTION 8: PINJAMAN --}}
            <tr>
                <td class="pl-3">Penarikan Pinjaman</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('penarikan_pinjaman'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->penarikan_pinjaman, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">Pembayaran Pokok</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('pembayaran_pokok'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->pembayaran_pokok, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">Biaya Provisi</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('biaya_provisi'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->biaya_provisi, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-3">Beban Bunga</td>
                <td class="text-right font-bold">Rp {{ number_format($project->cashflows->sum('beban_bunga'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->beban_bunga, 0, ',', '.') }}</td>
                @endforeach
            </tr>

            {{-- SECTION 9: CASH MARGIN & KUMULATIF --}}
            <tr class="bg-cash-margin">
                <td>CASH MARGIN</td>
                <td class="text-right font-black">Rp {{ number_format($project->cashflows->sum('cash_margin'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right font-bold">Rp {{ number_format($cf->cash_margin, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr class="bg-cf-kumulatif">
                <td>CASH FLOW KUMULATIF</td>
                <td class="text-right font-black" style="color: #fde047;">Rp {{ number_format($project->cashflows->last()->cash_flow_kumulatif ?? 0, 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right {{ $cf->cash_flow_kumulatif < 0 ? 'text-kumulatif-neg' : 'text-kumulatif-pos' }}">
                        Rp {{ number_format($cf->cash_flow_kumulatif, 0, ',', '.') }}
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
    @else
        <p style="text-align: center; color: #64748b; padding: 20px;">Data cashflow belum tersedia.</p>
    @endif

</body>
</html>
