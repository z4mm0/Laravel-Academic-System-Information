@extends('layout.app')

@section('title', 'Kelola Kelas - ' . $subject->name)

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Kelola Kelas untuk {{ $subject->name }}</h1>
        <a href="{{ route('admin.superusers.subjects.show', $subject) }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Detail
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Pilih Kelas yang Akan Mendapatkan Mata Pelajaran Ini</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.superusers.subjects.assign-classes', $subject) }}" method="POST">
            @csrf

            <div class="row">
                @foreach($classes as $class)
                <div class="col-md-4 mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="classes[]" value="{{ $class->id }}" id="class{{ $class->id }}" {{ in_array($class->id, $assignedClasses) ? 'checked' : '' }}>
                        <label class="form-check-label" for="class{{ $class->id }}">
                            <strong>{{ $class->name }}</strong><br>
                            <small class="text-muted">Wali: {{ $class->admin->name ?? 'Belum ditugaskan' }}</small><br>
                            <small class="text-muted">Siswa: {{ $class->students->count() }}</small>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection