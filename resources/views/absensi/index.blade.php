@extends('layouts.app')

@section('content')
<div class="container">
    <h1>📝 Absensi</h1>

    <h3>Absensi Anggota</h3>
    <ul>
        <li><a href="{{ url('/absensi/anggota/tahfidz') }}">Tahfidz (Mahasiswi & Muhafidzoh)</a></li>
        <li><a href="{{ url('/absensi/anggota/tilawah') }}">Tilawah (Mahasiswi, Muhafidzoh, Staf, Dosen)</a></li>
    </ul>

    <h3>Absensi Pengurus</h3>
    <ul>
        <li><a href="{{ url('/absensi/pengurus/lailatu') }}">Malam Lailatu Tahfidz</a></li>
        <li><a href="{{ url('/absensi/pengurus/tilawah') }}">Tilawah</a></li>
        <li><a href="{{ url('/absensi/pengurus/taujihat') }}">Taujihat</a></li>
    </ul>
</div>
@endsection
