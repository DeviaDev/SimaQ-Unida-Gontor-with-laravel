<table>
    <thead>
        <tr>
            <th colspan="4" align="center"><strong>Data Pengurus</strong></th>
        </tr>
        <tr>
            <th align="center">No</th>
            <th align="center">Foto</th>
            <th align="center">Nama</th>
            <th align="center">Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach($Pengurus as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @php
                        $fotoPath = public_path('storage/foto_pengurus/' . $item->foto);
                    @endphp
                    @if($item->foto && file_exists($fotoPath))
                        <img src="{{ $fotoPath }}" width="60" height="60" alt="Foto">
                    @else
                        (Tidak ada foto)
                    @endif
                </td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

