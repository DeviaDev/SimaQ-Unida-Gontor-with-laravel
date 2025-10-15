<table>
    <thead>
        <tr>
            <th colspan="8" align="center"><strong>Data Mahasiswi</strong></th>
        </tr>
        <tr>
            <th width="25" align="center">No</th>
            <th width="25" align="center">Nama Mahasiswi</th>
            <th width="25" align="center">Program Studi</th>
            <th width="25" align="center">Semester</th>
            <th width="25" align="center">Nama Muhafidzoh</th>
            <th width="25" align="center">Kelompok</th>
            <th width="25" align="center">Tempat</th>
            <th width="25" align="center">Dosen Pembimbing</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mahasiswi as $item)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td align="center">{{ $item->nama_mahasiswi }}</td>
                <td align="center">{{ $item->prodi }}</td>
                <td align="center">{{ $item->semester }}</td>
                <td align="center">{{ $item->muhafidzoh->nama_muhafidzoh }}</td>
                <td align="center">{{ $item->kelompok->kode_kelompok }}</td>
                <td align="center">{{ $item->tempat->nama_tempat }}</td>
                <td align="center">{{ $item->dosen->nama_dosen }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
