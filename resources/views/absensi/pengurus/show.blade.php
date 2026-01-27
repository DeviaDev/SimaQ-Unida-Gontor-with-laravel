@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Header & Info Kegiatan --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Detail Kegiatan</h6>
            <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
                        <tr><td width="150">Nama Kegiatan</td><td>: <strong>{{ $kegiatan->nama_kegiatan }}</strong></td></tr>
                        <tr><td>Tanggal / Waktu</td><td>: {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d M Y') }} / {{ $kegiatan->waktu }}</td></tr>
                        <tr><td>Tempat</td><td>: {{ $kegiatan->tempat }}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <p><strong>Link Foto:</strong> <a href="{{ $kegiatan->link_foto }}" target="_blank">{{Str::limit($kegiatan->link_foto, 40)}}</a></p>
                    <p><strong>Berita Acara:</strong><br> {{ $kegiatan->berita_acara ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Form Absensi Pengurus --}}
    <form action="{{ route('laporan.update_absensi', $kegiatan->id) }}" method="POST">
        @csrf
        <div class="card shadow">
            <div class="card-header py-3 bg-light text-primary d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users"></i> Absensi Pengurus</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th width="5%" class="align-middle border-0">No</th>
                                <th class="align-middle border-0" style="text-align: center; padding-left: 20px;">Nama Pengurus</th>
                                <th class="align-middle border-0" width="40%">Status Kehadiran</th>
                                <th class="align-middle border-0">Keterangan (Opsional)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kegiatan->absensi as $absen)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $absen->pengurus->nama ?? 'Pengurus Terhapus' }}</td>
                                <td class="text-center">
                                    {{-- Radio Button Group --}}
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        
                                        {{-- ✅ LOGIKA DEFAULT HADIR: Jika status 'hadir' ATAU masih 'alpha' (bawaan), maka Hadir aktif --}}
                                        <label class="btn btn-sm btn-outline-success {{ ($absen->status == 'hadir' || $absen->status == 'alpha') ? 'active' : '' }}">
                                            <input type="radio" name="absensi[{{ $absen->id }}][status]" value="hadir" 
                                                {{ ($absen->status == 'hadir' || $absen->status == 'alpha') ? 'checked' : '' }}> Hadir
                                        </label>

                                        <label class="btn btn-sm btn-outline-warning {{ $absen->status == 'izin' ? 'active' : '' }}">
                                            <input type="radio" name="absensi[{{ $absen->id }}][status]" value="izin" {{ $absen->status == 'izin' ? 'checked' : '' }}> Izin
                                        </label>

                                        <label class="btn btn-sm btn-outline-info {{ $absen->status == 'sakit' ? 'active' : '' }}">
                                            <input type="radio" name="absensi[{{ $absen->id }}][status]" value="sakit" {{ $absen->status == 'sakit' ? 'checked' : '' }}> Sakit
                                        </label>

                                        <label class="btn btn-sm btn-outline-danger"> 
                                            {{-- Alpha tidak perlu active default, karena kalau kosong larinya ke Hadir --}}
                                            <input type="radio" name="absensi[{{ $absen->id }}][status]" value="alpha" {{ $absen->status == 'alpha' && $absen->status != 'alpha' ? 'checked' : '' }}> Alpha
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="absensi[{{ $absen->id }}][keterangan]" 
                                           class="form-control form-control-sm" 
                                           placeholder="Ket..." 
                                           value="{{ $absen->keterangan }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Tombol Simpan Bawah (Tetap Ada) --}}
            <div class="card-footer text-right">
                {{-- ✅ BUTTON EXPORT (Menggantikan Simpan Atas) --}}
                <a href="{{ route('laporan.export', $kegiatan->id) }}" class="btn btn-primary btn-sm font-weight-bold">
                    <i class="fas fa-file-word mr-1"></i> Export Absensi
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan Absensi
                </button>
            </div>
        </div>
    </form>
</div>
@endsection