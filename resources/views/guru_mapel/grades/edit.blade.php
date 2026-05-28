@extends('layout.app')

@section('title', 'Edit Nilai - ' . $student->name . ' - ' . $subject->name)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Edit Nilai {{ $student->name }}</h3>
                <a href="{{ route('guru_mapel.grades.index', $class) }}" class="btn btn-secondary">
                    ← Kembali ke Daftar Nilai
                </a>
            </div>

            <div class="card">
                <div class="card-body">
<form action="{{ route('guru_mapel.grades.update', [$class, $grade]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Siswa</label>
                                <input type="text" class="form-control bg-light" value="{{ $student->name }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mata Pelajaran</label>
                                <input type="text" class="form-control bg-light" value="{{ $subject->name }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nilai <span class="text-danger">*</span></label>
                            <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror" 
                                   min="0" max="100" value="{{ old('nilai', $grade->nilai) }}" required>
                            @error('nilai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" 
                                      rows="2">{{ old('keterangan', $grade->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                💾 Simpan Perubahan
                            </button>
                            <a href="{{ route('guru_mapel.grades.index', $class) }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

