@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-user mr-2"></i>
        {{ $title ?? 'Data Ujian Tahsin' }}
    </h1>

    {{-- 1. TAB FILTER KATEGORI (Tendik Dihapus, Staff Jadi Muhafidzoh) --}}
    <div class="card mb-4 shadow-sm border-left-primary">
        <div class="card-body py-3">
            <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-filter mr-2"></i> Filter Data:</h6>
            <div class="d-flex flex-wrap" style="gap: 10px;">
                
                {{-- Tombol Mahasiswi --}}
                <a href="{{ route('tahsin', ['role' => 'Mahasiswi']) }}" 
                   class="btn {{ request('role', 'Mahasiswi') == 'Mahasiswi' ? 'btn-success' : 'btn-outline-success' }} px-4 shadow-sm">
                   <i class="fas fa-user-graduate mr-1"></i> Mahasiswi
                </a>

                {{-- Tombol Dosen --}}
                <a href="{{ route('tahsin', ['role' => 'Dosen']) }}" 
                   class="btn {{ request('role') == 'Dosen' ? 'btn-primary' : 'btn-outline-primary' }} px-4 shadow-sm">
                   <i class="fas fa-chalkboard-teacher mr-1"></i> Dosen
                </a>

                {{-- Tombol Muhafidzoh (Dulunya Staff) --}}
                <a href="{{ route('tahsin', ['role' => 'Muhafidzoh']) }}" 
                   class="btn {{ request('role') == 'Muhafidzoh' ? 'btn-info' : 'btn-outline-info' }} px-4 shadow-sm">
                   <i class="fas fa-user-tie mr-1"></i> Muhafidzoh
                </a>

                {{-- Tombol Tendik SUDAH DIHAPUS --}}

            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        {{-- 2. HEADER: TOMBOL TAMBAH & EXPORT --}}
        <div class="card-header py-3 d-flex flex-wrap justify-content-center justify-content-xl-between align-items-center">
            <div class="mb-1 mr-2">
                <a href="{{ route('tahsinCreate', ['role' => request('role', 'Mahasiswi')]) }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Tambah Ujian {{ request('role', 'Mahasiswi') }}
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

        {{-- 3. TABEL DATA --}}
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
                        @forelse ($tahsinData as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            
                            {{-- LOGIKA NAMA --}}
                            <td>
                            @if($item->kategori == 'Dosen')
                                {{ $item->dosen->nama_dosen ?? $item->dosen->nama ?? '-' }}
                                
                            @elseif($item->kategori == 'Muhafidzoh') 
                                {{-- PANGGIL RELASI MUHAFIDZOH --}}
                                {{-- Cek nama_muhafidzoh atau nama --}}
                                {{ $item->muhafidzoh->nama_muhafidzoh ?? $item->muhafidzoh->nama ?? '-' }}
                                
                            @elseif($item->kategori == 'Mahasiswi')
                                {{ $item->mahasiswi->nama_mahasiswi ?? '-' }}
                            @else
                                -
                            @endif
                        </td>

                            <td>{{ $item->prodi ?? '-' }}</td>
                            <td class="text-center">
                                {{-- Jika 0 atau null, tampilkan strip. Jika ada angka, tampilkan angkanya --}}
                                {{ $item->semester == 0 ? '-' : $item->semester }}
                            </td>
                            <td>{{ $item->kategori ?? '-' }}</td>
                            <td>{{ $item->materi }}</td>
                            <td class="text-center font-weight-bold">{{ $item->nilai ?? '-' }}</td>
                            
                            <td class="text-center">
                            <div style="display: inline-flex; gap: 5px;">
                                {{-- Tombol Edit (Tetap seperti biasa) --}}
                                <a href="{{ route('tahsinEdit', $item->id_tahsin) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- PERBAIKAN: Tombol Delete HARUS pakai Form --}}
                                <form action="{{ route('tahsinDestroy', $item->id_tahsin) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                
                            </div>
                        </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Belum ada data ujian untuk kategori <strong>{{ request('role', 'Mahasiswi') }}</strong>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT EXPORT --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function(){
        
        const urlParams = new URLSearchParams(window.location.search);
        const currentRole = urlParams.get('role') || 'Mahasiswi';

        console.log("Mode Export Aktif: " + currentRole);

        // EXPORT PDF
        $('#exportPdf').on('click', function(e){
            e.preventDefault();
            let baseUrl = "{{ route('tahsinExportPdf') }}"; 
            let finalUrl = baseUrl + '?role=' + currentRole;
            let search = $('input[type="search"]').val(); 
            if(search) { finalUrl += '&search=' + encodeURIComponent(search); }
            window.open(finalUrl, '_blank');
        });

        // EXPORT EXCEL
        $('#exportExcel').on('click', function(e){
            e.preventDefault();
            let baseUrl = "{{ route('tahsinExportExcel') }}"; 
            let finalUrl = baseUrl + '?role=' + currentRole;
            let search = $('input[type="search"]').val();
            if(search) { finalUrl += '&search=' + encodeURIComponent(search); }
            window.open(finalUrl, '_blank');
        });
    });
</script>
@endsection