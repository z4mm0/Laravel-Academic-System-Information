@extends('layout.app')

@section('title', 'Detail Guru - ' . $guru->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.gurus.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-fill"></i> Informasi Guru</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 200px;"><strong>Nama</strong></td>
                        <td>{{ $guru->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $guru->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>No HP</strong></td>
                        <td>{{ $guru->nohp }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>{{ $guru->alamat }}</td>
                    </tr>
                    <tr>
                        <td><strong>Terdaftar Sejak</strong></td>
                        <td>{{ $guru->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-building"></i> Kelas ({{ $classes->count() }})</h5>
            </div>
            <div class="card-body">
                @if($classes->isEmpty())
                    <p class="text-muted">Belum mengampu kelas apapun</p>
                @else
                    <ul class="list-group">
                        @foreach($classes as $class)
                            <li class="list-group-item">
                                <strong>{{ $class->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $class->students->count() }} siswa</small>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-book-fill"></i> Mata Pelajaran ({{ $subjects->count() }})</h5>
            </div>
            <div class="card-body">
                @if($subjects->isEmpty())
                    <p class="text-muted">Belum mengajar mata pelajaran apapun</p>
                @else
                    <ul class="list-group">
                        @foreach($subjects as $subject)
                            <li class="list-group-item">
                                {{ $subject->name }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Aksi</h5>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.gurus.edit', $guru) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Guru
                </a>
                <form action="{{ route('admin.gurus.destroy', $guru) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Hapus Guru
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
