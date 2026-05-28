@extends('layout.app')

@section('title', 'Daftar Kelas - Wali Kelas')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">Daftar Kelas Saya</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                @if ($classes->isEmpty())
                    <p class="text-muted">Anda belum ditugaskan sebagai wali kelas.</p>
                @else
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Jumlah Siswa</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classes as $class)
                            <tr>
                                <td>{{ $class->name }}</td>
                                <td>{{ $class->students->count() }}</td>
                                <td>
                                    <a href="{{ route('wali_kelas.class.grades', $class) }}" class="btn btn-sm btn-primary">
                                        Lihat Nilai
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
