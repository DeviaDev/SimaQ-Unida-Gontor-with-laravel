<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Ujian Tahsin</title>
    
    
    <style>
        /* 🧾 Hilangkan semua margin bawaan dari halaman PDF */
        @page {
            margin: 0cm;
        }

        body {
            margin: 0cm;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        /* 🔹 Gambar kop surat full width */
        .kop img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin: 10px 0;
        }

        table {
            width: 95%;
            border-collapse: collapse;
            margin: 10px auto;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        .ttd {
            width: 95%;
            margin: 40px auto 0 auto;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="kop">
        <img src="{{ public_path('assets/img/kopsurat.png') }}" alt="Kop Surat">
    </div>

    <table>
        <thead>
            <tr>
                <th width="30px">No</th>
                <th>Nama Mahasiswi</th>
                <th>Prodi</th>
                <th width="50px">Sem.</th>
                <th>Materi</th>
                <th width="60px">Nilai Asli</th>
                <th width="80px">Nilai Remedial</th> </tr>
        </thead>
        <tbody>
            @foreach($remedialData as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-left">{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}</td>
                <td>{{ $item->prodi }}</td>
                <td>{{ $item->semester }}</td>
                <td>{{ $item->materi }}</td>
                <td><strong>{{ $item->nilai }}</strong></td>
                <td>
                    @if($item->nilai_remedial)
                        <span class="status-sudah">{{ $item->nilai_remedial }}</span>
                    @else
                        <span class="status-belum">Belum Ujian</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>