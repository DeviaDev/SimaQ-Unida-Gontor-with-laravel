@extends('layouts/app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-plus mr-2"></i>
    {{ $title }}
</h1>

<div class="card">
    <div class="card-header bg-primary d-flex flex-wrap justify-content-center justify-content-xl-between">
        <div class="mb-1 mr-2">
            <a href="{{ route('muhafidzoh') }}" class="btn btn-sm btn-success">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('muhafidzohUpdate',$muhafidzoh->id_muhafidzoh) }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row mb-2">
                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span>Nama Muhafidzoh:</label>

                    <input type="text" name="nama_muhafidzoh" class="form-control @error('nama_muhafidzoh') is-invalid @enderror" value="{{ $muhafidzoh->nama_muhafidzoh }}">

                    @error('nama_muhafidzoh')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span>Keterangan:</label>
                    <input type="text" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" value="{{$muhafidzoh->keterangan}}">
                    @error('keterangan')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span>Kelompok:</label>
                    <select name="id_kelompok" class="form-control @error('id_kelompok') is-invalid @enderror">
                        @if ($muhafidzoh->kelompok)
                        
                        <option value="{{ $muhafidzoh->kelompok->id_kelompok }}">{{ $muhafidzoh->kelompok->kode_kelompok }}</option>

                        @else
                            <option value="" selected>-- Pilih Kelompok</option>
                        @endif

                        @foreach($kelompok as $item)
                        @if(!$muhafidzoh->kelompok || $item->id_kelompok != $muhafidzoh->kelompok->id_kelompok)
                            <option value="{{ $item->id_kelompok }}">{{ $item->kode_kelompok }}</option>
                        @endif
                        @endforeach
                    </select>
                    @error('id_kelompok')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span>Tempat:</label>
                    <select name="id_tempat" class="form-control @error('id_tempat') is-invalid @enderror">
                        
                        @if ($muhafidzoh->tempat)
                        <option value="{{ $muhafidzoh->tempat->id_tempat}}">{{ $muhafidzoh->tempat->nama_tempat}}</option>

                        @else
                        <option value="" selected>-- Pilih Muhafidzoh --</option>
                        @endif

                        @foreach($tempat as $item)
                            @if (!$muhafidzoh->tempat || $item->id_tempat != $muhafidzoh->tempat->id_tempat)
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
