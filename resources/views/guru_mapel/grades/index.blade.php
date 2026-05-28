@extends('layout.app')

@section('title', 'Daftar Nilai - ' . $class->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Daftar Nilai {{ $class->name }}</h1>
    <div>
        <a href="{{ route('guru_mapel.class.show', $class) }}" class="btn btn-secondary">Kembali</a>
        @if($isWali)
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addGradeModal">
                ➕ Tambah Nilai
            </button>
        @endif
    </div>
</div>

<!-- Modal Tambah Nilai -->
@if($isWali)
<div class="modal fade" id="addGradeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Nilai Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addGradeForm" method="GET">
                    <div class="mb-3">
                        <label class="form-label">Pilih Siswa</label>
                        <input type="text" id="studentSearch" class="form-control" placeholder="Cari nama siswa..." autocomplete="off">
                        <div id="studentList" class="list-group mt-2" style="max-height: 300px; overflow-y: auto;"></div>
                        <input type="hidden" name="student_id" id="studentSelect">
                        <small id="selectedStudent" class="text-muted"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="goToInputForm()">Lanjut</button>
            </div>
        </div>
    </div>
</div>

<script>
const students = @json($students);

document.getElementById('studentSearch').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    const listDiv = document.getElementById('studentList');
    listDiv.innerHTML = '';
    
    if (query.length < 1) {
        return;
    }
    
    const filtered = students.filter(s => s.name.toLowerCase().includes(query));
    
    filtered.forEach(student => {
        const item = document.createElement('button');
        item.type = 'button';
        item.className = 'list-group-item list-group-item-action';
        item.textContent = student.name;
        item.onclick = (e) => {
            e.preventDefault();
            document.getElementById('studentSelect').value = student.id;
            document.getElementById('selectedStudent').textContent = 'Dipilih: ' + student.name;
            document.getElementById('studentSearch').value = student.name;
            listDiv.innerHTML = '';
        };
        listDiv.appendChild(item);
    });
});

function goToInputForm() {
    const studentId = document.getElementById('studentSelect').value;
    if (!studentId) {
        alert('Pilih siswa terlebih dahulu');
        return;
    }
    const url = "{{ route('guru_mapel.grade.form', [$class, ':student']) }}".replace(':student', studentId);
    window.location.href = url;
}
</script>
@endif

@if ($grades->isEmpty())
<div class="alert alert-info">
    Belum ada nilai yang diinput untuk kelas ini.
</div>
@else
<!-- Kelompokkan nilai berdasarkan mapel -->
@foreach($subjects as $subject)
    @php
        $subjectGrades = $grades->where('subject_id', $subject->id);
    @endphp
    
    @if($subjectGrades->isNotEmpty())
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $subject->name }}</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Siswa</th>
                        <th style="width: 100px;">Nilai</th>
                        <th>Keterangan</th>
                        <th>Guru Input</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subjectGrades as $grade)
                    <tr>
                        <td>{{ $grade->student->name }}</td>
                        <td>
                            <form action="{{ route('guru_mapel.grades.update', [$class, $grade]) }}" method="POST" class="d-inline" onsubmit="return confirm('Ubah nilai?')">
                                @csrf
                                @method('PUT')
                                <input type="number" name="nilai" value="{{ $grade->nilai }}" min="0" max="100" class="form-control form-control-sm" style="width: 80px;" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('guru_mapel.grades.update', [$class, $grade]) }}" method="POST" class="d-inline" onsubmit="return true;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="nilai" value="{{ $grade->nilai }}">
                                <select name="keterangan" class="form-select form-select-sm" style="width: 120px; display: inline-block;" onchange="this.form.submit()">
                                    <option value="">-</option>
                                    <option value="Sangat Baik" @selected($grade->keterangan === 'Sangat Baik')>Sangat Baik</option>
                                    <option value="Baik" @selected($grade->keterangan === 'Baik')>Baik</option>
                                    <option value="Cukup" @selected($grade->keterangan === 'Cukup')>Cukup</option>
                                    <option value="Kurang" @selected($grade->keterangan === 'Kurang')>Kurang</option>
                                </select>
                            </form>
                        </td>
                        <td>{{ $grade->subject->admin->name }}</td>
                        <td>{{ $grade->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endforeach

<!-- Jika ada nilai dari mapel lain yang tidak terlist di atas -->
@php
    $listedSubjectIds = $subjects->pluck('id')->toArray();
    $otherGrades = $grades->whereNotIn('subject_id', $listedSubjectIds);
@endphp

@if($otherGrades->isNotEmpty())
    @php
        $otherGrouped = $otherGrades->groupBy('subject_id');
    @endphp
    @foreach($otherGrouped as $subjectId => $grades_group)
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">{{ $grades_group->first()->subject->name }}</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Siswa</th>
                            <th style="width: 100px;">Nilai</th>
                            <th>Keterangan</th>
                            <th>Guru Input</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grades_group as $grade)
                        <tr>
                            <td>{{ $grade->student->name }}</td>
                            <td>
                                <form action="{{ route('guru_mapel.grades.update', [$class, $grade]) }}" method="POST" class="d-inline" onsubmit="return confirm('Ubah nilai?')">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="nilai" value="{{ $grade->nilai }}" min="0" max="100" class="form-control form-control-sm" style="width: 80px;" onchange="this.form.submit()">
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('guru_mapel.grades.update', [$class, $grade]) }}" method="POST" class="d-inline" onsubmit="return true;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="nilai" value="{{ $grade->nilai }}">
                                    <select name="keterangan" class="form-select form-select-sm" style="width: 120px; display: inline-block;" onchange="this.form.submit()">
                                        <option value="">-</option>
                                        <option value="Sangat Baik" @selected($grade->keterangan === 'Sangat Baik')>Sangat Baik</option>
                                        <option value="Baik" @selected($grade->keterangan === 'Baik')>Baik</option>
                                        <option value="Cukup" @selected($grade->keterangan === 'Cukup')>Cukup</option>
                                        <option value="Kurang" @selected($grade->keterangan === 'Kurang')>Kurang</option>
                                    </select>
                                </form>
                            </td>
                            <td>{{ $grade->subject->admin->name }}</td>
                            <td>{{ $grade->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endif

@endif
@endsection
