<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Log Book</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px;
            vertical-align: top;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .holiday-row { background-color: #fee2e2; }
        .empty-row { color: #999; font-style: italic; }
        .holiday-badge {
            display: inline-block;
            background-color: #ef4444;
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            margin-bottom: 4px;
        }
        .attendance-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            color: #fff;
        }
        .images-container {
            margin-top: 10px;
        }
        .images-container img {
            max-width: 150px;
            max-height: 150px;
            margin-right: 5px;
            margin-bottom: 5px;
            border: 1px solid #ddd;
        }
        .page-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

    <h2>Laporan Log Book Harian</h2>

    @php
        $isSuperadminView = \Illuminate\Support\Facades\Auth::user()?->access_type == \App\Constants\UserConst::SUPERADMIN;
    @endphp

    <table>
        <thead>
            <tr>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 25%">Judul</th>
                <th style="width: 40%">Deskripsi</th>
                <th style="width: 20%">Bukti Kegiatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                @php
                    $isHoliday = $item->is_holiday ?? false;
                    $isWeekend = $item->is_weekend ?? false;
                    $isEmpty = $item->is_empty ?? false;
                    
                    // Jangan masukkan ke export jika hari libur/weekend dan tidak ada kegiatan
                    if ($isEmpty && ($isHoliday || $isWeekend)) {
                        continue;
                    }
                    
                    $rowClass = '';
                    if ($isHoliday || $isWeekend) $rowClass = 'holiday-row';
                    elseif ($isEmpty) $rowClass = 'empty-row';
                @endphp
                <tr class="page-break {{ $rowClass }}">
                    <td>{{ $item->log_date ? \Carbon\Carbon::parse($item->log_date)->locale('id')->translatedFormat('l, d M Y') : '-' }}</td>
                    
                    <td>
                        @if($isEmpty)
                            Belum Diisi
                        @else
                            @if($isHoliday)
                                <div class="holiday-badge">Libur Nasional: {{ $item->holiday_name }}</div><br>
                            @endif
                            <strong>{{ $item->title }}</strong>
                        @endif
                    </td>
                    <td>
                        @if(!$isEmpty)
                            {!! \Illuminate\Support\Str::markdown($item->description ?? '') !!}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if(!$isEmpty && isset($item->images) && count($item->images) > 0)
                            <div class="images-container">
                                @foreach($item->images as $img)
                                    @php
                                        $path = storage_path('app/public/' . $img->path);
                                        if (file_exists($path)) {
                                            $type = pathinfo($path, PATHINFO_EXTENSION);
                                            $dataStr = file_get_contents($path);
                                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataStr);
                                        } else {
                                            $base64 = '';
                                        }
                                    @endphp
                                    @if($base64)
                                        <img src="{{ $base64 }}" alt="Log Image">
                                    @endif
                                @endforeach
                            </div>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
