@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
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

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-filter mr-2"></i> Pilih Kategori Peserta</h6>
            <div class="d-flex flex-wrap" style="gap: 10px;">
                <a href="{{ route('tahsinCreate', ['role' => 'Dosen']) }}" class="btn {{ request('role') == 'Dosen' ? 'btn-primary' : 'btn-outline-primary' }} px-4 shadow-sm">Dosen</a>
                <a href="{{ route('tahsinCreate', ['role' => 'Mahasiswi']) }}" class="btn {{ request('role') == 'Mahasiswi' ? 'btn-success' : 'btn-outline-success' }} px-4 shadow-sm">Mahasiswi</a>
                <a href="{{ route('tahsinCreate', ['role' => 'Staff']) }}" class="btn {{ request('role') == 'Staff' ? 'btn-info' : 'btn-outline-info' }} px-4 shadow-sm">Staff</a>
                <a href="{{ route('tahsinCreate', ['role' => 'Tendik']) }}" class="btn {{ request('role') == 'Tendik' ? 'btn-warning' : 'btn-outline-warning' }} px-4 shadow-sm">Tendik</a>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('tahsinStore') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="font-weight-bold">Nama Peserta</label>
                    <select name="id_mahasiswi" class="form-control" required>
                        <option value="">-- Pilih Nama --</option>
                        @foreach($mahasiswi as $m)
                            <option value="{{ $m->id_mahasiswi }}">{{ $m->nama_mahasiswi }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="kolomKhususMahasiswi" class="{{ request('role') == 'Mahasiswi' ? '' : 'd-none' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Program Studi</label>
                                <input type="text" name="prodi" class="form-control" placeholder="Masukkan Prodi">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Semester</label>
                                <input type="number" name="semester" class="form-control" placeholder="Contoh: 4">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Materi Tahsin</label>
                    <input type="text" name="materi" class="form-control" placeholder="Masukkan materi yang diujikan" required>
                </div>
                <div class="col-md-6">
                        <div class="form-group">
                            <label>Nilai (Huruf)</label>
                            <select name="nilai" class="form-control" required>
                                <option value="">-- Pilih Nilai --</option>
                                <option value="A">A</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>
                    </div>

                <hr>
                <button type="submit" class="btn btn-primary btn-block shadow">
                    <i class="fas fa-save mr-2"></i> Simpan Data Ujian
                </button>
            </form>
        </div>
    </div>
</div>
@endsection