@extends('layout.app')

@section('title', 'Input/Edit Nilai - ' . $student->name)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $grade ? 'Edit' : 'Input' }} Nilai {{ $student->name }} - {{ $subject->name }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ $grade ? route('wali_kelas.grade.update', [$class, $student, $subject]) : route('wali_kelas.grade.store', [$class, $student, $subject]) }}">
                        @csrf
                        @if($grade)
                        @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Siswa</label>
                            <input type="text" class="form-control" value="{{ $student->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mata Pelajaran</label>
                            <input type="text" class="form-control" value="{{ $subject->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nilai <span class="text-danger">*</span></label>
                            <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror" 
                                   value="{{ old('nilai', $grade->nilai ?? '') }}" min="0" max="100" step="1" required>
                            @error('nilai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3">{{ old('keterangan', $grade->keterangan ?? '') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('wali_kelas.class.grades', $class) }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">{{ $grade ? 'Update' : 'Simpan' }} Nilai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

