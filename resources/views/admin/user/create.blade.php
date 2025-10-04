@extends('layouts/app')

@section('content')
     <!-- Page Heading -->
     <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-plus mr-2"></i>
        {{ $title }}
    </h1>

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-center justify-content-xl-between">
           
                <div class="mb-1 mr-2">
                <a href="{{ route('user') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
         
            
        </div>
        <div class="card-body">
            <form action="userStore" method="post">
                @csrf
           <div class="row mb-2">
            <div class="col-xl-6 mb-2">
                <label class="form-label">
                    <span class="text-danger">*</span> Nama:</label>
                <input type="text" name="name" class="form-control">
                @error('nama')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror
            </div>

            <div class="col-xl-6">
                <label class="form-label">
                    <span class="text-danger">*</span> Email:</label>
                <input type="email" name="email" class="form-control">
                @error('email')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror
            </div>
           </div>

           <div class="row mb-2">
                <div class="col-xl-6" >
                <label class="form-label">
                    <span class="text-danger">*</span> Password:</label>
                <input type="password" name="password" class="form-control">
                @error('password')
                <small class="text-danger">
                    {{ $message }}
                </small>
                @enderror
            </div>
           </div>

           <div class="row mb-2">
                <div class="col-xl-6" >
                <label class="form-label">
                    <span class="text-danger">*</span> Konfimasi Password:</label>
                <input type="password" name="password" class="form-control">
            </div>
           </div>
           
        </div>

        <div class="row mb-5 justify-content-center rounded">
            <button class="btn btn-sm btn-primary col-3" href="#">
                <i class="fas fa-save mr-2"></i>Simpan</button>
        </div>
        </form>
    </div>
@endsection