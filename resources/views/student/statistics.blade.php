@extends('layout.app')

@section('title', 'Statistik Nilai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Statistik Nilai Akademik Saya</h1>
    <a href="{{ route('student.grades.export-pdf') }}" class="btn btn-danger" target="_blank">
        📄 Ekspor ke PDF
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center bg-primary text-white">
            <div class="card-body">
                <h6 class="card-title">Total Nilai</h6>
                <h2>{{ $totalGrades }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-success text-white">
            <div class="card-body">
                <h6 class="card-title">Rata-rata</h6>
                <h2>{{ number_format($averageGrade, 2) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-info text-white">
            <div class="card-body">
                <h6 class="card-title">Tertinggi</h6>
                <h2>{{ $highestGrade }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center bg-danger text-white">
            <div class="card-body">
                <h6 class="card-title">Terendah</h6>
                <h2>{{ $lowestGrade }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Distribusi Nilai</h5>
            </div>
            <div class="card-body">
                @php
                    $excellent = $grades->where('nilai', '>=', 90)->count();
                    $good = $grades->where('nilai', '>=', 80)->where('nilai', '<', 90)->count();
                    $fair = $grades->where('nilai', '>=', 60)->where('nilai', '<', 80)->count();
                    $poor = $grades->where('nilai', '<', 60)->count();
                @endphp

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Sangat Baik (90-100)</span>
                        <span class="badge bg-success">{{ $excellent }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ $totalGrades > 0 ? ($excellent/$totalGrades*100) : 0 }}%"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Baik (80-89)</span>
                        <span class="badge bg-info">{{ $good }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: {{ $totalGrades > 0 ? ($good/$totalGrades*100) : 0 }}%"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Cukup (60-79)</span>
                        <span class="badge bg-warning">{{ $fair }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-warning" style="width: {{ $totalGrades > 0 ? ($fair/$totalGrades*100) : 0 }}%"></div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Kurang (< 60)</span>
                        <span class="badge bg-danger">{{ $poor }}</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar bg-danger" style="width: {{ $totalGrades > 0 ? ($poor/$totalGrades*100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Semua Nilai</h5>
            </div>
            <div class="card-body">
                @if ($grades->isEmpty())
                <p class="text-muted">Belum ada nilai.</p>
                @else
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grades as $grade)
                        <tr>
                            <td>{{ $grade->subject->name }}</td>
                            <td>{{ $grade->teacher->name }}</td>
                            <td>
                                <span class="badge" style="background-color: {{ $grade->nilai >= 80 ? '#28a745' : ($grade->nilai >= 60 ? '#ffc107' : '#dc3545') }}">
                                    {{ $grade->nilai }}
                                </span>
                            </td>
                            <td>{{ $grade->keterangan ?? '-' }}</td>
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
