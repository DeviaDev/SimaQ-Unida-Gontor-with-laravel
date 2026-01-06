<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Ujian</title>
    
    
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

    <h2>Data Mahasiswi</h2>

    <table>
    <thead>
        <tr>
            <th width="25" align="center">No</th>
            <th width="25" align="center">NIM</th>
            <th width="25" align="center">Nama Mahasiswi</th>
            <th width="25" align="center">Program Studi</th>
            <th width="25" align="center">Semester</th>
            <th width="25" align="center">Materi</th>
            <th width="25" align="center">Keterangan Ujian</th>
            <th width="25" align="center">Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mandiriData as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nim }}</td>
            <td>{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}</td>
            <td>{{ $item->mahasiswi->prodi ?? '-' }}</td>
            <td>{{ $item->mahasiswi->semester ?? '-' }}</td>
            <td>{{ $item->materi }}</td>
            <td>{{ $item->keterangan_ujian ?? '-' }}</td>
            <td>{{ $item->nilai ?? '-' }}</td>
            <td>{{ $item->created_at->format('d-m-Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

    <div class="ttd">
        Ponorogo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
        <strong>Admin Markaz Qur'an</strong>
    </div>
</body>



</html>
