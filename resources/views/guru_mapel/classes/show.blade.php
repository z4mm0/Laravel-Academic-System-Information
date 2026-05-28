@extends('layout.app')

@section('title', 'Detail Kelas - ' . $class->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>{{ $class->name }}</h1>
    <a href="{{ route('guru_mapel.classes.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">
            👥 Daftar Siswa ({{ $students->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="grades-tab" data-bs-toggle="tab" data-bs-target="#grades" type="button" role="tab">
            📝 Daftar Nilai
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- Tab Siswa -->
    <div class="tab-pane fade show active" id="students" role="tabpanel">
        @if ($students->isEmpty())
        <div class="alert alert-info">
            Tidak ada siswa di kelas ini.
        </div>
        @else
        <div class="alert alert-info mb-3">
            💡 Untuk menginput atau mengubah nilai, gunakan menu "Daftar Nilai" di tab sebelah.
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Email</th>
                    @if(isset($isWali) && $isWali)
                    <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $key => $student)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    @if(isset($isWali) && $isWali)
                    <td>
                        <a href="{{ route('guru_mapel.grade.form', [$class, $student]) }}" class="btn btn-sm btn-primary">
                            ➕ Tambah Nilai
                        </a>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <!-- Tab Nilai -->
    <div class="tab-pane fade" id="grades" role="tabpanel">
        @if(isset($isWali) && $isWali)
            <span class="badge bg-success me-2">👑 Wali Kelas - Bisa Edit Semua Mapel</span>
        @endif
        <a href="{{ route('guru_mapel.grades.index', $class) }}" class="btn btn-info mb-3">
            📊 Lihat Semua Nilai
        </a>
    </div>
</div>
@endsection
