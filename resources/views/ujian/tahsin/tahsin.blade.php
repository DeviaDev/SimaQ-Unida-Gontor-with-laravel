@extends('layouts/app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-user mr-2"></i>
        {{ $title }}</h1>

<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-center justify-content-xl-between">
        <div class="mb-1 mr-2">
            <a href="{{ route('tahsinCreate') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-2"></i> Add Ujian
            </a>
        </div>
        <div class="mr-1">

           <a href="{{ route('tahsinExportExcel') }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ route('tahsinExportPdf') }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>

        </div>
    </div>


    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Semester</th>
                        <th>Kategori</th>
                        <th>Materi</th>
                        <th>Nilai</th>
                        <th>Action</th>
                    </tr>
                </thead>
                    
                        <tbody>
                        @foreach ($tahsinData as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}</td>
                            <td>{{ $item->prodi ?? '-' }}</td>
                            <td>{{ $item->semester ?? '-' }}</td>
                            <td>{{ $item->kategori ?? '-' }}</td>
                            <td>{{ $item->materi }}</td>
                            <td>{{ $item->nilai ?? '-' }}</td>
                            
                            <td class="text-center">
                                <div style="display: inline-flex; gap: 5px;">
                                    <a href="{{ route('tahsinEdit', $item->id_tahsin) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger deleteButton" 
                                        data-id_tahsin="{{ $item->id_tahsin }}"
                                        data-nama_mahasiswi="{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}"
                                        data-prodi="{{ $item->prodi }}"
                                        data-semester="{{ $item->semester }}"
                                        data-kategori="{{ $item->kategori }}"
                                        data-materi="{{ $item->materi }}"
                                        data-nilai="{{ $item->nilai }}"
                                        data-toggle="modal" data-target="#deleteModal">
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
            @method('DELETE') <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Hapus Data Ujian Tahsin?</h5>
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

<script>
$(document).ready(function(){
    $(document).on('click', '.deleteButton', function() {
        // Ambil ID dari atribut data-id_tahsin pada tombol yang diklik
        let id = $(this).data('id_tahsin'); 
        
        // Sesuaikan action form agar mengarah ke route destroy yang benar
        // URL ini harus sama dengan yang ada di web.php
        $('#deleteForm').attr('action', '/tahsin/destroy/' + id);
        
        // Menampilkan info nama mahasiswi di dalam modal konfirmasi
        let nama = $(this).data('nama_mahasiswi');
        $('#userInfo').html(`Apakah Anda yakin ingin menghapus data ujian <strong>${nama}</strong>?`);
    });

    // 2. Tombol export PDF (Route: tahsinPdf)
    $('#exportPdf').on('click', function(e){
        e.preventDefault();
        let search = $('input[type="search"]').val(); 
        let url = "{{ route('tahsinExportPdf') }}"; 

        if(search) {
            url += '?search=' + encodeURIComponent(search);
        }

        window.open(url, '_blank');
    });

    // 3. Tombol export Excel (Route: tahsinExport)
    $('#exportExcel').on('click', function(e){
        e.preventDefault();
        let search = $('input[type="search"]').val();
        let url = "{{ route('tahsinExportExcel') }}"; 

        if(search) {
            url += '?search=' + encodeURIComponent(search);
        }

        window.open(url, '_blank');
    });
});
</script>
@endsection