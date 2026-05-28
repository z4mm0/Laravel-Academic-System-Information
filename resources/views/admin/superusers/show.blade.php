@extends('layout.app')

@section('title', 'Detail Pengguna - ' . $user->name)

@section('content')
<div class="mb-4">
    <h1>Detail Pengguna: {{ $user->name }}</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Informasi Utama</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td><span class="badge bg-{{ $user->role === 'siswa' ? 'primary' : 'warning' }}">{{ ucfirst($user->role) }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>No HP:</strong></td>
                        <td>{{ $user->nohp }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat:</strong></td>
                        <td>{{ $user->alamat }}</td>
                    </tr>
                    <tr>
                        <td><strong>Password (Plaintext):</strong></td>
                        <td>
                            <div class="alert alert-warning">
                                <strong>{{ $user->password }}</strong>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if($user->role === 'siswa' && isset($classes))
        <div class="card">
            <div class="card-header"><h6>Kelas Siswa</h6></div>
            <div class="card-body">
                @forelse($classes as $class)
                    <span class="badge bg-info">{{ $class->name }}</span>
                @empty
                    <span class="text-muted">Tidak ada kelas</span>
                @endforelse
            </div>
        </div>
        @endif

        @if($user->role === 'admin' && isset($classesTeacher))
        <div class="card">
            <div class="card-header"><h6>Kelas Guru</h6></div>
            <div class="card-body">
                @forelse($classesTeacher as $class)
                    <span class="badge bg-success">{{ $class->name }}</span>
                @empty
                    <span class="text-muted">Tidak ada kelas</span>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</div>

<div class="mt-4">
    <a href="{{ route('admin.superusers.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>
@endsection

