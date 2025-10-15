@extends('layouts/app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-user mr-2"></i>
    {{ $title }}
</h1>

<div class="card">
    <div class="card-header d-flex flex-wrap justify-content-center justify-content-xl-between">
        <div class="mb-1 mr-2">
            <a href="{{ route('mahasiswiCreate') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus mr-2"></i> Add Mahasiswi
            </a>
        </div>
        <div class="mr-1">
            <form action="{{ route('mahasiswiImport') }}" method="POST" enctype="multipart/form-data" class="d-inline">
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
                        <th>Nama Mahasiswi</th>
                        <th>Prodi</th>
                        <th>Semester</th>
                        <th>Nama Muhafidzoh</th>
                        <th>Kelompok</th>
                        <th>Tempat</th>
                        <th>Dosen Pembimbing</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_mahasiswi }}</td>
                        <td>{{ $item->prodi }}</td>
                        <td>{{ $item->semester }}</td>
                        <td>{{ $item->muhafidzoh->nama_muhafidzoh ?? '-' }}</td>
                        <td>{{ $item->kelompok->kode_kelompok ?? '-' }}</td>
                        <td>{{ $item->tempat->nama_tempat ?? '-' }}</td>
                        <td>{{ $item->dosen->nama_dosen ?? '-' }}</td>

                        <td class="text-center">
                            <div style="display: inline-flex; gap: 8px;">
                            <a href="
                            {{ route('mahasiswiEdit',$item->id_mahasiswi) }}" class="btn btn-sm btn-warning ">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Tombol hapus -->
                            <button
                                class="btn btn-sm btn-danger deleteButton"
                                data-id_mahasiswi="{{ $item->id_mahasiswi }}"
                                data-nama_mahasiswi="{{ $item->nama_mahasiswi }}"
                                data-prodi="{{ $item->prodi }}"
                                data-semester="{{ $item->semester }}"
                                data-nama_muhafidzoh="{{ $item->muhafidzoh->nama_muhafidzoh ?? '-' }}"
                                data-kode_kelompok="{{ $item->kelompok->kode_kelompok ?? '-'}}"
                                data-nama_tempat="{{ $item->tempat->nama_tempat ?? '-'}}"
                                data-nama_dosen="{{ $item->dosen->nama_dosen ?? '-'}}"
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
          <h5 class="modal-title">Hapus Data Mahasiswi?</h5>
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
        let id_mahasiswi = $(this).data('id_mahasiswi');
        let nama_mahasiswi = $(this).data('nama_mahasiswi');
        let prodi = $(this).data('prodi');
        let semester = $(this).data('semester');
        let nama_muhafidzoh = $(this).data('nama_muhafidzoh');
        let kode_kelompok = $(this).data('kode_kelompok');
        let nama_tempat = $(this).data('nama_tempat');
        let nama_dosen = $(this).data('nama_dosen');
        

        $('#deleteForm').attr('action', '/mahasiswi/destroy/' + id_mahasiswi);
        $('#userInfo').html(`
            <strong>Nama Mahasiswi:</strong> ${nama_mahasiswi}<br>
            <strong>Program Studi:</strong> ${prodi}<br>
            <strong>semester:</strong> ${semester}<br>
            <strong>Nama Muhafidzoh:</strong> ${nama_muhafidzoh}<br>
            <strong>Kode Kelompok:</strong> ${kode_kelompok}<br>
            <strong>Nama Tempat:</strong> ${nama_tempat}<br>
            <strong>Nama dosen:</strong> ${nama_dosen}
        `);
    });

    // Tombol export PDF sesuai search
    $('#exportPdf').on('click', function(e){
        e.preventDefault();
        let search = $('input[type="search"]').val(); // ambil dari DataTables search box
        let url = "{{ route('mahasiswiPdf') }}";

        if(search) {
            url += '?search=' + encodeURIComponent(search);
        }

        window.open(url, '_blank');
    });

    $('#exportExcel').on('click', function(e){
    e.preventDefault();
    let search = $('input[type="search"]').val(); // ambil keyword dari DataTables
    let url = "{{ route('mahasiswiExport') }}";

    if(search) {
        url += '?search=' + encodeURIComponent(search);
    }

    window.open(url, '_blank');
});


});
</script>

@endsection
