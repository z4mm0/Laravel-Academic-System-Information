@extends('layout.app')

@section('title', 'Manajemen Guru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Guru</h1>
    <a href="{{ route('admin.gurus.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Guru
    </a>
</div>

@if ($gurus->isEmpty())
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> Belum ada guru terdaftar.
</div>
@else
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gurus as $index => $guru)
            <tr>
                <td>{{ ($gurus->currentPage() - 1) * $gurus->perPage() + $index + 1 }}</td>
                <td>{{ $guru->name }}</td>
                <td>{{ $guru->email }}</td>
                <td>{{ $guru->nohp }}</td>
                <td>{{ Str::limit($guru->alamat, 30) }}</td>
                <td>
                    <a href="{{ route('admin.gurus.show', $guru) }}" class="btn btn-sm btn-info" title="Lihat">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('admin.gurus.edit', $guru) }}" class="btn btn-sm btn-warning" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.gurus.destroy', $guru) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus guru ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $gurus->links() }}
</div>
@endif
@endsection
