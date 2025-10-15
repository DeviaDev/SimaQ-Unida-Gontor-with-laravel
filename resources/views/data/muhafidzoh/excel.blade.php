<table>
    <thead>
        <tr>
            <th colspan="5" align="center"><strong>Data Muhafidzoh</strong></th>
        </tr>
        <tr>
            <th width="25" align="center">No</th>
            <th width="25" align="center">Nama Muhafidzoh</th>
            <th width="25" align="center">Keterangan</th>
            <th width="25" align="center">Kelompok</th>
            <th width="25" align="center">Tempat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($muhafidzoh as $item)
            <tr>
                <td width="25" align="center">{{ $loop->iteration }}</td>
                <td width="25" align="center">{{ $item->nama_muhafidzoh }}</td>
                <td width="25" align="center">{{ $item->keterangan }}</td>
                <td width="25" align="center">{{ $item->kelompok->kode_kelompok }}</td>
                <td width="25" align="center">{{ $item->tempat->nama_tempat }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
