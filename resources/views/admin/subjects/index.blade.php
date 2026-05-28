@extends('layout.app')

@section('title', 'Kelola Mata Pelajaran')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Kelola Mata Pelajaran</h1>
        <a href="{{ route('admin.superusers.subjects.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Tambah Mata Pelajaran
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Mata Pelajaran</th>
                        <th>Guru Pengajar</th>
                        <th>Jumlah Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subjects as $subject)
                    <tr>
                        <td>{{ $subject->name }}</td>
                        <td>{{ $subject->admin->name ?? 'Belum ditugaskan' }}</td>
                        <td>{{ $subject->classes->count() }}</td>
                        <td>
                            <a href="{{ route('admin.superusers.subjects.show', $subject) }}" class="btn btn-sm btn-info">Detail</a>
                            <a href="{{ route('admin.superusers.subjects.edit', $subject) }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="{{ route('admin.superusers.subjects.classes', $subject) }}" class="btn btn-sm btn-success">Kelola Kelas</a>
                            <form action="{{ route('admin.superusers.subjects.destroy', $subject) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus mata pelajaran {{ $subject->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada mata pelajaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $subjects->links() }}
        </div>
    </div>
</div>
@endsection