@extends('layout.app')

@section('title', isset($guru) ? 'Edit Guru' : 'Tambah Guru')

@section('content')
<div class="mb-4">
    <h1>{{ isset($guru) ? 'Edit Guru' : 'Tambah Guru Baru' }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ isset($guru) ? route('admin.gurus.update', $guru) : route('admin.gurus.store') }}" method="POST">
            @csrf
            @if(isset($guru))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label for="name" class="form-label">Nama Guru <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                       value="{{ old('name', $guru->name ?? '') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
                       value="{{ old('email', $guru->email ?? '') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nohp" class="form-label">No HP <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nohp') is-invalid @enderror" id="nohp" name="nohp" 
                       value="{{ old('nohp', $guru->nohp ?? '') }}" required>
                @error('nohp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $guru->alamat ?? '') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">
                    Password 
                    @if(!isset($guru))
                        <span class="text-danger">*</span>
                    @else
                        <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small>
                    @endif
                </label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" 
                       {{ !isset($guru) ? 'required' : '' }}>
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
                    <i class="bi bi-check-circle"></i> {{ isset($guru) ? 'Update' : 'Simpan' }}
                </button>
                <a href="{{ route('admin.gurus.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
