@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-user mr-2"></i>
        {{ $title ?? 'Data Ujian Tahsin' }}
    </h1>

    <div class="card shadow mb-4">
        {{-- Header Card --}}
        <div class="card-header py-3 d-flex flex-wrap justify-content-center justify-content-xl-between align-items-center">
            <div class="mb-1 mr-2">
                <a href="{{ route('tahsinCreate') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Tambah Ujian
                </a>
            </div>
            <div class="mr-1">
                <a href="#" id="exportExcel" class="btn btn-success btn-sm shadow-sm">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>
                <a href="#" id="exportPdf" class="btn btn-danger btn-sm shadow-sm">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </a>
            </div>
        </div>

        {{-- Body Card --}}
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="bg-primary text-white">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Prodi</th>
                            <th>Semester</th>
                            <th>Kategori</th>
                            <th>Materi</th>
                            <th>Nilai</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tahsinData as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->mahasiswi->nama_mahasiswi ?? '-' }}</td>
                            <td>{{ $item->prodi ?? '-' }}</td>
                            <td class="text-center">{{ $item->semester ?? '-' }}</td>
                            <td>{{ $item->kategori ?? '-' }}</td>
                            <td>{{ $item->materi }}</td>
                            <td class="text-center font-weight-bold">{{ $item->nilai ?? '-' }}</td>
                            
                            <td class="text-center">
                                <div style="display: inline-flex; gap: 5px;">
                                    
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('tahsinEdit', $item->id_tahsin) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- TOMBOL DELETE VERSI LINK (GET) --}}
                                        <a href="{{ route('tahsinDestroy', $item->id_tahsin) }}" 
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin hapus data {{ $item->mahasiswi->nama_mahasiswi ?? '-' }}?');">
                                            <i class="fas fa-trash"></i>
                                        </a>

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

{{-- MODAL DELETE --}}
{{-- PENTING: Jangan ubah ID ini --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE') 
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data milik:</p>
                    <h4 class="font-weight-bold text-danger" id="namaHapus"></h4>
                    <small class="text-muted">Data yang dihapus tidak bisa dikembalikan.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">Ya, Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT --}}
{{-- Kita load jQuery lagi untuk jaga-jaga fitur lain, tapi delete pakai JS Murni --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // 1. FUNGSI HAPUS (Ditaruh di luar document.ready agar global)
    function hapusData(url, nama) {
        // Debugging: Cek apakah fungsi ini terpanggil
        console.log("Tombol hapus diklik. URL:", url);

        // Ambil elemen form dan teks nama
        var form = document.getElementById('deleteForm');
        var textNama = document.getElementById('namaHapus');

        // Set action form secara manual (JS MURNI)
        form.action = url;
        textNama.innerText = nama;

        // Tampilkan modal (Pake jQuery bawaan template admin)
        $('#deleteModal').modal('show');
    }

    // 2. LOGIKA EXPORT (Pake jQuery)
    $(document).ready(function(){
        // Export PDF
        $('#exportPdf').on('click', function(e){
            e.preventDefault();
            let search = $('input[type="search"]').val(); 
            let url = "{{ route('tahsinExportPdf') }}"; 
            if(search) { url += '?search=' + encodeURIComponent(search); }
            window.open(url, '_blank');
        });

        // Export Excel
        $('#exportExcel').on('click', function(e){
            e.preventDefault();
            let search = $('input[type="search"]').val();
            let url = "{{ route('tahsinExportExcel') }}"; 
            if(search) { url += '?search=' + encodeURIComponent(search); }
            window.open(url, '_blank');
        });
    });
</script>
@endsection