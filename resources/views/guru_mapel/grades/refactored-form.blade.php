@extends('layout.app')

@section('title', 'Tambah Nilai')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4"> Tambah Nilai Siswa</h1>

    <!-- Tambah Nilai: tekan Tambah Nilai -> pilih kelas -> pilih mapel -> tabel siswa -->

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Tambah Nilai (Tabel Siswa)</h5>
            </div>
            <div class="card-body">
                <form id="massGradeForm" action="{{ route('guru_mapel.grade.refactored.mass.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pilih Kelas <span class="text-danger">*</span></label>
                            <select name="class_id" id="classSelect" class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Pilih Mata Pelajaran <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subjectSelect" class="form-select" required>
                                <option value="">-- Pilih Mapel --</option>
                            </select>
                            <div id="subjectsDebug" class="text-danger small mt-1"></div>
                        </div>

                    </div>

                    <hr>

                    <div id="gradesTableWrap" style="display:none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Daftar Siswa & Input Nilai</h6>
                            <button type="submit" class="btn btn-primary" id="saveAllBtn">💾 Simpan Nilai</button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="min-width:220px;">Nama Siswa</th>
                                        <th style="width:140px;">Nilai (0-100)</th>
                                        <th style="width:180px;">Keterangan</th>
                                        <th style="width:190px;">Tanggal Input</th>
                                    </tr>
                                </thead>
                                <tbody id="gradesTbody"></tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    console.log('=== Script started ===');
    
    const classSelect = document.getElementById('classSelect');
    const subjectSelect = document.getElementById('subjectSelect');
    const gradesTableWrap = document.getElementById('gradesTableWrap');
    const gradesTbody = document.getElementById('gradesTbody');
    const subjectsDebug = document.getElementById('subjectsDebug');

    console.log('DOM Elements:', {
        classSelect: classSelect ? 'FOUND' : 'NOT FOUND',
        subjectSelect: subjectSelect ? 'FOUND' : 'NOT FOUND',
        gradesTableWrap: gradesTableWrap ? 'FOUND' : 'NOT FOUND',
        gradesTbody: gradesTbody ? 'FOUND' : 'NOT FOUND',
        subjectsDebug: subjectsDebug ? 'FOUND' : 'NOT FOUND',
    });

    // Safety check
    if (!classSelect || !subjectSelect) {
        console.error('FATAL: classSelect atau subjectSelect tidak ditemukan!');
    }


    function resetTable() {
        gradesTbody.innerHTML = '';
        gradesTableWrap.style.display = 'none';
        subjectSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
        // Disable dropdown sampai kelas dipilih
        subjectSelect.disabled = true;
    }

    function getSafeText(v) {
        return (v === null || v === undefined) ? '' : String(v);
    }

    async function loadSubjects(classId) {
        // Selalu enable dropdown saat mulai load
        subjectSelect.disabled = false;
        subjectSelect.innerHTML = '<option value="">Loading mapel...</option>';
        console.log('loadSubjects called with classId:', classId);

        try {
            const url = `/guru_mapel/api/classes/${classId}/subjects`;
            console.log('Fetching from:', url);
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json' },
                credentials: 'include'  // PENTING: kirim session cookie
            });

            console.log('Response status:', res.status, res.statusText);
            console.log('Response headers:', res.headers);

            let payload = null;
            try {
                payload = await res.json();
                console.log('Parsed JSON payload:', payload);
            } catch (jsonError) {
                console.error('JSON parse error for subjects:', jsonError);
                console.log('Response text:', res.text);
            }

            // Debug: tampilkan status & payload error jika ada
            if (!res.ok) {
                const err = payload?.error ?? payload?.message ?? getSafeText(payload) ?? '';
                const errorMsg = err ? `(${res.status}) ${err}` : `Gagal memuat mapel (${res.status})`;
                subjectSelect.innerHTML = `<option value="">${errorMsg}</option>`;
                // Tetap enable agar user bisa coba ulang atau memilih kelas lain
                subjectSelect.disabled = false;
                console.error('loadSubjects failed:', { status: res.status, payload });
                return;
            }

            // sukses
            console.log('Response OK, processing data...');
            subjectSelect.innerHTML = '<option value="">-- Pilih Mapel --</option>';
            subjectSelect.disabled = false;
            if (subjectsDebug) subjectsDebug.textContent = '';

            if (!Array.isArray(payload) || payload.length === 0) {
                // Tampilkan juga payload sebagai fallback agar jelas
                const payloadStr = getSafeText(payload);
                const msg = `Mapel kosong${payloadStr ? ' (payload: ' + payloadStr + ')' : ''}`;
                subjectSelect.insertAdjacentHTML(
                    'beforeend',
                    `<option value="" disabled>${msg}</option>`
                );
                // Tetap enable dropdown meski kosong
                subjectSelect.disabled = false;
                if (subjectsDebug) subjectsDebug.textContent = msg;
                console.warn('loadSubjects empty payload:', payload);
                return;
            }

            console.log('Processing', payload.length, 'subjects');
            payload.forEach((s, idx) => {
                console.log(`Subject ${idx}:`, s);
                subjectSelect.insertAdjacentHTML('beforeend', `<option value="${s.id}">${s.name}</option>`);
            });
            console.log('loadSubjects complete. Dropdown now has', subjectSelect.options.length, 'options');

        } catch (error) {
            console.error('Fetch error for subjects:', error);
            subjectSelect.innerHTML = '<option value="">Gagal memuat mapel (koneksi atau server)</option>';
            // Tetap enable meski ada error
            subjectSelect.disabled = false;
        }
    }


    async function loadStudentsTable(classId) {
        gradesTableWrap.style.display = 'block';
        gradesTableWrap.style.display = 'block';
        gradesTbody.innerHTML = '<tr><td colspan="4" class="text-center">Loading siswa...</td></tr>';
        if (subjectsDebug) subjectsDebug.textContent = `classId=${classId}`;

        try {
            const res = await fetch(`/guru_mapel/api/classes/${classId}/students`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'include'
            });

            let payload = null;
            try {
                payload = await res.json();
            } catch (e) {}

            if (!res.ok) {
                gradesTbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">Gagal memuat siswa (${res.status})</td></tr>`;
                return;
            }

            if (!Array.isArray(payload) || payload.length === 0) {
                gradesTbody.innerHTML = `<tr><td colspan="4" class="text-center">Tidak ada siswa untuk kelas ini</td></tr>`;
                return;
            }

            gradesTbody.innerHTML = '';
            payload.forEach((row, idx) => {
                gradesTbody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td>
                            <div class="fw-semibold">${row.name}</div>
                            <input type="hidden" name="grades[${idx}][student_id]" value="${row.id}">
                        </td>
                        <td>
                            <input type="number" name="grades[${idx}][nilai]" class="form-control" min="0" max="100" step="1" value="" placeholder="--">
                        </td>
                        <td>
                            <select name="grades[${idx}][keterangan]" class="form-select">
                                <option value="">-- Tidak ada --</option>
                                <option value="Sangat Baik">⭐⭐⭐⭐⭐ Sangat Baik</option>
                                <option value="Baik">⭐⭐⭐⭐ Baik</option>
                                <option value="Cukup">⭐⭐⭐ Cukup</option>
                                <option value="Kurang">⭐⭐ Kurang</option>
                            </select>
                        </td>
                        <td>-</td>
                    </tr>
                `);
            });
        } catch (error) {
            gradesTbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">Gagal memuat siswa (koneksi/ server)</td></tr>`;
        }
    }

    async function loadGrades(classId, subjectId) {
        gradesTbody.innerHTML = '<tr><td colspan="4" class="text-center">Loading nilai...</td></tr>';

        try {
            const res = await fetch(`/guru_mapel/api/classes/${classId}/subjects/${subjectId}/grades`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'include'
            });

            let data = null;

            try {
                data = await res.json();
            } catch (jsonError) {
                console.error('JSON parse error for grades:', jsonError);
            }

            if (!res.ok) {
                const errorMsg = data?.error || `Gagal memuat data (${res.status})`;
                gradesTbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">${errorMsg}</td></tr>`;
                gradesTableWrap.style.display = 'block';
                return;
            }

            gradesTbody.innerHTML = '';
data.rows.forEach((row, idx) => {
            gradesTbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>
                        <div class="fw-semibold">${row.student_name}</div>
                        <input type="hidden" name="grades[${idx}][student_id]" value="${row.student_id}">
                    </td>
                    <td>
                        <input type="number" name="grades[${idx}][nilai]" class="form-control" min="0" max="100" step="1" value="${row.nilai ?? ''}" placeholder="--">
                    </td>
                    <td>
                        <select name="grades[${idx}][keterangan]" class="form-select">
                                <option value="" ${row.keterangan == null || row.keterangan === '' ? 'selected' : ''}>-- Tidak ada --</option>
                                <option value="Sangat Baik" ${row.keterangan === 'Sangat Baik' ? 'selected' : ''}>⭐⭐⭐⭐⭐ Sangat Baik</option>
                                <option value="Baik" ${row.keterangan === 'Baik' ? 'selected' : ''}>⭐⭐⭐⭐ Baik</option>
                                <option value="Cukup" ${row.keterangan === 'Cukup' ? 'selected' : ''}>⭐⭐⭐ Cukup</option>
                                <option value="Kurang" ${row.keterangan === 'Kurang' ? 'selected' : ''}>⭐⭐ Kurang</option>
                            </select>
                        </td>
                        <td>${row.created_at ? row.created_at : '-'}</td>
                    </tr>
                `);
            });

        gradesTableWrap.style.display = 'block';
        } catch (error) {
            console.error('Fetch error for grades:', error);
            gradesTbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">Gagal memuat data (koneksi atau server)</td></tr>`;
            gradesTableWrap.style.display = 'block';
        }
    }

    classSelect.addEventListener('change', async function() {
        console.log('!!! classSelect change event fired !!!');
        const classId = this.value;
        console.log('Selected classId:', classId);
        if (subjectsDebug) subjectsDebug.textContent = `classId=${classId || '-'}`;
        resetTable();
        if (!classId) {
            console.log('classId kosong, tidak load subjects');
            return;
        }
        console.log('Calling loadSubjects with:', classId);
        await loadSubjects(classId);
    });

    // Fallback: beberapa browser/DOM kadang tidak memicu `change` saat value ter-set ulang
    classSelect.addEventListener('input', async function() {
        console.log('!!! classSelect input event fired !!!');
        const classId = this.value;
        console.log('Selected classId (input):', classId);
        if (subjectsDebug) subjectsDebug.textContent = `classId(input)=${classId || '-'}`;
        resetTable();
        if (!classId) {
            console.log('classId kosong, tidak load subjects');
            return;
        }
        console.log('Calling loadSubjects with:', classId);
        await loadSubjects(classId);
    });

    subjectSelect.addEventListener('change', async function() {
        console.log('!!! subjectSelect change event fired !!!');
        const classId = classSelect.value;
        const subjectId = this.value;
        console.log('Selected:', { classId, subjectId });
        if (!classId || !subjectId) {
            console.log('classId atau subjectId kosong, tidak load grades');
            return;
        }
        console.log('Calling loadGrades with:', { classId, subjectId });
        await loadGrades(classId, subjectId);
    });

    document.getElementById('massGradeForm').addEventListener('submit', function() {
        const btn = document.getElementById('saveAllBtn');
        btn.disabled = true;
        btn.textContent = '⏳ Menyimpan...';
    });

    console.log('=== Event listeners attached ===');
    
    // Set initial state: disable mapel dropdown sampai kelas dipilih
    subjectSelect.disabled = true;
    console.log('Initial state: subjectSelect.disabled =', subjectSelect.disabled);
    
    resetTable();
</script>


<style>
    .cursor-pointer {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cursor-pointer:hover {
        background-color: #0d5fbf !important;
    }

    .card-header.bg-primary {
        padding: 1rem;
    }

    .form-label.fw-bold {
        color: #333;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
</style>
@endsection
