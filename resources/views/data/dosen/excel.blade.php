<table>
    <thead>
        <tr>
            <th colspan="5" align="center"><strong>Data Dosen</strong></th>
        </tr>
        <tr>
            <th width="25" align="center">No</th>
            <th width="25" align="center">Nama Dosen</th>
            <th width="25" align="center">Kelompok</th>
            <th width="25" align="center">Nama Muhafidzoh</th>
            <th width="25" align="center">Tempat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dosen as $item)
            <tr>
                <td width="25" align="center">{{ $loop->iteration }}</td>
                <td width="25" align="center">{{ $item->nama_dosen }}</td>
                <td width="25" align="center">{{ $item->kelompok->kode_kelompok }}</td>
                <td width="25" align="center">{{ $item->muhafidzoh->nama_muhafidzoh }}</td>
                <td width="25" align="center">{{ $item->tempat->nama_tempat }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
