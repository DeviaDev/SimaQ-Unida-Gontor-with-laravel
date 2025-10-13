@extends('layouts/app')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-plus mr-2"></i>
    {{ $title }}
</h1>

<div class="card">
    <div class="card-header bg-warning d-flex flex-wrap justify-content-center justify-content-xl-between">
        <div class="mb-1 mr-2">
            <a href="{{ route('dosen') }}" class="btn btn-sm btn-success">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('dosenUpdate',$dosen->id_dosen) }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row mb-2">
                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Nama Dosen:</label>

                    <input type="text" name="nama_dosen" class="form-control @error('nama_dosen') is-invalid @enderror" value="{{ $dosen->nama_dosen }}">

                </div>

                
               <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Muhafidzoh:</label>

                    <select name="id_muhafidzoh" class="form-control @error('id_muhafidzoh') is-invalid @enderror">
                        @if($dosen->muhafidzoh)
                    <option value="{{ $dosen->muhafidzoh->id_muhafidzoh }}" selected>
                    {{ $dosen->muhafidzoh->nama_muhafidzoh }}
                </option>

            @else
                <option value="" selected>-- Pilih Muhafidzoh --</option>
            @endif

        {{-- Tampilkan pilihan lain --}}
        @foreach($muhafidzoh as $item)
            {{-- Hindari duplikat nama yang sudah dipilih --}}
            @if(!$dosen->muhafidzoh || $item->id_muhafidzoh != $dosen->muhafidzoh->id_muhafidzoh)
                <option value="{{ $item->id_muhafidzoh }}">
                    {{ $item->nama_muhafidzoh }}
                </option>
            @endif
        @endforeach
    </select>

                    @error('id_muhafidzoh')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Kelompok:</label>

                    <select name="id_kelompok" class="form-control @error('id_kelompok') is-invalid @enderror">
                        @if($dosen->kelompok)
                        <option value="{{ $dosen->kelompok->id_kelompok }}" selected>{{ $dosen->kelompok->kode_kelompok }}</option>

                    
                    @else
                    <option value="" selected>-- Pilih Kelompok --</option>
                    @endif

                        @foreach($kelompok as $item)
                            @if(!$dosen->kelompok || $item->id_kelompok != $dosen->kelompok->id_kelompok)
                            <option value="{{ $item->id_kelompok }}">{{ $item->kode_kelompok }}</option>
                            @endif
                        @endforeach
                    </select>

                    @error('id_kelompok')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Tempat:</label>

                    <select name="id_tempat" class="form-control @error('id_tempat') is-invalid @enderror">
                        @if($dosen->muhafidzoh)
                        <option value="{{ $dosen->tempat->id_tempat }}">{{ $dosen->tempat->nama_tempat }}</option>

                        @else
                        <option value="" selected>-- Pilih Muhafidzoh --</option>
                        @endif


                        @foreach($tempat as $item)
                        @if(!$dosen->tempat || $item->id_tempat != $dosen->tempat->id_tempat)
                            <option value="{{ $item->id_tempat }}">{{ $item->nama_tempat }}</option>
                        @endif
                        @endforeach
                        
                    </select>
                    @error('id_tempat')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-5 justify-content-center rounded">
                <button type="submit" class="btn btn-sm btn-primary col-3">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>

        <script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input, select').forEach(el => {
        // Event untuk input teks
        el.addEventListener('input', handleValidationClear);
        // Event untuk select option
        el.addEventListener('change', handleValidationClear);
    });

    function handleValidationClear(event) {
        const el = event.target;
        // Hilangkan kelas is-invalid
        el.classList.remove('is-invalid');

        // Hapus pesan error (hanya untuk elemen ini)
        const parent = el.closest('.mb-2, .col-xl-6');
        if (parent) {
            parent.querySelectorAll('.text-danger').forEach(err => err.remove());
        }
    }
});
</script>

    </div>
</div>
@endsection
