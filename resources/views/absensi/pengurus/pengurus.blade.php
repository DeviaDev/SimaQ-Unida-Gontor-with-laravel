@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">
            <i class="fas fa-clipboard-list mr-2"></i>Absensi Kegiatan Pengurus
        </h1>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
            <i class="fas fa-plus"></i> Tambah Kegiatan
        </button>
    </div>

    {{-- Tabel List Kegiatan --}}
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Kegiatan</th>
                            <th>Tempat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kegiatan as $k)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}</td>
                            <td>{{ $k->nama_kegiatan }}</td>
                            <td>{{ $k->tempat ?? '-' }}</td>
                            <td>
                                <a href="{{ route('laporan.show', $k->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-clipboard-list"></i> Buka Absensi
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Kegiatan --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('laporan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kegiatan Baru</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Waktu</label>
                            <input type="time" name="waktu" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tempat</label>
                        <input type="text" name="tempat" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Link Foto (GDrive)</label>
                        <input type="text" name="link_foto" class="form-control" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label>Berita Acara / Deskripsi</label>
                        <textarea name="berita_acara" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan & Lanjut ke Absen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection