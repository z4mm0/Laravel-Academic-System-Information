@extends('layout.app')

@section('title', isset($class) ? 'Edit Kelas' : 'Tambah Kelas')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>{{ isset($class) ? 'Edit Kelas' : 'Tambah Kelas Baru' }}</h4>
            </div>
            <div class="card-body">
               <form action="{{ isset($class) ? route('admin.classes.update', $class->id) : route('admin.classes.store') }}" method="POST">
    @csrf
    @if(isset($class))
        @method('PUT')
    @endif

                    <div class="mb-3">
                        <label class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $class->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Wali Kelas <span class="text-danger">*</span></label>
                        <select name="admin_id" class="form-select @error('admin_id') is-invalid @enderror" required>
                            <option value="">Pilih Wali Kelas</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ old('admin_id', $class->admin_id ?? '') == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->name }} ({{ $guru->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('admin_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi {{ isset($class) ? 'bi-check-circle' : 'bi-plus-circle' }}"></i>
                            {{ isset($class) ? 'Update' : 'Tambah' }} Kelas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

