<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-weight: bold; font-size: 14px;">
                DATA UJIAN TAHSIN {{ isset($role) ? strtoupper($role) : '' }}
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #d9d9d9;">No</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #d9d9d9;">Nama Peserta</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #d9d9d9;">Prodi</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #d9d9d9;">Semester</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #d9d9d9;">Kategori</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #d9d9d9;">Materi</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center; background-color: #d9d9d9;">Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tahsinData as $item)
        <tr>
            <td style="border: 1px solid #000000; text-align: center;">{{ $loop->iteration }}</td>
            
            {{-- LOGIKA PINTAR: Update Bagian Ini --}}
            <td style="border: 1px solid #000000;">
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

            <td style="border: 1px solid #000000;">{{ $item->prodi ?? '-' }}</td>
            
            {{-- Tampilkan Strip jika semester 0 --}}
            <td style="border: 1px solid #000000; text-align: center;">
                {{ ($item->semester == 0 || $item->semester == '-') ? '-' : $item->semester }}
            </td>

            <td style="border: 1px solid #000000; text-align: center;">{{ $item->kategori ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $item->materi }}</td>
            <td style="border: 1px solid #000000; text-align: center; font-weight: bold;">{{ $item->nilai }}</td>
        </tr>
        @endforeach
    </tbody>
</table>