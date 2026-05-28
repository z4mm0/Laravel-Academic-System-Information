@extends('layout.app')

@section('title', 'Manajemen Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manajemen Siswa</h1>
    <a href="{{ route('admin.siswas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Siswa
    </a>
</div>

@if ($siswas->isEmpty())
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> Belum ada siswa terdaftar.
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
                <th>Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswas as $index => $siswa)
            <tr>
                <td>{{ ($siswas->currentPage() - 1) * $siswas->perPage() + $index + 1 }}</td>
                <td>{{ $siswa->name }}</td>
                <td>{{ $siswa->email }}</td>
                <td>{{ $siswa->nohp }}</td>
                <td>{{ Str::limit($siswa->alamat, 30) }}</td>
                <td>
                    @if($siswa->classesAsStudent->isEmpty())
                        <span class="badge bg-secondary">Belum ada</span>
                    @else
                        @foreach($siswa->classesAsStudent as $class)
                            <span class="badge bg-info">{{ $class->name }}</span>
                        @endforeach
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.siswas.show', $siswa) }}" class="btn btn-sm btn-info" title="Lihat">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('admin.siswas.edit', $siswa) }}" class="btn btn-sm btn-warning" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.siswas.destroy', $siswa) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
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
    {{ $siswas->links() }}
</div>
@endif
@endsection
