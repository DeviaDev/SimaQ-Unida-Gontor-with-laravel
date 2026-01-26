@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>
        {{ $title }}
    </h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-end align-items-center">
            <div class="d-flex" style="gap: 5px;">
                {{-- Tombol Export --}}
                <a href="#" id="exportExcel" class="btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </a>
                <a href="#" id="exportPdf" class="btn btn-sm btn-danger shadow-sm">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nama Mahasiswi</th>
                            <th>Prodi</th>
                            <th>Semester</th>
                            <th>Materi</th>
                            <th>Nilai Awal</th>
                            <th>Nilai Remedial</th> 
                            <th width="15%">Action</th> 
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
                                
                                {{-- Nilai Asli (Merah) --}}
                                <td class="text-center">
                                    <span class="badge badge-danger" style="font-size: 0.9em;">
                                        {{ $item->nilai }}
                                    </span>
                                </td>

                                {{-- Nilai Remedial (Hijau jika ada) --}}
                                <td class="text-center">
                                    @if($item->nilai_remedial)
                                        <span class="badge badge-success" style="font-size: 0.9em;">
                                            {{ $item->nilai_remedial }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">Belum Ada</span>
                                    @endif
                                </td>

                                <td class="text-center text-nowrap">
                                    <div style="display: inline-flex; gap: 8px;">
                                        {{-- Tombol Edit: Mengarah ke Halaman Edit yang baru kita fix --}}
                                        <a href="{{ route('remedialEdit', $item->id) }}" class="btn btn-sm btn-warning" title="Input Nilai Remedial">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        {{-- Tombol Hapus --}}
                                        <button type="button" class="btn btn-sm btn-danger deleteButton" 
                                            data-url="{{ route('remedialDestroy', $item->id) }}" 
                                            data-nama_mahasiswi="{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}"
                                            data-materi="{{ $item->materi }}"
                                            data-nilai="{{ $item->nilai }}"
                                            data-toggle="modal" data-target="#deleteModal"
                                            title="Hapus Data">
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

{{-- Modal Delete --}}
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
    // Inisialisasi DataTable
    var table = $('#dataTable').DataTable({
        "responsive": false,
        "scrollX": true
    });

    // 1. Logika Modal Hapus (Menggunakan URL dinamis dari atribut data-url)
    $(document).on('click', '.deleteButton', function() {
        let url = $(this).data('url'); // Ambil URL route destroy
        let nama = $(this).data('nama_mahasiswi');
        let materi = $(this).data('materi');
        let nilai = $(this).data('nilai');

        $('#deleteForm').attr('action', url); 
        
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
        let search = $('input[type="search"]').val(); // Ambil input search datatable
        let url = "{{ route('remedialExportPdf') }}"; 
        
        if(search) { 
            url += '?search=' + encodeURIComponent(search); 
        }
        window.open(url, '_blank');
    });

    // 3. Klik Export Excel
    $(document).on('click', '#exportExcel', function(e) {
        e.preventDefault();
        let search = $('input[type="search"]').val();
        let url = "{{ route('remedialExportExcel') }}"; 
        
        if(search) { 
            url += '?search=' + encodeURIComponent(search); 
        }
        window.open(url, '_blank');
    });
});
</script>
@endsection