<!-- <!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: A4 portrait;
            margin: 140px 25px 30px 25px; /* atas kanan bawah kiri */
        }

        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background: #eee;
        }
    </style>
</head>
<body>

{{-- HEADER PDF --}}
@php
    $path = public_path('kop/kop-absensi.png');
    $base64 = null;

    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
@endphp

@if($base64)
<div style="
    position: fixed;
    top: -110px;
    left: 0;
    right: 0;
    text-align: center;
">
    <img src="{{ $base64 }}" style="width:100%; height:auto;">
</div>
@endif
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Prodi</th>
            <th>Semester</th>
            @foreach($pertemuan as $p)
                <th>P{{ $p }}</th>
            @endforeach
            <th>Total Hadir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mahasiswi as $m)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $m->nama }}</td>
            <td>{{ $m->prodi }}</td>
            <td>{{ $m->semester }}</td>

            @foreach($pertemuan as $p)
                <td>
                    {{ $m->riwayat[$p] ?? '-' }}
                </td>
            @endforeach

            <td>{{ $m->total_hadir }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html> -->
