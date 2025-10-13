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
        <form action="{{ route('mahasiswiUpdate',$mahasiswi->id_mahasiswi) }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row mb-2">
                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Nama Mahasiswi:</label>
                    <input type="text" name="nama_mahasiswi" class="form-control @error('nama_mahasiswi') is-invalid @enderror" value="{{ $mahasiswi->nama_mahasiswi }}">
                    @error('nama_mahasiswi')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Program Studi:</label>
                    <input type="text" name="prodi" class="form-control @error('prodi') is-invalid @enderror" value="{{ $mahasiswi->prodi }}">
                    @error('prodi')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Semester:</label>
                    <input type="number" name="semester" class="form-control @error('semester') is-invalid @enderror" value="{{ $mahasiswi->semester }}">
                    @error('semester')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-xl-6 mb-2">
                    <label class="form-label"><span class="text-danger">*</span> Dosen Pembimbing:</label>
                    <select name="id_dosen" class="form-control @error('id_dosen') is-invalid @enderror">
                        @if($mahasiswi->dosen)
                        <option value="{{ $mahasiswi->dosen->id_dosen }}">{{ $mahasiswi->dosen->nama_dosen }}</option>

                        @else
                            <option value="" selected>-- Pilih Dosen --</option>
                        @endif

                        @foreach($dosen as $item)
                        @if(!$mahasiswi->dosen || $item->id_dosen != $mahasiswi->dosen->id_dosen)
                            <option value="{{ $item->id_dosen }}">{{ $item->nama_dosen }}</option>
                        @endif
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
                        @if($mahasiswi->muhafidzoh)
                        <option value="{{ $mahasiswi->muhafidzoh->id_muhafidzoh }}">{{ $mahasiswi->muhafidzoh->nama_muhafidzoh }}</option>

                        @else
                        <option value="" selected>-- Pilih Muhafidzoh --</option>
                        @endif

                        @foreach($muhafidzoh as $item)
                            @if (!$mahasiswi->muhafidzoh || $item->id_muhafidzoh != $mahasiswi->muhafidzoh->id_muhafidzoh)
                            <option value="{{ $item->id_muhafidzoh }}">{{ $item->nama_muhafidzoh }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('id_muhafidzoh')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-xl-6 mb-2">
                    <label class="form-label">Kelompok:</label>
                    <select name="id_kelompok" class="form-control @error('id_kelompok') is-invalid @enderror">
                        @if ($mahasiswi->kelompok)          
                       
                        <option value="{{ $mahasiswi->kelompok->id_kelompok }}">{{ $mahasiswi->kelompok->kode_kelompok }}</option>

                        @else
                    <option value="" selected>-- Pilih Kelompok --</option>
                    @endif


                        @foreach($kelompok as $item)
                        @if(!$mahasiswi->kelompok || $item->id_kelompok != $mahasiswi->kelompok->id_kelompok)
                            <option value="{{ $item->id_kelompok }}">{{ $item->kode_kelompok }}</option>
                        @endif
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
                        @if ($mahasiswi->tempat)
                            
                      
                        <option value="{{$mahasiswi->tempat->id_tempat}}">{{$mahasiswi->tempat->nama_tempat}}</option>

                        @else
                        <option value="" selected>-- Pilih Tempat --</option>
                        @endif


                        @foreach($tempat as $item)
                            @if (!$mahasiswi->tempat || $item->id_tempat != $mahasiswi->tempat->id_tempat)
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
