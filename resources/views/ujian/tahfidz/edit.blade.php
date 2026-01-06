@extends('layouts/app')

@section('content')
<h1 class="h3 mb-4 text-gray-800">
    <i class="fas fa-edit mr-2"></i>
    {{ $title }}
</h1>

<div class="card">
    <div class="card-body">
        <form action="{{ route('mandiriUpdate', $ujian->id_ujian_mandiri) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="id_mahasiswi">Mahasiswi</label>
                <select name="id_mahasiswi" id="id_mahasiswi" class="form-control" required>
                    <option value="">Pilih Mahasiswi</option>
                    @foreach($mahasiswi as $mhs)
                        <option value="{{ $mhs->id_mahasiswi }}" {{ $ujian->id_mahasiswi == $mhs->id_mahasiswi ? 'selected' : '' }}>
                            {{ $mhs->nama_mahasiswi }} - {{ $mhs->nim }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="materi">Materi</label>
                <input type="text" name="materi" id="materi" class="form-control" value="{{ $ujian->materi }}" required>
            </div>

            <div class="form-group">
                <label for="keterangan_ujian">Keterangan Ujian</label>
                <select name="keterangan_ujian" id="keterangan_ujian" class="form-control" required>
                    <option value="Mandiri" {{ $ujian->keterangan_ujian == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                    <option value="Serentak" {{ $ujian->keterangan_ujian == 'Serentak' ? 'selected' : '' }}>Serentak</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nilai">Nilai</label>
                <select name="nilai" id="nilai" class="form-control" required>
                    <option value="a" {{ $ujian->nilai == 'a' ? 'selected' : '' }}>A</option>
                    <option value="a-" {{ $ujian->nilai == 'a-' ? 'selected' : '' }}>A-</option>
                    <option value="b+" {{ $ujian->nilai == 'b+' ? 'selected' : '' }}>B+</option>
                    <option value="b" {{ $ujian->nilai == 'b' ? 'selected' : '' }}>B</option>
                    <option value="b-" {{ $ujian->nilai == 'b-' ? 'selected' : '' }}>B-</option>
                    <option value="c+" {{ $ujian->nilai == 'c+' ? 'selected' : '' }}>C+</option>
                    <option value="c" {{ $ujian->nilai == 'c' ? 'selected' : '' }}>C</option>
                    <option value="c-" {{ $ujian->nilai == 'c-' ? 'selected' : '' }}>C-</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('mandiri') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
