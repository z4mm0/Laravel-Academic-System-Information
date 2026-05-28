@extends('layout.app')

@section('title', 'Input Nilai di Kelas Lain')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Input Nilai di Kelas Lain</h3>
                <a href="{{ route('guru_mapel.dashboard') }}" class="btn btn-secondary btn-sm">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Form Input Nilai</h5>
            <small>Pilih siswa, kelas, dan mata pelajaran untuk input nilai</small>
        </div>
        <div class="card-body">
            <form action="{{ route('guru_mapel.grade.other-class.store') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Pilih Siswa Langsung -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Pilih Siswa <span class="text-danger">*</span></label>
                        <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($classes as $class)
                                <optgroup label="{{ $class->name }}">
                                    @foreach($class->students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @endif
                    </div>

                    <!-- Pilih Mata Pelajaran -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Nilai (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror" 
                               min="0" max="100" value="{{ old('nilai') }}" required>
                        @error('nilai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @endif
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Keterangan</label>
                        <select name="keterangan" class="form-select">
                            <option value="">-- Tidak ada --</option>
                            <option value="Sangat Baik">Sangat Baik</option>
                            <option value="Baik">Baik</option>
                            <option value="Cukup">Cukup</option>
                            <option value="Kurang">Kurang</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            Simpan Nilai
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-info mt-3">
        <strong>Informasi:</strong>
        <ul class="mb-0">
            <li>Pilih siswa dari Optgroup (kelas)</li>
            <li>Anda hanya bisa input nilai untuk mata pelajaran Anda</li>
        </ul>
    </div>
</div>
@endsection
