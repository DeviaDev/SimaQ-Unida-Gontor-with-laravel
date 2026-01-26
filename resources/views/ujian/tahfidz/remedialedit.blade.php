@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Data Remedial</h6>
        </div>
        <div class="card-body">
            {{-- Form update mengarah ke route 'remedialUpdate' --}}
            <form action="{{ route('remedialUpdate', $remedial->id) }}" method="POST">
                @csrf
                
                {{-- INFO MAHASISWA (READ ONLY / TIDAK BISA DIEDIT) --}}
                <div class="form-group">
                    <label class="font-weight-bold">Nama Mahasiswi</label>
                    <input type="text" class="form-control" value="{{ $remedial->mahasiswi->nama_mahasiswi ?? '-' }}" readonly>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Materi</label>
                            <input type="text" class="form-control" value="{{ $remedial->materi }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group">
                            <label class="font-weight-bold">Nilai Awal</label>
                            <input type="text" class="form-control" value="{{ $remedial->nilai }}" readonly>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- INPUT NILAI REMEDIAL (YANG BISA DIEDIT) --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold text-primary">Nilai Remedial Baru</label>
                            <select name="nilai_remedial" class="form-control @error('nilai_remedial') is-invalid @enderror">
                                <option value="">-- Pilih Nilai --</option>
                                {{-- Loop opsi nilai --}}
                                @foreach(['A', 'A-', 'B+', 'B', 'C+', 'C'] as $n)
                                    <option value="{{ $n }}" {{ old('nilai_remedial', $remedial->nilai_remedial) == $n ? 'selected' : '' }}>
                                        {{ $n }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Kosongkan jika mahasiswi belum ujian remedial.</small>
                            @error('nilai_remedial')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('remedial') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection