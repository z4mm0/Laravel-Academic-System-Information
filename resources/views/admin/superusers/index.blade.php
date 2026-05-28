@extends('layout.app')

@section('title', 'Manajemen Seluruh Pengguna - Super Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Seluruh Pengguna (Siswa & Guru)</h1>
</div>

<div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>No HP</th>
                <th>Password</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
            <tr>
                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge bg-{{ $user->role === 'siswa' ? 'primary' : 'warning' }}">{{ ucfirst($user->role) }}</span></td>
                <td>{{ $user->nohp }}</td>
                <td><code class="bg-light p-1">{{ Str::limit($user->password, 20) }}</code></td>
                <td>
                    <a href="{{ route('admin.superusers.show', $user) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $users->links() }}
@endsection

