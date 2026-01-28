@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Data Ujian Tahsin</h1>
        <a href="{{ route('tahsin', ['role' => $role]) }}" class="btn btn-secondary btn-sm shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('tahsinUpdate', $item->id_tahsin) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Simpan Kategori agar tidak berubah --}}
                <input type="hidden" name="kategori" value="{{ $item->kategori }}">

                <div class="form-group">
                    <label class="font-weight-bold">Nama Peserta ({{ $item->kategori }})</label>
                    <select name="id_mahasiswi" id="selectMahasiswi" class="form-control select2" required>
                        <option value="">-- Pilih Nama --</option>
                        
                        @foreach($peserta as $p)
                            @php
                                // Tentukan ID Peserta saat ini (dari loop)
                                $currentId = $p->id_mahasiswi ?? $p->id_dosen ?? $p->id_muhafidzoh ?? $p->id;
                                
                                // Tentukan Nama Peserta saat ini
                                $currentName = $p->nama_mahasiswi ?? $p->nama_dosen ?? $p->nama_muhafidzoh ?? $p->nama;

                                // Tentukan Data Prodi/Semester (Hanya untuk Mahasiswi)
                                $dataProdi = ($role == 'Mahasiswi') ? ($p->prodi ?? '-') : '-';
                                $dataSem   = ($role == 'Mahasiswi') ? ($p->semester ?? '-') : '-';
                            @endphp

                            <option value="{{ $currentId }}" 
                                data-prodi="{{ $dataProdi }}" 
                                data-semester="{{ $dataSem }}"
                                {{-- Jika ID di database SAMA dengan ID di loop, maka PILIH (SELECTED) --}}
                                {{ $item->id_mahasiswi == $currentId ? 'selected' : '' }}>
                                
                                {{ $currentName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Prodi</label>
                            <input type="text" name="prodi" id="prodi" class="form-control bg-light" readonly 
                                   value="{{ $item->prodi }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Semester</label>
                            <input type="text" name="semester" id="semester" class="form-control bg-light" readonly 
                                   value="{{ ($item->semester == 0) ? '-' : $item->semester }}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Materi Ujian</label>
                    <input type="text" name="materi" class="form-control" value="{{ $item->materi }}" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Nilai (Huruf)</label>
                    <select name="nilai" class="form-control" required>
                        <option value="">-- Pilih Nilai --</option>
                        @foreach(['A','A-','B+','B','B-','C+','C','C-','D','E'] as $val)
                            <option value="{{ $val }}" {{ $item->nilai == $val ? 'selected' : '' }}>
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary btn-block shadow">
                    <i class="fas fa-save mr-2"></i> Update Data
                </button>

            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#selectMahasiswi').on('change', function() {
        let selectedOption = $(this).find(':selected');
        $('#prodi').val(selectedOption.data('prodi'));
        $('#semester').val(selectedOption.data('semester'));
    });
});
</script>
@endsection