@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">{{ $title }}</h1>

<div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap">
        <a href="{{ route('data.create', $type) }}" class="btn btn-sm btn-primary mb-2">
            <i class="fas fa-plus mr-2"></i> Tambah Data
        </a>

        <div>
            <form action="{{ route('data.import', $type) }}" method="POST" enctype="multipart/form-data" class="d-inline">
                @csrf
                <input type="file" name="file" class="d-none" id="fileInput" accept=".xls,.xlsx" onchange="this.form.submit()">
                <button type="button" class="btn btn-info btn-sm" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-file-import mr-2"></i> Import Excel
                </button>
            </form>

            <a href="{{ route('data.export', $type) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>

            <a href="{{ route('data.pdf', $type) }}" class="btn btn-danger btn-sm" target="_blank">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered text-center">
            <thead class="bg-primary text-white">
                <tr>
                    <th>No</th>
                    @foreach ($columns as $col)
                        <th>{{ ucfirst(str_replace('_', ' ', $col)) }}</th>
                    @endforeach
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        @foreach ($columns as $col)
                            <td>{{ $item->$col }}</td>
                        @endforeach
                        <td>
                            <a href="{{ route('data.edit', [$type, $item->id]) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('data.destroy', [$type, $item->id]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
