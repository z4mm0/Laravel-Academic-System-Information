@extends('layout.app')

@section('title', 'Nilai dari ' . $teacher->name ?? 'Guru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Nilai dari {{ $teacher->name ?? 'Guru' }}</h1>
    <a href="{{ route('student.grades.index') }}" class="btn btn-secondary">Kembali</a>
</div>

@if ($grades->isEmpty())
<div class="alert alert-info">
    Guru ini belum memberikan nilai kepada Anda.
</div>
@else
<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Mata Pelajaran</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                    <th>Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grades as $grade)
                <tr>
                    <td>{{ $grade->subject->name }}</td>
                    <td>
                        <span class="badge" style="background-color: {{ $grade->nilai >= 80 ? '#28a745' : ($grade->nilai >= 60 ? '#ffc107' : '#dc3545') }}">
                            {{ $grade->nilai }}
                        </span>
                    </td>
                    <td>{{ $grade->keterangan ?? '-' }}</td>
                    <td>{{ $grade->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@php
    $avg = $grades->avg('nilai');
    $max = $grades->max('nilai');
    $min = $grades->min('nilai');
@endphp

<div class="card mt-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Statistik Nilai dari Guru ini</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <h6 class="text-muted">Rata-rata</h6>
                <h3 class="text-primary">{{ number_format($avg, 2) }}</h3>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted">Tertinggi</h6>
                <h3 class="text-success">{{ $max }}</h3>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted">Terendah</h6>
                <h3 class="text-danger">{{ $min }}</h3>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted">Total Nilai</h6>
                <h3 class="text-info">{{ $grades->count() }}</h3>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
