@extends('layouts/app')

@section('content')
     <!-- Page Heading -->
     <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-plus mr-2"></i>
        {{ $title }}
    </h1>

    <div class="card">
        <div class="card-header bg-primary d-flex flex-wrap justify-content-center justify-content-xl-between">
           
                <div class="mb-1 mr-2">
                <a href="{{ route('pengurus') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
         
            
        </div>
        <div class="card-body">
            <form action="{{ route ('pengurusStore') }}" method="post" enctype="multipart/form-data">
                @csrf
           <div class="row mb-2">
            <div class="col-xl-6 mb-2">
                <label class="form-label">
                    <span class="text-danger">*</span> Foto:</label>
                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" value="{{ old('foto') }}">
                @error('foto')
                <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            <div class="col-xl-6">
                <label class="form-label">
                    <span class="text-danger">*</span> Nama Pengurus:</label>
                <input type="text" name="nama" class="form-control @error ('nama') is-invalid
                @enderror" value="{{ old('nama') }}">
                @error('nama')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror
            </div>
           </div>

           <div class="row mb-2">
                <div class="col-xl-6 mb-3" >
                <label class="form-label">
                    <span class="text-danger">*</span> Email:</label>
                <input type="email" name="email" class="form-control @error ('email') is-invalid
                @enderror" value="{{ old('email') }}">
                @error('email')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror
            </div>

           </div>

           <div class="row mb-3 justify-content-center rounded">
            <button type="submit" class="btn btn-sm btn-primary col-3" href="#">
                <i class="fas fa-save mr-2"></i>Simpan</button>
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