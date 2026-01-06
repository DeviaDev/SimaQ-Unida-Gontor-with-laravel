<table>
    <thead>
        <tr>
            <th style="background-color: #4e73df; color: #ffffff; font-weight: bold;">No</th>
            <th style="background-color: #4e73df; color: #ffffff; font-weight: bold;">Nama Mahasiswi</th>
            <th style="background-color: #4e73df; color: #ffffff; font-weight: bold;">Prodi</th>
            <th style="background-color: #4e73df; color: #ffffff; font-weight: bold;">Semester</th>
            <th style="background-color: #4e73df; color: #ffffff; font-weight: bold;">Materi</th>
            <th style="background-color: #4e73df; color: #ffffff; font-weight: bold;">Nilai Asli</th>
            <th style="background-color: #4e73df; color: #ffffff; font-weight: bold;">Nilai Remedial</th> </tr>
    </thead>
    <tbody>
        @foreach($remedialData as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}</td>
            <td>{{ $item->prodi }}</td>
            <td>{{ $item->semester }}</td>
            <td>{{ $item->materi }}</td>
            <td>{{ $item->nilai }}</td>
            <td>{{ $item->nilai_remedial ?? 'Belum Ujian' }}</td> </tr>
        @endforeach
    </tbody>
</table>