@extends('layouts/app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-user mr-2"></i>
    {{ $title }}
</h1>

<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-center justify-content-xl-between">
        <div class="mb-1 mr-2">
            <a href="{{ route('mandiriCreate') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-2"></i> Add Mandiri
            </a>
        </div>
        <div class="mr-1">
            <form action="{{ route('mandiriImport') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                @csrf
                <input type="file" name="file" class="d-none" id="fileInput" accept=".xls,.xlsx" onchange="this.form.submit()">
                <button type="button" class="btn btn-info btn-sm" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-file-import mr-2"></i> Import Excel
                </button>
            </form>

            <a href="#" id="exportExcel" class="btn btn-sm btn-success">
            <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>


            <a href="#" id="exportPdf" class="btn btn-sm btn-danger">
            <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>

        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr class="text-center">
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Mahasiswi</th>
                        <th>Prodi</th>
                        <th>Semester</th>
                        <th>Materi</th>
                        <th>Keterangan Ujian</th>
                        <th>Nilai</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection