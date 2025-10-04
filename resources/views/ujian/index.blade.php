@extends('layouts.app')

@section('content')
<div class="container">
    <h1>📚 Ujian</h1>
    <ul>
        <li><a href="{{ url('/ujian/mandiri') }}">Ujian Mandiri</a></li>
        <li><a href="{{ url('/ujian/serentak') }}">Ujian Serentak</a></li>
        <li><a href="{{ url('/ujian/remedial') }}">Ujian Remedial</a></li>
        <li><a href="{{ url('/ujian/tahsin') }}">Ujian Tahsin</a></li>
    </ul>
</div>
@endsection
