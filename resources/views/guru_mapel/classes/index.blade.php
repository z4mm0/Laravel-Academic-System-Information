@extends('layout.app')

@section('title', 'Daftar Kelas')

@section('content')
<h1 class="mb-4">Kelas Saya</h1>

@if ($classes->isEmpty())
<div class="alert alert-info">
    Anda belum memiliki kelas. Hubungi admin untuk ditambahkan.
</div>
@else
<div class="row">
    @foreach ($classes as $class)
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $class->name }}</h5>
                <p class="card-text text-muted">
                    Jumlah Siswa: <strong>{{ $class->students->count() }}</strong>
                </p>
                <a href="{{ route('guru_mapel.class.show', $class) }}" class="btn btn-primary">
                    Lihat Detail
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
