@extends('layout.app')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="page-header">
    <h1>
        <i class="bi bi-shield-check"></i>
        Dashboard Super Admin
    </h1>
    <p>Kelola seluruh sistem: Guru, Siswa, dan Kelas</p>
</div>

<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <i class="bi bi-briefcase"></i>
            <div class="stat-value">{{ $totalGuru }}</div>
            <div class="stat-label">Total Guru</div>
            <a href="{{ route('admin.gurus.index') }}" class="btn btn-sm btn-primary mt-2">Kelola</a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <i class="bi bi-mortarboard"></i>
            <div class="stat-value">{{ $totalSiswa }}</div>
            <div class="stat-label">Total Siswa</div>
            <a href="{{ route('admin.siswas.index') }}" class="btn btn-sm btn-primary mt-2">Kelola</a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <i class="bi bi-building"></i>
            <div class="stat-value">{{ $totalClasses }}</div>
            <div class="stat-label">Total Kelas</div>
            <a href="{{ route('admin.classes.index') }}" class="btn btn-sm btn-primary mt-2">Kelola</a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <i class="bi bi-book"></i>
            <div class="stat-value">{{ $totalSubjects }}</div>
            <div class="stat-label">Mata Pelajaran</div>
            <a href="{{ route('admin.superusers.subjects.index') }}" class="btn btn-sm btn-primary mt-2">Kelola</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-people"></i>
                Guru Terbaru
            </div>
            <div class="card-body p-0">
                @if($recentGuru->isEmpty())
                <p class="p-4 text-muted text-center">Belum ada guru</p>
                @else
                <ul class="list-group list-group-flush">
                    @foreach($recentGuru as $guru)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $guru->name }}</strong><br>
                            <small class="text-muted">{{ $guru->email }}</small>
                        </div>
                        <a href="{{ route('admin.gurus.show', $guru) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-person-lines-fill"></i>
                Siswa Terbaru
            </div>
            <div class="card-body p-0">
                @if($recentSiswa->isEmpty())
                <p class="p-4 text-muted text-center">Belum ada siswa</p>
                @else
                <ul class="list-group list-group-flush">
                    @foreach($recentSiswa as $siswa)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $siswa->name }}</strong><br>
                            <small class="text-muted">{{ $siswa->email }}</small>
                        </div>
                        <a href="{{ route('admin.siswas.show', $siswa) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-book-half"></i> Mata Pelajaran</span>
                <span class="badge bg-info">{{ $totalSubjects }}</span>
            </div>
            <div class="card-body">
                <p class="text-muted mb-2">Kelola mata pelajaran melalui halaman guru.</p>
            </div>
        </div>
    </div>
</div>
@endsection
