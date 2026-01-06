<table>
    <thead>
        <tr>
            <th colspan="8" align="center"><strong>Data Ujian Mandiri</strong></th>
        </tr>
        <tr>
            <th>No</th>
            <th>Nama Mahasiswi</th>
            <th>Program Studi</th>
            <th>Semester</th>
            <th>Materi</th>
            <th>Keterangan Ujian</th>
            <th>Nilai</th>
            <th>Tanggal</th>
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
