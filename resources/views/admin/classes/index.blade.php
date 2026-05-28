@extends('layout.app')

@section('title', 'Manajemen Kelas - Super Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Kelola Kelas</h1>
    <a href="{{ route('admin.classes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Kelas
    </a>
</div>

@if ($classes->isEmpty())
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> Belum ada kelas terdaftar.
</div>
@else
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama Kelas</th>
                <th>Guru Pembimbing</th>
                <th>Jumlah Siswa</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($classes as $index => $class)
            <tr>
                <td>{{ ($classes->currentPage() - 1) * $classes->perPage() + $index + 1 }}</td>
                <td><strong>{{ $class->name }}</strong></td>
                <td>
                    @if($class->admin)
                        <span class="badge bg-success">{{ $class->admin->name }}</span>
                    @else
                        <span class="badge bg-secondary">Belum ditugaskan</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-info">{{ $class->students->count() }}</span>
                </td>
                <td>
                    <a href="{{ route('admin.classes.show', $class) }}" class="btn btn-sm btn-info" title="Lihat">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-sm btn-warning" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" style="display:inline;" class="d-inline" onsubmit="return confirm('Yakin hapus kelas {{ $class->name }}?');">
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
{{ $classes->links() }}
@endif
@endsection

