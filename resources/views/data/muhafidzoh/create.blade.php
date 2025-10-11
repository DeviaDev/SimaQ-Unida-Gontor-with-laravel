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
        <form action="{{ route('muhafidzohStore') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row mb-2">
                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span>Nama Muhafidzoh:</label>
                    <input type="text" name="nama_muhafidzoh" class="form-control @error('nama_muhafidzoh') is-invalid @enderror" value="{{ old('nama_muhafidzoh') }}">
                    @error('nama_muhafidzoh')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span>Keterangan:</label>
                    <input type="text" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" value="{{ old('keterangan') }}">
                    @error('keterangan')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span>Kelompok:</label>
                    <select name="id_kelompok" class="form-control @error('id_kelompok') is-invalid @enderror">
                        <option value="">-- Pilih Kelompok --</option>
                        @foreach($kelompok as $item)
                            <option value="{{ $item->id_kelompok }}">{{ $item->kode_kelompok }}</option>
                        @endforeach
                    </select>
                    @error('id_kelompok')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span>Tempat:</label>

                    <select name="id_tempat" class="form-control @error('id_tempat') is-invalid @enderror">
                        <option value="">-- Pilih Tempat --</option>
                        @foreach($tempat as $item)
                            <option value="{{ $item->id_tempat }}">{{ $item->nama_tempat }}</option>
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
