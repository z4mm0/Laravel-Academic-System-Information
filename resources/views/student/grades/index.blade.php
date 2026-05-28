@extends('layout.app')

@section('title', 'Nilai Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Nilai Saya</h1>
    <a href="{{ route('student.grades.export-pdf') }}" class="btn btn-danger" target="_blank">
        📄 Ekspor ke PDF
    </a>
</div>

@if ($grades->isEmpty())
<div class="alert alert-info">
    Anda belum memiliki nilai dari guru manapun.
</div>
@else
<div class="row">
    @foreach ($grades as $teacherName => $teacherGrades)
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">👨‍🏫 {{ $teacherName }}</h5>
                    <small>{{ $teacherGrades->count() }} Mata Pelajaran</small>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Nilai</th>
                            <th>Keterangan</th>
                            <th>Tanggal Input</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teacherGrades as $grade)
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

                @php
                    $avg = $teacherGrades->avg('nilai');
                    $max = $teacherGrades->max('nilai');
                    $min = $teacherGrades->min('nilai');
                @endphp

                <div class="mt-3 pt-3 border-top">
                    <small class="text-muted">
                        Rata-rata: <strong>{{ number_format($avg, 2) }}</strong> | 
                        Tertinggi: <strong>{{ $max }}</strong> | 
                        Terendah: <strong>{{ $min }}</strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
