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

    @include('projects.pdf.partials.s_curve_table')

    @if(isset($chartBase64) && $chartBase64)
        <div style="margin-top: 40px; text-align: center; border: 1px solid #cbd5e1; padding: 20px; background-color: #f8fafc; border-radius: 8px;">
            <img src="{{ $chartBase64 }}" alt="S-Curve Chart" style="max-width: 100%; height: auto;">
        </div>
    @endif
</body>
</html>
