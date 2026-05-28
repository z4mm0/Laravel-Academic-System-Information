@extends('layout.app')

@section('title', 'Detail Mata Pelajaran - ' . $subject->name)

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $subject->name }}</h1>
        <div>
            <a href="{{ route('admin.superusers.subjects.edit', $subject) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.superusers.subjects.classes', $subject) }}" class="btn btn-success me-2">
                <i class="bi bi-building"></i> Kelola Kelas
            </a>
            <a href="{{ route('admin.superusers.subjects.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Informasi Mata Pelajaran</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $subject->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Guru Pengajar:</strong></td>
                        <td>
                            @if($subject->admin)
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    {{ $subject->admin->name }}
                                </span>
                                <br><small class="text-muted">{{ $subject->admin->email }}</small>
                            @else
                                <span class="badge bg-secondary">Belum ditugaskan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Kelas:</strong></td>
                        <td><span class="badge bg-info fs-6">{{ $subject->classes->count() }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Jumlah Nilai:</strong></td>
                        <td><span class="badge bg-primary fs-6">{{ $subject->grades->count() }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat:</strong></td>
                        <td>{{ $subject->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($subject->classes->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h5>Kelas yang Menggunakan Mata Pelajaran Ini</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Kelas</th>
                                <th>Wali Kelas</th>
                                <th>Jumlah Siswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subject->classes as $class)
                            <tr>
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->admin->name ?? 'Belum ditugaskan' }}</td>
                                <td>{{ $class->students->count() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.superusers.subjects.edit', $subject) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit Mata Pelajaran
                </a>
                <a href="{{ route('admin.superusers.subjects.classes', $subject) }}" class="btn btn-success w-100 mb-2">
                    <i class="bi bi-building"></i> Kelola Kelas
                </a>
                <form action="{{ route('admin.superusers.subjects.destroy', $subject) }}" method="POST" onsubmit="return confirm('Yakin hapus mata pelajaran {{ $subject->name }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Hapus Mata Pelajaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection