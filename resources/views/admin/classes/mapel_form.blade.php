@extends('layout.app')

@section('title', 'Assign Mata Pelajaran')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Mata Pelajaran untuk {{ $class->name }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.classes.mapel.store', $class) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pilih Mapel <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                            <option value="">Pilih Mapel</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} ({{ $subject->admin->name ?? 'No Guru' }})
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-secondary me-md-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Mapel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

