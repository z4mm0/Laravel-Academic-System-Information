@extends('layout.app')

@section('title', 'Edit Nilai - ' . $student->name)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Edit Nilai untuk {{ $student->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('wali_kelas.grade.update', [$class, $student, $grade->subject_id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <input type="text" class="form-control" value="{{ $grade->subject->name }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nilai (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror"
                               min="0" max="100" value="{{ old('nilai', $grade->nilai) }}" required>
                        @error('nilai')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                               value="{{ old('keterangan', $grade->keterangan) }}">
                        @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                        <a href="{{ route('wali_kelas.class.grades', $class) }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
