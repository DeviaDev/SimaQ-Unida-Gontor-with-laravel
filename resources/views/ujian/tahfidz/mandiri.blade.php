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
                <i class="fas fa-plus mr-2"></i> Add Ujian
            </a>
        </div>
        <div class="mr-1">

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
                        @foreach ($mandiri as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}</td>
                            <td>{{ $item->mahasiswi->prodi ?? '-' }}</td>
                            <td>{{ $item->mahasiswi->semester ?? '-' }}</td>
                            <td>{{ $item->materi }}</td>
                            <td>{{ $item->keterangan_ujian }}</td>
                            <td>{{ $item->nilai }}</td>
                            <td class="text-center">
                                <div style="display: inline-flex; gap: 8px;">
                                    <a href="{{ route('mandiriEdit', $item->id_ujian_mandiri) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Tombol hapus -->
                                    <button type="button" 
                                        class="btn btn-sm btn-danger deleteButton"
                                        data-id_ujian_mandiri="{{ $item->id_ujian_mandiri }}"
                                        data-nama_mahasiswi="{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}"
                                        data-toggle="modal" 
                                        data-target="#deleteModal">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Hapus Data Ujian Mandiri?</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="userInfo">Yakin ingin menghapus data ini?</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">Hapus Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    // 1. Tombol hapus (Konfirmasi Modal)
    $(document).on('click', '.deleteButton', function() {
        let id = $(this).data('id_ujian_mandiri');
        let nim = $(this).data('nim');
        let nama_mahasiswi = $(this).data('nama_mahasiswi');
        let prodi = $(this).data('prodi');
        let semester = $(this).data('semester');
        let materi = $(this).data('materi');
        let nilai = $(this).data('nilai');

        // Menggunakan rute name 'mandiriDestroy' dari web.php
        // Kita buat URL manual karena ini di dalam file .js eksternal/script tag
        $('#deleteForm').attr('action', '/mandiri/destroy/' + id);
        
        $('#userInfo').html(`
            <strong>NIM:</strong> ${nim}<br>
            <strong>Nama Mahasiswi:</strong> ${nama_mahasiswi}<br>
            <strong>Program Studi:</strong> ${prodi}<br>
            <strong>semester:</strong> ${semester}<br>
            <strong>Materi Ujian:</strong> ${materi}<br>
            <strong>Nilai:</strong> ${nilai}
        `);
    });

    // 2. Tombol export PDF (Route: mandiriPdf)
    $('#exportPdf').on('click', function(e){
        e.preventDefault();
        let search = $('input[type="search"]').val(); 
        let url = "{{ route('mandiriPdf') }}"; 

        if(search) {
            url += '?search=' + encodeURIComponent(search);
        }

        window.open(url, '_blank');
    });

    // 3. Tombol export Excel (Route: mandiriExport)
    $('#exportExcel').on('click', function(e){
        e.preventDefault();
        let search = $('input[type="search"]').val();
        let url = "{{ route('mandiriExport') }}"; 

        if(search) {
            url += '?search=' + encodeURIComponent(search);
        }

        window.open(url, '_blank');
    });

});
</script>

@endsection