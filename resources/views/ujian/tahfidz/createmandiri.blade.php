@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('mandiri') }}" class="btn btn-sm btn-success">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <form action="{{ route('mandiriStore') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Mahasiswi</label>
                <select name="id_mahasiswi" class="form-control" required>
                    <option value="">-- Pilih Mahasiswi --</option>
                    @foreach($mahasiswi as $m)
                        <option value="{{ $m->id_mahasiswi }}">{{ $m->nama_mahasiswi }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Materi</label>
                <input type="text" name="materi" class="form-control" placeholder="Masukkan Materi" required>
            </div>

            <div class="form-group">
            <label>Keterangan Ujian</label>
            <select name="keterangan_ujian" class="form-control" required>
        
        <option value="">-- Pilih Keterangan Ujian --</option>
        <option value="Mandiri">Mandiri</option>
        <option value="Serentak">Serentak</option>
        </select>
        </div>


        <div class="form-group">
            <label>Nilai</label>
            <select name="nilai" class="form-control" required>
        <option value="">-- Pilih Nilai --</option>
        <option value="a">A</option>
        <option value="a-">A-</option>
        <option value="b+">B+</option>
        <option value="b">B</option>
        <option value="b-">B-</option>
        <option value="c+">C+</option>
        <option value="c">C</option>
        <option value="c-">C-</option>
        </select>
        </div>


            

            <button type="submit" class="btn btn-primary">Simpan Data</button>
        </form>
    </div>
</div>
@endsection