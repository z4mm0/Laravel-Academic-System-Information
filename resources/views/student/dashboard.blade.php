@extends('layout.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Dashboard Siswa</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">Kelas Saya</h6>
                <h2 class="text-primary">{{ $classes->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">Total Nilai</h6>
                <h2 class="text-success">{{ $grades->count() }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6 class="card-title text-muted">Rata-rata Nilai</h6>
                <h2 class="text-info">
                    @if ($grades->count() > 0)
                        {{ number_format($grades->avg('nilai'), 2) }}
                    @else
                        -
                    @endif
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Nilai Terbaru</h5>
            </div>
            <div class="card-body">
                @if ($grades->isEmpty())
                <p class="text-muted">Anda belum memiliki nilai dari guru manapun.</p>
                @else
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Guru</th>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grades as $grade)
                        <tr>
                            <td>{{ $grade->subject->name }}</td>
                            <td>{{ $grade->teacher->name }}</td>
                            <td><span class="badge bg-info">{{ $grade->nilai }}</span></td>
                            <td>{{ $grade->keterangan ?? '-' }}</td>
                            <td>{{ $grade->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $grades->links() }}
                </div>
                @endif
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('student.grades.index') }}" class="btn btn-primary">
                📊 Lihat Semua Nilai
            </a>
            <a href="{{ route('student.statistics') }}" class="btn btn-secondary">
                📈 Lihat Statistik
            </a>
        </div>
    </div>
</div>
@endsection
