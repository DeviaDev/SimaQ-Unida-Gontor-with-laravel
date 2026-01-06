<table>
    <thead>
        <tr><th colspan="7" style="text-align: center;"><strong>DATA UJIAN TAHSIN</strong></th></tr>
        <tr>
            <th>No</th>
            <th>Nama Mahasiswi</th>
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
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}</td>
            <td>{{ $item->prodi }}</td>
            <td>{{ $item->semester }}</td>
            <td>{{ $item->kategori }}</td>
            <td>{{ $item->materi }}</td>
            <td>{{ $item->nilai }}</td>
        </tr>
        @endforeach
    </tbody>
</table>