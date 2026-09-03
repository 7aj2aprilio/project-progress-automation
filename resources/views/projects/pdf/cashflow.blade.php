<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cashflow Project - {{ $project->name }}</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #1e293b;
        }
        .header h3 {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #64748b;
        }
        .section-title {
            font-weight: bold;
            font-size: 12px;
            background-color: #1e293b;
            color: white;
            padding: 4px 8px;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        
        /* Kesimpulan Table */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 8px;
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
        .summary-table .bg-red { background-color: #fee2e2; color: #7f1d1d; }
        .summary-table .bg-dark { background-color: #1e293b; color: white; }
        
        /* Cashflow Table */
        .cf-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Courier New', Courier, monospace;
            font-size: 9px;
        }
        .cf-table th, .cf-table td {
            border: 1px solid #cbd5e1;
            padding: 3px 4px;
        }
        .cf-table th {
            background-color: #1e293b;
            color: white;
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 9px;
        }
        .cf-table .text-right { text-align: right; }
        .cf-table .text-center { text-align: center; }
        .cf-table .font-bold { font-weight: bold; }
        .cf-table .bg-section { background-color: #991b1b; color: white; font-weight: bold; font-family: Arial, sans-serif;}
        .cf-table .bg-sub { background-color: #e2e8f0; font-weight: bold; font-family: Arial, sans-serif;}
        .cf-table .bg-yellow { background-color: #fef9c3; }
        .cf-table .pl-4 { padding-left: 15px; }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Cashflow Project: {{ $project->name }}</h1>
        <h3>{{ $project->information->nama_pelanggan ?? '-' }}</h3>
    </div>

    <div class="section-title">KESIMPULAN ANALISIS KELAYAKAN PROJECT</div>
    <table class="summary-table">
        <tbody>
            <tr>
                <td class="font-bold" style="width: 33%;">REVENUE</td>
                <td style="width: 17%;"></td>
                <td class="text-right font-bold" style="width: 50%;">Rp {{ number_format($project->total_revenue, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="font-bold">TOTAL BEBAN/CASH OUT</td>
                <td></td>
                <td class="text-right font-bold">Rp {{ number_format($project->total_cost, 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-blue font-bold">
                <td>GROSS MARGIN</td>
                <td class="text-center">{{ number_format($project->gross_margin_percentage, 2, ',', '.') }}%</td>
                <td class="text-right">Rp {{ number_format($project->gross_margin, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pajak PPH</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($project->total_pph, 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-green font-bold">
                <td>GROSS MARGIN + PPH</td>
                <td class="text-center">{{ number_format($project->gross_margin_pph_percentage, 2, ',', '.') }}%</td>
                <td class="text-right">Rp {{ number_format($project->gross_margin_pph, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Provisi</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($project->provisi, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bunga Pinjaman</td>
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
                <td>CF selama kontrak</td>
                <td class="text-center">{{ $project->has_cf_negatif ? 1 : 0 }}</td>
                <td class="text-center font-bold {{ $project->has_cf_negatif ? 'bg-red' : '' }}">
                    {{ $project->has_cf_negatif ? 'Terdapat CF Negatif' : 'All CF Positif' }}
                </td>
            </tr>
            <tr class="{{ $project->kelayakan === 'Layak' ? 'bg-green' : 'bg-red' }} font-bold">
                <td class="text-center">KESIMPULAN</td>
                <td class="text-center" colspan="2">
                    {{ $project->kesimpulan_kelayakan_detail }}
                </td>
            </tr>
            <tr>
                <td class="font-bold">REVENUE INCL PPN</td>
                <td class="text-center font-bold">11/12*{{ \App\Models\GlobalSetting::getValue('ppn_rate', 12) }}%</td>
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
                <th style="width: 150px; text-align: left;">Jangka Waktu (Bulan)</th>
                <th style="width: 80px; text-align: right;">Total</th>
                @foreach($project->cashflows as $cf)
                    <th>
                        Bulan {{ $cf->month_index }}<br>
                        <span style="font-size: 7px; font-weight: normal;">
                            {{ $cf->month_date ? \Carbon\Carbon::parse($cf->month_date)->format('M Y') : '-' }}
                        </span>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{-- SECTION 1: TOP (progress) --}}
            <tr>
                <td class="bg-section" colspan="{{ count($project->cashflows) + 2 }}">TOP (progress)</td>
            </tr>
            <tr>
                <td>% PROGRESS PEKERJAAN</td>
                <td class="text-right font-bold">{{ number_format($project->cashflows->max('pct_progress'), 1, '.', '') }}%</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-center">{{ number_format($cf->pct_progress, 1, '.', '') }}%</td>
                @endforeach
            </tr>
            <tr class="bg-yellow">
                <td>% TOP PELANGGAN</td>
                <td class="text-right font-bold">{{ number_format($project->cashflows->sum('pct_top_pelanggan'), 1, '.', '') }}%</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-center font-bold">{{ number_format($cf->pct_top_pelanggan, 1, '.', '') }}%</td>
                @endforeach
            </tr>
            <tr class="bg-yellow">
                <td>% TOP KEPADA MITRA</td>
                <td class="text-right font-bold">{{ number_format($project->cashflows->sum('pct_top_mitra'), 1, '.', '') }}%</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-center font-bold">{{ number_format($cf->pct_top_mitra, 1, '.', '') }}%</td>
                @endforeach
            </tr>

            {{-- SECTION 2: REVENUE STREAM / CASH IN --}}
            <tr>
                <td class="bg-sub">REVENUE STREAM / CASH IN</td>
                <td class="text-right font-bold bg-sub">Rp {{ number_format($project->cashflows->sum('cash_in'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right font-bold bg-sub">Rp {{ number_format($cf->cash_in, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-4">Jasa Pelaksanaan konstruksi</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('jasa_konstruksi'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->jasa_konstruksi, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-4">Management Fee GSD</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('management_fee'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->management_fee, 0, ',', '.') }}</td>
                @endforeach
            </tr>

            {{-- SECTION 3: COST STRUCTURE / CASH OUT --}}
            <tr>
                <td class="bg-sub">COST STRUCTURE / CASH OUT</td>
                <td class="text-right font-bold bg-sub">Rp {{ number_format($project->cashflows->sum('cash_out'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right font-bold bg-sub">Rp {{ number_format($cf->cash_out, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-4">Biaya Mitra Pelaksana</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('biaya_mitra'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->biaya_mitra, 0, ',', '.') }}</td>
                @endforeach
            </tr>

            {{-- SECTION 4: BEBAN LAINNYA --}}
            <tr>
                <td class="bg-section" colspan="{{ count($project->cashflows) + 2 }}">BEBAN LAINNYA</td>
            </tr>
            <tr>
                <td class="pl-4">Fee Fasilitas Jaminan</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('fee_jaminan'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->fee_jaminan, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-4">Admin Fasilitas Jaminan</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('admin_jaminan'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->admin_jaminan, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-4">CAR / Assurance</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('car'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->car, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-4">Iuran Jasa (LJK)</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('iuran_jasa'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->iuran_jasa, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-4">Biaya Pengawasan</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('biaya_pengawasan'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->biaya_pengawasan, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-4">BOP Project</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('bop_project'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->bop_project, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            
            {{-- NET CASH FLOW --}}
            <tr>
                <td class="bg-sub" style="font-size: 10px;">NET CASH FLOW BULANAN</td>
                <td class="bg-sub text-right font-bold" style="font-size: 10px;">Rp {{ number_format($project->net_cash_flow, 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right font-bold bg-sub" style="font-size: 10px;">Rp {{ number_format($cf->net_cashflow, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="bg-sub" style="font-size: 10px;">KUMULATIF NET CASHFLOW</td>
                <td class="bg-sub text-right font-bold" style="font-size: 10px;">-</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right font-bold bg-sub {{ $cf->kumulatif_net_cashflow < 0 ? 'bg-red' : '' }}" style="font-size: 10px;">
                        Rp {{ number_format($cf->kumulatif_net_cashflow, 0, ',', '.') }}
                    </td>
                @endforeach
            </tr>
            
            {{-- LOAN --}}
            <tr>
                <td class="bg-section" colspan="{{ count($project->cashflows) + 2 }}">LOAN AMORTIZATION</td>
            </tr>
            <tr>
                <td class="pl-4">Penerimaan Pinjaman</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('loan_penerimaan'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->loan_penerimaan, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-4">Pembayaran Pokok</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('loan_pembayaran_pokok'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->loan_pembayaran_pokok, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-4">Saldo Pinjaman Terhutang</td>
                <td class="text-right">-</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->loan_saldo_terhutang, 0, ',', '.') }}</td>
                @endforeach
            </tr>
            <tr>
                <td class="pl-4">Beban Bunga Bulanan</td>
                <td class="text-right">Rp {{ number_format($project->cashflows->sum('loan_beban_bunga'), 0, ',', '.') }}</td>
                @foreach($project->cashflows as $cf)
                    <td class="text-right">Rp {{ number_format($cf->loan_beban_bunga, 0, ',', '.') }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>
    @else
        <p>Data cashflow belum tersedia.</p>
    @endif

</body>
</html>
