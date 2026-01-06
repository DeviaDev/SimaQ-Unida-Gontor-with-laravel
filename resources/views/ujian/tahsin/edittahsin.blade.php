@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data Ujian Tahsin</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('tahsinUpdate', $tahsin->id_tahsin) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="font-weight-bold">Nama Peserta</label>
                    <select name="id_mahasiswi" class="form-control" required>
                        @foreach($mahasiswi as $m)
                            <option value="{{ $m->id_mahasiswi }}" {{ $tahsin->id_mahasiswi == $m->id_mahasiswi ? 'selected' : '' }}>
                                {{ $m->nama_mahasiswi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Program Studi</label>
                            <input type="text" name="prodi" class="form-control" value="{{ $tahsin->prodi }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="{{ $tahsin->semester }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Materi Tahsin</label>
                    <input type="text" name="materi" class="form-control" value="{{ $tahsin->materi }}" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Nilai (Opsional)</label>
                    <select name="nilai" class="form-control">
                        <option value="">-- Pilih Nilai --</option>
                        <option value="A" {{ $tahsin->nilai == 'A' ? 'selected' : '' }}>A</option>
                        <option value="A-" {{ $tahsin->nilai == 'A-' ? 'selected' : '' }}>A-</option>
                        <option value="B+" {{ $tahsin->nilai == 'B+' ? 'selected' : '' }}>B+</option>
                        <option value="B" {{ $tahsin->nilai == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C+" {{ $tahsin->nilai == 'C+' ? 'selected' : '' }}>C+</option>
                        <option value="C" {{ $tahsin->nilai == 'C' ? 'selected' : '' }}>C</option>
                        <option value="C-" {{ $tahsin->nilai == 'C-' ? 'selected' : '' }}>C-</option>
                    </select>
                </div>

                <hr>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('tahsin') }}" class="btn btn-secondary shadow-sm">Kembali</a>
                    <button type="submit" class="btn btn-primary shadow-sm">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection