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
            margin: 20px 0;
            text-transform: uppercase;
        }

        table {
            width: 90%;
            border-collapse: collapse;
            margin: 10px auto;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #eee;
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .ttd {
            width: 90%;
            margin: 40px auto 0 auto;
            text-align: right;
        }
    </style>
</head>
<body>

    {{-- 1. BAGIAN KOP SURAT --}}
    <div class="kop">
        <img src="{{ public_path('assets/img/kopsurat.png') }}" alt="Kop Surat">
    </div>

    {{-- 2. JUDUL HALAMAN --}}
    <h2>Data Ujian Tahsin {{ isset($role) ? $role : '' }}</h2>

    {{-- 3. TABEL DATA --}}
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Peserta</th>
                <th>Prodi</th>
                <th>Semester</th>
                <th>Kategori</th>
                <th>Materi</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tahsinData as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                
                {{-- LOGIKA PINTAR (UPDATE DISINI) --}}
                <td>
                    @if($item->kategori == 'Dosen')
                        {{ $item->dosen->nama_dosen ?? $item->dosen->nama ?? '-' }}
                    
                    @elseif($item->kategori == 'Muhafidzoh')
                        {{-- PANGGIL RELASI MUHAFIDZOH --}}
                        {{ $item->muhafidzoh->nama_muhafidzoh ?? $item->muhafidzoh->nama ?? '-' }}
                        
                    @else
                        {{-- Default Mahasiswi --}}
                        {{ $item->mahasiswi->nama_mahasiswi ?? '-' }}
                    @endif
                </td>

                <td>{{ $item->prodi ?? '-' }}</td>
                
                {{-- Semester: Ubah angka 0 jadi strip (-) --}}
                <td class="text-center">
                    {{ ($item->semester == 0 || $item->semester == '-') ? '-' : $item->semester }}
                </td>

                <td class="text-center">{{ $item->kategori ?? '-' }}</td>
                <td>{{ $item->materi }}</td>
                <td class="text-center"><b>{{ $item->nilai }}</b></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- 4. TANDA TANGAN --}}
    <div class="ttd">
        Ponorogo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} <br>
        <br><br><br>
        <strong>Admin Markaz Qur'an</strong>
    </div>

</body>
</html>