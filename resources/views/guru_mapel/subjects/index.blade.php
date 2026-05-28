@extends('layout.app')

@section('title', 'Mata Pelajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Mata Pelajaran Saya</h1>
    <a href="{{ route('guru_mapel.subjects.create') }}" class="btn btn-primary">
        ➕ Tambah Mata Pelajaran
    </a>
</div>

@if ($subjects->isEmpty())
<div class="alert alert-info">
    Anda belum memiliki mata pelajaran. 
    <a href="{{ route('guru_mapel.subjects.create') }}">Tambahkan sekarang</a>.
</div>
@else
<div class="row">
    @foreach ($subjects as $subject)
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $subject->name }}</h5>
                <p class="card-text text-muted">
                    Dibuat: {{ $subject->created_at->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
