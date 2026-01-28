@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    {{-- JUDUL & TOMBOL KEMBALI --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <a href="{{ route('tahsin', ['role' => request('role', 'Mahasiswi')]) }}" class="btn btn-secondary btn-sm mr-2 shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
            {{ $title ?? 'Tambah Ujian Tahsin' }}
        </h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow">
            <h6 class="font-weight-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Ada kesalahan input:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FILTER KATEGORI --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-filter mr-2"></i> Pilih Kategori Peserta</h6>
            <div class="d-flex flex-wrap" style="gap: 10px;">
                
                <a href="{{ route('tahsinCreate', ['role' => 'Mahasiswi']) }}" 
                   class="btn {{ request('role', 'Mahasiswi') == 'Mahasiswi' ? 'btn-success' : 'btn-outline-success' }} px-4 shadow-sm">
                   Mahasiswi
                </a>

                <a href="{{ route('tahsinCreate', ['role' => 'Dosen']) }}" 
                   class="btn {{ request('role') == 'Dosen' ? 'btn-primary' : 'btn-outline-primary' }} px-4 shadow-sm">
                   Dosen
                </a>

                <a href="{{ route('tahsinCreate', ['role' => 'Muhafidzoh']) }}" 
                   class="btn {{ request('role') == 'Muhafidzoh' ? 'btn-info' : 'btn-outline-info' }} px-4 shadow-sm">
                   Muhafidzoh
                </a>

            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('tahsinStore') }}" method="POST">
                @csrf

                {{-- Input Hidden Kategori --}}
                <input type="hidden" name="kategori" value="{{ request('role') ?? 'Mahasiswi' }}">
                
                <div class="form-group">
                    <label class="font-weight-bold">Nama Peserta ({{ request('role') ?? 'Mahasiswi' }})</label>
                    <select name="id_mahasiswi" id="selectMahasiswi" class="form-control select2" required>
                        <option value="">-- Pilih Nama --</option>
                        
                        @foreach($mahasiswi as $m)
                            {{-- LOGIKA VALUE ID: --}}
                            {{-- Cek id_muhafidzoh, kalau gak ada cek id_mahasiswi, dst --}}
                            <option value="{{ $m->id_muhafidzoh ?? $m->id_mahasiswi ?? $m->id_dosen ?? $m->id }}" 
                                
                                {{-- LOGIKA DATA PRODI & SEMESTER: --}}
                                {{-- Jika role Muhafidzoh atau Dosen, paksa jadi strip (-) --}}
                                @if(request('role') == 'Muhafidzoh' || request('role') == 'Dosen')
                                    data-prodi="-" 
                                    data-semester="-"
                                @else
                                    {{-- Jika Mahasiswi, ambil data aslinya --}}
                                    data-prodi="{{ $m->prodi ?? '-' }}" 
                                    data-semester="{{ $m->semester ?? '-' }}"
                                @endif
                                >
                                
                                {{-- LOGIKA TAMPILAN NAMA: --}}
                                {{-- Cek nama_muhafidzoh (sesuai screenshot), lalu nama_mahasiswi, dst --}}
                                {{ $m->nama_muhafidzoh ?? $m->nama_mahasiswi ?? $m->nama_dosen ?? $m->nama }}
                            
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Prodi</label>
                            <input type="text" name="prodi" id="prodi" class="form-control bg-light" readonly placeholder="Otomatis...">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Semester</label>
                            <input type="text" name="semester" id="semester" class="form-control bg-light" readonly placeholder="Otomatis...">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Materi Ujian</label>
                    <input type="text" name="materi" class="form-control" placeholder="Contoh: Al-Baqarah" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Nilai (Huruf)</label>
                    <select name="nilai" class="form-control" required>
                        <option value="">-- Pilih Nilai --</option>
                        <option value="A">A</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B">B</option>
                        <option value="B-">B-</option>
                        <option value="C+">C+</option>
                        <option value="C">C</option>
                        <option value="C-">C-</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                    </select>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary btn-block shadow">
                    <i class="fas fa-save mr-2"></i> Simpan Data Ujian
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Ketika dropdown dipilih
    $('#selectMahasiswi').on('change', function() {
        let selectedOption = $(this).find(':selected');

        let prodiData = selectedOption.data('prodi');
        let semesterData = selectedOption.data('semester');

        $('#prodi').val(prodiData);
        $('#semester').val(semesterData);
    });
});
</script>
@endsection