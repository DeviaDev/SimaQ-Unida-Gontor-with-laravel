@extends('layouts/app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>
        {{ $title }}
    </h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-end align-items-center">
            <div class="d-flex" style="gap: 5px;">
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
                            <th>Nilai Asli</th>
                            <th>Nilai Remedial</th> <th class="text-nowrap">Action</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($remedialData as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td class="text-center">{{ $item->semester }}</td>
                                <td>{{ $item->materi }}</td>
                                <td class="text-center">
                                    <span class="badge badge-danger">{{ $item->nilai }}</span>
                                </td>

                                <td class="text-center">
                                    <select class="form-control form-control-sm status-remedial" data-id="{{ $item->id }}">
                                        <option value="" {{ $item->nilai_remedial == null ? 'selected' : '' }}>🔴 Belum Ujian</option>
                                        <option value="Sudah Ujian" {{ $item->nilai_remedial != null ? 'selected' : '' }}>🟢 Sudah Ujian</option>
                                    </select>

                                    
                                </td>

                                <td class="text-center text-nowrap">
                                    <div style="display: inline-flex; gap: 8px;">
                                        <a href="{{ route('remedialEdit', $item->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger deleteButton" 
                                            data-id_remedial="{{ $item->id }}"
                                            data-nama_mahasiswi="{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}"
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
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Hapus Data Remedial?</h5>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    var table = $('#dataTable').DataTable({
        "responsive": false,
        "scrollX": true
    });

    // --- LOGIKA BARU UNTUK DROPDOWN NILAI ---
    $(document).on('change', '.status-remedial', function() {
        let id = $(this).data('id');
        let status = $(this).val();
        let container = $('#input-container-' + id);

        if (status === 'Sudah Ujian') {
            container.fadeIn();
        } else {
            container.fadeOut();
            container.find('select').val('');
        }
    });

    // 1. Logika Modal Hapus
    $(document).on('click', '.deleteButton', function() {
        let id = $(this).data('id_remedial');
        let nama = $(this).data('nama_mahasiswi');
        let materi = $(this).data('materi');
        let nilai = $(this).data('nilai');

        $('#deleteForm').attr('action', '/tahfidz/remedial/destroy/' + id); 
        
        $('#userInfo').html(`
            Yakin ingin menghapus data remedial mahasiswi berikut?<br><br>
            <strong>Nama:</strong> ${nama}<br>
            <strong>Materi:</strong> ${materi}<br>
            <strong>Nilai Asli:</strong> ${nilai}
        `);
    });

    // 2. Klik Export PDF
    $(document).on('click', '#exportPdf', function(e) {
        e.preventDefault();
        let search = table.search(); 
        let url = "{{ route('remedialExportPdf') }}"; 
        if(search && search !== "") { url += '?search=' + encodeURIComponent(search); }
        window.open(url, '_blank');
    });

    // 3. Klik Export Excel
    $(document).on('click', '#exportExcel', function(e) {
        e.preventDefault();
        let search = table.search();
        let url = "{{ route('remedialExportExcel') }}"; 
        if(search && search !== "") { url += '?search=' + encodeURIComponent(search); }
        window.open(url, '_blank');
    });

    // Simpan otomatis saat nilai dipilih
$(document).ready(function(){
    // Jalankan simpan otomatis saat dropdown pilihan nilai berubah
    $(document).on('change', '.select-nilai-remedial', function() {
        let id = $(this).data('id');
        let nilaiBaru = $(this).val();

        $.ajax({
            url: "{{ route('remedialUpdateInline') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}", // Token keamanan Laravel
                id: id,
                nilai: nilaiBaru
            },
            success: function(response) {
                if(response.success) {
                    // Notifikasi jika data berhasil menetap di database
                    alert('Nilai Remedial Berhasil Disimpan!');
                }
            },
            error: function() {
                alert('Gagal menyambung ke server. Periksa koneksi atau route Anda.');
            }
        });
    });
});
});
</script>
@endsection