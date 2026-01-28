@extends('layouts/app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-user mr-2"></i>
    {{ $title }}
</h1>

<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-center justify-content-xl-between">
        <div class="mb-1 mr-2">
            <a href="{{ route('muhafidzohCreate') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-2"></i> Add Muhafidzoh
            </a>
        </div>
        <div class="mr-1">
            <form action="{{ route('muhafidzohImport') }}" method="POST" enctype="multipart/form-data" class="d-inline">
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
                        <th>Nama Muhafidzoh</th>
                        <th>Keterangan</th>
                        <th>Kelompok</th>
                        <th>Tempat</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_muhafidzoh }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->kelompok->kode_kelompok ?? '-' }}</td>
                        <td>{{ $item->tempat->nama_tempat ?? '-' }}</td>
                        
                        
                        <td class="text-center">
                            <div style="display: inline-flex; gap: 8px;">
                            <a href="{{ route('muhafidzohEdit',$item->id_muhafidzoh) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Tombol hapus -->
                            <button
                                class="btn btn-sm btn-danger deleteButton"
                                data-id_muhafidzoh="{{ $item->id_muhafidzoh }}"
                                data-nama_muhafidzoh="{{ $item->nama_muhafidzoh }}"
                                data-keterangan="{{ $item->keterangan }}"
                                data-kode_kelompok="{{ $item->kelompok->kode_kelompok ?? '-' }}"
                                data-nama_tempat="{{ $item->tempat->nama_tempat ?? '-' }}"
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

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="deleteForm" method="POST">
      @csrf
      @method('DELETE')
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Hapus Data Muhafidzoh?</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p id="userInfo">Yakin ingin menghapus data ini?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    // Tombol hapus
    $(document).on('click', '.deleteButton', function() {
        let id_muhafidzoh = $(this).data('id_muhafidzoh');
        let nama_muhafidzoh = $(this).data('nama_muhafidzoh');
        let keterangan = $(this).data('keterangan');
        let kode_kelompok = $(this).data('kode_kelompok');
        let nama_tempat = $(this).data('nama_tempat');


        $('#deleteForm').attr('action', '/muhafidzoh/destroy/' + id_muhafidzoh);
        $('#userInfo').html(`
            <strong>Nama Muhafidzoh:</strong> ${nama_muhafidzoh}<br>
            <strong>Keterangan:</strong> ${keterangan}<br>
            <strong>Kode Kelompok:</strong> ${kode_kelompok}<br>
            <strong>Nama Tempat:</strong> ${nama_tempat}
        `);
    });

    // Tombol export PDF sesuai search
    $('#exportPdf').on('click', function(e){
        e.preventDefault();
        let search = $('input[type="search"]').val(); // ambil dari DataTables search box
        let url = "{{ route('muhafidzohPdf') }}";

        if(search) {
            url += '?search=' + encodeURIComponent(search);
        }

        window.open(url, '_blank');
    });

    $('#exportExcel').on('click', function(e){
    e.preventDefault();
    let search = $('input[type="search"]').val(); // ambil keyword dari DataTables
    let url = "{{ route('muhafidzohExport') }}";

    if(search) {
        url += '?search=' + encodeURIComponent(search);
    }

    window.open(url, '_blank');
});


});
</script>

@endsection
