@extends('layout.app')

@section('title', 'Input Nilai - ' . $student->name)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Input Nilai untuk {{ $student->name }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('guru_mapel.grade.store', [$class, $student]) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select name="subject_id" class="form-control @error('subject_id') is-invalid @enderror">
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
                                {{ $subject->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nilai (0-100) <span class="text-danger">*</span></label>
                        <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror" 
                               min="0" max="100" value="{{ old('nilai') }}" placeholder="Masukkan nilai">
                        @error('nilai')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <select name="keterangan" class="form-control @error('keterangan') is-invalid @enderror">
                            <option value="">-- Tidak ada keterangan --</option>
                            <option value="Sangat Baik" @selected(old('keterangan') == 'Sangat Baik')>Sangat Baik</option>
                            <option value="Baik" @selected(old('keterangan') == 'Baik')>Baik</option>
                            <option value="Cukup" @selected(old('keterangan') == 'Cukup')>Cukup</option>
                            <option value="Kurang" @selected(old('keterangan') == 'Kurang')>Kurang</option>
                        </select>
                        @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">💾 Simpan Nilai</button>
                        <a href="javascript:history.back()" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Nilai yang sudah ada -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Nilai yang Ada</h5>
            </div>
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                @if ($existingGrades->isEmpty())
                <p class="text-muted small">Belum ada nilai untuk siswa ini.</p>
                @else
                @foreach ($existingGrades as $grade)
                <div class="mb-3 pb-3 border-bottom">
                    <small class="text-muted">{{ $grade->subject->name }}</small><br>
                    <strong class="text-primary">{{ $grade->nilai }}</strong>
                    @if ($grade->keterangan)
                    <br><small class="text-success">{{ $grade->keterangan }}</small>
                    @endif
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
