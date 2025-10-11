@extends('layouts/app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-plus mr-2"></i>
    {{ $title }}
</h1>

<div class="card">
    <div class="card-header bg-primary d-flex flex-wrap justify-content-center justify-content-xl-between">
        <div class="mb-1 mr-2">
            <a href="{{ route('mahasiswi') }}" class="btn btn-sm btn-success">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="card-body">
        <form action="{{ route('mahasiswiStore') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row mb-2">
                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Nama Mahasiswi:</label>
                    <input type="text" name="nama_mahasiswi" class="form-control @error('nama_mahasiswi') is-invalid @enderror" value="{{ old('nama_mahasiswi') }}">
                    @error('nama_mahasiswi')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Program Studi:</label>
                    <input type="text" name="prodi" class="form-control @error('prodi') is-invalid @enderror" value="{{ old('prodi') }}">
                    @error('prodi')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Semester:</label>
                    <input type="number" name="semester" class="form-control @error('semester') is-invalid @enderror" value="{{ old('semester') }}">
                    @error('semester')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Dosen Pembimbing:</label>
                    <select name="id_dosen" class="form-control @error('id_dosen') is-invalid @enderror">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosen as $item)
                            <option value="{{ $item->id_dosen }}">{{ $item->nama_dosen }}</option>
                        @endforeach
                    </select>
                    @error('id_dosen')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-xl-6 mb-2">
                    <label class="form-label">Muhafidzoh:</label>
                    <select name="id_muhafidzoh" class="form-control @error('id_muhafidzoh') is-invalid @enderror">
                        <option value="">-- Pilih Muhafidzoh --</option>
                        @foreach($muhafidzoh as $item)
                            <option value="{{ $item->id_muhafidzoh }}">{{ $item->nama_muhafidzoh }}</option>
                        @endforeach
                    </select>
                    @error('id_muhafidzoh')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-xl-6 mb-2">
                    <label class="form-label">Kelompok:</label>
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
            </div>

            <div class="row mb-2">
                <div class="col-xl-6">
                    <label class="form-label">Tempat:</label>
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
    document.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function () {
            // Hilangkan border merah
            this.classList.remove('is-invalid');

            // Hapus pesan error di bawah input (mencakup semua struktur)
            const allErrors = document.querySelectorAll('.text-danger');
            allErrors.forEach(err => {
                // Pastikan error terkait dengan input yang sama (berada di parent yang sama)
                if (err.closest('.col-xl-6, .mb-2, .form-group')?.contains(this)) {
                    err.remove();
                }
            });
        });
    });
});
</script>

    </div>
</div>
@endsection
