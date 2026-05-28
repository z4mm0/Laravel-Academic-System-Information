@extends('layout.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Dashboard Guru</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Kelas</h6>
                <h2 class="text-primary">{{ $classes->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Siswa</h6>
                <h2 class="text-success">{{ $totalStudents }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">Mata Pelajaran</h6>
                <h2 class="text-info">{{ $subjects->count() }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="btn-group" role="group">
            <a href="{{ route('guru_mapel.grade.refactored.form') }}" class="btn btn-success">
                ➕ Tambah Nilai
            </a>
            <a href="{{ route('guru_mapel.grades.all') }}" class="btn btn-outline-primary">
                Lihat Semua Nilai
            </a>
        
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Kelas Saya</h5>
            </div>
            <div class="card-body">
                @if ($classes->isEmpty())
                <p class="text-muted">Anda belum memiliki kelas. Hubungi admin untuk ditambahkan.</p>
                @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Kelas</th>
                            <th>Jumlah Siswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classes as $class)
                        <tr>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->students->count() }}</td>
                            <td>
<a href="{{ route('guru_mapel.class.show', $class) }}" class="btn btn-sm btn-primary">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
