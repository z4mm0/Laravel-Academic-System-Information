@extends('layout.app')

@section('title', 'Semua Nilai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Semua Nilai Siswa</h1>
    <a href="{{ route('guru_mapel.dashboard') }}" class="btn btn-secondary">
        Kembali ke Dashboard </a>
</div>

@if ($grades->isEmpty())
<div class="alert alert-info">
    Belum ada nilai yang diinput.
</div>
@else
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th>Siswa</th>
                <th>Mata Pelajaran</th>
                <th>Nilai</th>
                <th>Keterangan</th>
                <th>Tanggal Input</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($grades as $grade)
            <tr>
                <td>{{ $grade->student->name }}</td>
                <td>{{ $grade->subject->name }}</td>
                <td>
                    <span class="badge bg-info">{{ $grade->nilai }}</span>
                </td>
                <td>{{ $grade->keterangan ?? '-' }}</td>
                <td>{{ $grade->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $grades->links() }}
</div>
@endif
@endsection
