@extends('layout.app')

@section('title', 'Detail Siswa - ' . $siswa->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.siswas.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-person-fill"></i> Informasi Siswa</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td style="width: 200px;"><strong>Nama</strong></td>
                        <td>{{ $siswa->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $siswa->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>No HP</strong></td>
                        <td>{{ $siswa->nohp }}</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat</strong></td>
                        <td>{{ $siswa->alamat }}</td>
                    </tr>
                    <tr>
                        <td><strong>Terdaftar Sejak</strong></td>
                        <td>{{ $siswa->created_at->format('d/m/Y H:i') }}</td>
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
                    <p class="text-muted">Belum terdaftar di kelas apapun</p>
                @else
                    <ul class="list-group">
                        @foreach($classes as $class)
                            <li class="list-group-item">
                                <strong>{{ $class->name }}</strong>
                                <br>
                                <small class="text-muted">Guru: {{ $class->admin?->name ?? '-' }}</small>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Nilai ({{ $grades->count() }})</h5>
            </div>
            <div class="card-body">
                @if($grades->isEmpty())
                    <p class="text-muted">Belum ada nilai</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>Nilai</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grades as $grade)
                                    <tr>
                                        <td>{{ $grade->subject->name }}</td>
                                        <td>{{ $grade->teacher->name }}</td>
                                        <td><span class="badge bg-info">{{ $grade->nilai }}</span></td>
                                        <td>{{ $grade->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
                <a href="{{ route('admin.siswas.edit', $siswa) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit Siswa
                </a>
                <form action="{{ route('admin.siswas.destroy', $siswa) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Hapus Siswa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
