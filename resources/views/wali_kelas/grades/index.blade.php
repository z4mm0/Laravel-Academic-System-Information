@extends('layout.app')

@section('title', 'Nilai Kelas - ' . $class->name)

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h1>Nilai Kelas {{ $class->name }}</h1>
        <p class="text-muted">Edit nilai siswa untuk mata pelajaran yang sudah ditugaskan ke kelas ini.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('wali_kelas.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Mapel</th>
                    <th>Nilai</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    @foreach ($subjects as $subject)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $subject->name }}</td>
                            @php
                                $gradeKey = $student->id . '-' . $subject->id;
                                $grade = $grades->get($gradeKey);
                            @endphp
                            <td>{{ $grade ? $grade->nilai : '-' }}</td>
                            <td>{{ $grade ? ($grade->keterangan ?? '-') : '-' }}</td>
                            <td>
                                @if($grade)
                                    <a href="{{ route('wali_kelas.grade.edit', [$class, $student, $subject->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                                @else
                                    <span class="text-muted small">Belum ada nilai</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
