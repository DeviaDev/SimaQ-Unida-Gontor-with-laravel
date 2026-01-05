@extends('layouts/app')

@section('content')

<div class="card shadow">
    <div class="card-header">
        <h3>
            <i class="fas fa-users mr-2"></i>Absensi Kegiatan Pengurus
        </h3> 
    </div>
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('lailatu.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" class="form-control" value="Malam Lailatu Tahfidz" required>
                </div>
                <div class="col-md-6">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Alpha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengurus as $p)
                    <tr>
                        <td>
                            {{ $p->nama }} <br>
                            <small class="text-muted">{{ $p->nim }}</small>
                        </td>
                        <td>
                            <input type="radio" name="kehadiran[{{ $p->id }}]" value="Hadir" checked>
                        </td>
                        <td>
                            <input type="radio" name="kehadiran[{{ $p->id }}]" value="Izin">
                        </td>
                        <td>
                            <input type="radio" name="kehadiran[{{ $p->id }}]" value="Alpha">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary mt-3">Simpan Absensi</button>
        </form>
    </div>
</div>
@endsection