<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Time Schedule - {{ $project->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #777;
            padding: 4px;
            word-wrap: break-word;
        }
        th {
            background-color: #f0f0f0;
            text-align: center;
        }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .pl-2 { padding-left: 10px; }
        .pl-4 { padding-left: 20px; }
        
        .main-work { background-color: #e5e7eb; font-weight: bold; }
        .sub-work { font-weight: bold; }
        .item-work { color: #4b5563; }
        
        .col-task { width: 30%; }
        
        /* Cell colors */
        .cell-main { background-color: #10b981; }
        .cell-sub { background-color: #10b981; }
        .cell-item { background-color: #10b981; }
        .cell-empty { background-color: transparent; }
    </style>
</head>
<body>
    <h2>Time Schedule (Gantt Chart)<br>{{ $project->name }}</h2>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="col-task">PROJECT MANAGEMENT</th>
                @foreach($projectMonths as $month)
                    <th colspan="{{ $month['weeks_count'] }}">{{ $month['name'] }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach($projectMonths as $month)
                    @for($w = 1; $w <= $month['weeks_count']; $w++)
                        <th>W{{ $w }}</th>
                    @endfor
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($workItems as $main)
                {{-- Main Work --}}
                <tr class="main-work">
                    <td class="text-left">{{ $main->name }}</td>
                    @foreach($projectMonths as $month)
                        @for($w = 1; $w <= $month['weeks_count']; $w++)
                            <td class="text-center @if(in_array($main->id.'_'.$month['key'].'_'.$w, $ganttData)) cell-main @endif"></td>
                        @endfor
                    @endforeach
                </tr>

                @foreach($main->children as $sub)
                    @if($sub->type === 'sub')
                        {{-- Sub Work --}}
                        <tr class="sub-work">
                            <td class="text-left pl-2">{{ $sub->name }}</td>
                            @foreach($projectMonths as $month)
                                @for($w = 1; $w <= $month['weeks_count']; $w++)
                                    <td class="text-center @if(in_array($sub->id.'_'.$month['key'].'_'.$w, $ganttData)) cell-sub @endif"></td>
                                @endfor
                            @endforeach
                        </tr>

                        @foreach($sub->children as $item)
                            {{-- Item Work --}}
                            <tr class="item-work">
                                <td class="text-left pl-4">- {{ $item->name }}</td>
                                @foreach($projectMonths as $month)
                                    @for($w = 1; $w <= $month['weeks_count']; $w++)
                                        <td class="text-center @if(in_array($item->id.'_'.$month['key'].'_'.$w, $ganttData)) cell-item @endif"></td>
                                    @endfor
                                @endforeach
                            </tr>
                        @endforeach
                    @else
                        {{-- Direct Item Work --}}
                        <tr class="item-work">
                            <td class="text-left pl-2">- {{ $sub->name }}</td>
                            @foreach($projectMonths as $month)
                                @for($w = 1; $w <= $month['weeks_count']; $w++)
                                    <td class="text-center @if(in_array($sub->id.'_'.$month['key'].'_'.$w, $ganttData)) cell-item @endif"></td>
                                @endfor
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
