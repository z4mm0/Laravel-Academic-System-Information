    @extends('layout.app')

@section('title', 'Detail Kelas - ' . $class->name)

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $class->name }}</h1>
        <div>
            <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5>Informasi Kelas</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama Kelas:</strong></td>
                        <td>{{ $class->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Wali Kelas:</strong></td>
                        <td>
                            @if($class->admin)
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    {{ $class->admin->name }}
                                </span>
                                <br><small class="text-muted">{{ $class->admin->email }}</small>
                            @else
                                <span class="badge bg-secondary">Belum ditugaskan</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Total Siswa:</strong></td>
                        <td><span class="badge bg-info fs-6">{{ $class->students->count() }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Dibuat:</strong></td>
                        <td>{{ $class->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($class->students->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h5>Daftar Siswa</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Email</th>
                                <th>No HP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->students as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->nohp }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="mb-3" onsubmit="return confirm('Yakin hapus kelas {{ $class->name }} dan relasi siswa?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Hapus Kelas
                    </button>
                </form>
                <a href="{{ route('admin.classes.edit', $class) }}" class="btn btn-warning w-100 mb-2">
                    <i class="bi bi-pencil"></i> Edit Kelas
                </a>
                <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary w-100">
                    <i class="bi bi-list"></i> Lihat Semua Kelas
                </a>
            </div>

                <div class="card-header">
                    <h5>Mata Pelajaran Kelas</h5>
                    <a href="{{ route('admin.classes.mapel.form', $class) }}" class="btn btn-sm btn-primary float-end">
                        <i class="bi bi-plus"></i> Tambah Mapel
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($class->subjects) && $class->subjects->isNotEmpty())
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mapel</th>
                                    <th>Guru</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($class->subjects as $subject)
                                <tr>
                                    <td>{{ $subject->name }}</td>
                                    <td>{{ $subject->admin->name ?? 'Unassigned' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Belum ada mata pelajaran untuk kelas ini.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection


