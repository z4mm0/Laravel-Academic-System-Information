@extends('layout.app')

@section('title', isset($siswa) ? 'Edit Siswa' : 'Tambah Siswa')

@section('content')
<div class="mb-4">
    <h1>{{ isset($siswa) ? 'Edit Siswa' : 'Tambah Siswa Baru' }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($siswa) ? route('admin.siswas.update', $siswa) : route('admin.siswas.store') }}" method="POST">
            @csrf
            @if(isset($siswa))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                       value="{{ old('name', $siswa->name ?? '') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
                       value="{{ old('email', $siswa->email ?? '') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nohp" class="form-label">No HP <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nohp') is-invalid @enderror" id="nohp" name="nohp" 
                       value="{{ old('nohp', $siswa->nohp ?? '') }}" required>
                @error('nohp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="classes" class="form-label">Kelas <span class="text-danger">*</span></label>
                <div class="@error('classes') is-invalid @enderror" style="border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.5rem;">
                    @forelse($classes as $class)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="classes[]" 
                                   value="{{ $class->id }}" id="class_{{ $class->id }}"
                                   @if(in_array($class->id, old('classes', $selectedClasses ?? []))) checked @endif>
                            <label class="form-check-label" for="class_{{ $class->id }}">
                                {{ $class->name }}
                            </label>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Belum ada kelas</p>
                    @endforelse
                </div>
                @error('classes')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    Password 
                    @if(!isset($siswa))
                        <span class="text-danger">*</span>
                    @else
                        <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small>
                    @endif
                </label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" 
                       {{ !isset($siswa) ? 'required' : '' }}>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> {{ isset($siswa) ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.siswas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
