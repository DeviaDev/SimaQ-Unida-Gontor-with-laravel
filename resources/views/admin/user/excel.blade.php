<table>
    <thead>
    <tr colspan="3" align="center">Data User</tr>
    <tr>
        <th width="25" align="center">No</th>
        <th width="25" align="center">Name</th>
        <th width="25" align="center">Email</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($user as $item)
            <tr>
                <td align="center">{{ $loop->iteration }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
            </tr>
        @endforeach
    </tbody>
</table>