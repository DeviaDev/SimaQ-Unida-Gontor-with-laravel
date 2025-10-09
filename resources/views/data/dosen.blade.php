@extends('layouts/app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-user mr-2"></i>
    {{ $title }}
</h1>

<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-center justify-content-xl-between">
        <div class="mb-1 mr-2">
            <a href="{{ route('userCreate') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-2"></i> Add User
            </a>
        </div>
        <div class="mr-1">
            <form action="{{ route('userImport') }}" method="POST" enctype="multipart/form-data" class="d-inline">
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
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-primary text-white">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Dosen</th>
                        <th>Kelompok</th>
                        <th>Nama Muhafidzoh</th>
                        <th>Tempat</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data as $dsn)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $dsn->nama_dosen }}</td>
                        <td>{{ $dsn->kelompok->kode_kelompok ?? '-' }}</td>
                        <td>{{ $dsn->muhafidzoh->nama_muhafidzoh ?? '-' }}</td>
                        <td>{{ $dsn->tempat->nama_tempat ?? '-' }}</td>
                        <td class="text-center">
                            {{-- route'userEdit',$mhs->id --}}
                            <a href="#" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Tombol hapus -->
                            {{-- <button
                                class="btn btn-sm btn-danger deleteButton"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name }}"
                                data-email="{{ $item->email }}"
                                data-toggle="modal"
                                data-target="#deleteModal">
                                <i class="fas fa-trash"></i>
                            </button> --}}
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
          <h5 class="modal-title">Hapus User?</h5>
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
        let id = $(this).data('id');
        let name = $(this).data('name');
        let email = $(this).data('email');

        $('#deleteForm').attr('action', '/user/destroy/' + id);
        $('#userInfo').html(`
            <strong>Nama:</strong> ${name}<br>
            <strong>Email:</strong> ${email}
        `);
    });

    // Tombol export PDF sesuai search
    $('#exportPdf').on('click', function(e){
        e.preventDefault();
        let search = $('input[type="search"]').val(); // ambil dari DataTables search box
        let url = "{{ route('userPdf') }}";

        if(search) {
            url += '?search=' + encodeURIComponent(search);
        }

        window.open(url, '_blank');
    });

    $('#exportExcel').on('click', function(e){
    e.preventDefault();
    let search = $('input[type="search"]').val(); // ambil keyword dari DataTables
    let url = "{{ route('userExport') }}";

    if(search) {
        url += '?search=' + encodeURIComponent(search);
    }

    window.open(url, '_blank');
});


});
</script>

@endsection
