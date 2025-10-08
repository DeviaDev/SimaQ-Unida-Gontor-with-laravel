<table>
    <thead>
        <tr>
            <th colspan="3" align="center"><strong>Data User</strong></th>
        </tr>
        <tr>
            <th width="25" align="center">No</th>
            <th width="25" align="center">Name</th>
            <th width="25" align="center">Email</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $item)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
