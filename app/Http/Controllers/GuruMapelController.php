<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class GuruMapelController extends Controller
{
    // Dashboard Guru
    public function dashboard()
    {
        $teacher = auth()->user();
        $classes = $teacher->classes;
        $subjects = $teacher->subjects;
        $totalStudents = $teacher->classes->sum(fn($class) => $class->students->count());
        
        return view('guru_mapel.dashboard', compact('classes', 'subjects', 'totalStudents'));
    }

    // Lihat daftar kelas guru
    public function listClasses()
    {
        $classes = ClassModel::whereHas('subjects', function ($query) {
            $query->where('admin_id', auth()->id());
        })->get();

        return view('guru_mapel.classes.index', compact('classes'));
    }

    // Tampil detail kelas dan siswa
    public function showClass(ClassModel $class)
    {
        $isWali = $class->admin_id === auth()->id();
        
        if (!$isWali && !$class->subjects()->where('admin_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kelas ini');
        }

        $students = $class->students;
        $subjects = $isWali ? $class->subjects : $class->subjects()->where('admin_id', auth()->id())->get();

        return view('guru_mapel.classes.show', compact('class', 'students', 'subjects', 'isWali'));
    }

    // Tampil form input nilai
    public function inputGradeForm(ClassModel $class, User $student)
    {
        $isWali = $class->admin_id === auth()->id();
        
        // Wali kelas bisa add nilai di kelasnya, guru mapel bisa add nilai di mapelnya
        if (!$isWali && !$class->subjects()->where('admin_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        if (!$class->students()->where('student_id', $student->id)->exists()) {
            return redirect()->back()->with('error', 'Siswa tidak ada di kelas ini');
        }

        if ($isWali) {
            // Wali kelas bisa add nilai dari semua mapel
            $subjects = $class->subjects()->get();
        } else {
            // Guru mapel hanya mapelnya
            $subjects = $class->subjects()->where('admin_id', auth()->id())->get();
        }
        
        $existingGrades = Grade::where('student_id', $student->id)
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get();

        return view('guru_mapel.grades.form', compact('class', 'student', 'subjects', 'existingGrades', 'isWali'));
    }

    // Simpan atau update nilai
    public function storeGrade(Request $request, ClassModel $class, User $student)
    {
        $isWali = $class->admin_id === auth()->id();
        
        if (!$isWali && !$class->subjects()->where('admin_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        if (!$class->students()->where('student_id', $student->id)->exists()) {
            return redirect()->back()->with('error', 'Siswa tidak ada di kelas ini');
        }

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'nilai' => 'required|integer|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $subject = Subject::where('id', $request->subject_id)
            ->whereHas('classes', function ($query) use ($class) {
                $query->where('classes.id', $class->id);
            })
            ->first();

        if (!$subject) {
            return redirect()->back()->with('error', 'Mata pelajaran tidak tersedia di kelas ini atau Anda tidak memiliki akses.');
        }

        // Jika bukan wali, hanya bisa add nilai mapelnya sendiri
        if (!$isWali && $subject->admin_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menambah nilai untuk mata pelajaran ini.');
        }

        $validated['student_id'] = $student->id;
        $validated['admin_id'] = auth()->id();

        Grade::updateOrCreate(
            [
                'student_id' => $student->id,
                'subject_id' => $request->subject_id,
                'admin_id' => auth()->id(),
            ],
            $validated
        );

        return redirect()->route('guru_mapel.class.show', $class)
            ->with('success', 'Nilai berhasil disimpan');
    }

    // Lihat semua nilai yang sudah diinput
    // Lihat semua nilai
    public function allGrades()
    {
        $grades = Grade::where('admin_id', auth()->id())
            ->with('student', 'subject')
            ->paginate(50);

        return view('guru_mapel.grades.all', compact('grades'));
    }

    public function viewGrades(ClassModel $class)
    {
        $isWali = $class->admin_id === auth()->id();
        
        if (!$isWali && !$class->subjects()->where('admin_id', auth()->id())->exists()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses');
        }

        $students = $class->students;
        
        if ($isWali) {
            // Wali: ALL subjects in class
            $subjects = \App\Models\Subject::whereHas('classes', fn($q) => $q->where('classes.id', $class->id))->get();
            $grades = Grade::whereIn('student_id', $students->pluck('id'))
                ->whereIn('subject_id', $subjects->pluck('id'))
                ->with('student', 'subject', 'subject.admin')
                ->paginate(50);
        } else {
            // Mapel only: own subjects
            $subjects = $class->subjects()->where('admin_id', auth()->id())->get();
            $grades = Grade::where('admin_id', auth()->id())
                ->whereIn('student_id', $students->pluck('id'))
                ->whereIn('subject_id', $subjects->pluck('id'))
                ->with('student', 'subject')
                ->paginate(50);
        }

        return view('guru_mapel.grades.index', compact('class', 'students', 'subjects', 'grades', 'isWali'));
    }

    public function editGrade(ClassModel $class, Grade $grade)
    {
        if (!$class->students()->where('student_id', $grade->student_id)->exists()) {
            return redirect()->back()->with('error', 'Grade not in class');
        }

        $subject = $grade->subject;
        if ($class->id !== $subject->classes()->first()->id) {
            return redirect()->back()->with('error', 'Subject not in class');
        }

        $isWali = $class->admin_id === auth()->id();
        
        if (!$isWali && $grade->admin_id !== auth()->id()) {
            return redirect()->back()->with('error', 'No permission');
        }

        $student = $grade->student;

        return view('guru_mapel.grades.edit', compact('class', 'grade', 'student', 'subject'));
    }

    public function updateGrade(Request $request, ClassModel $class, Grade $grade)
    {
        $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $isWali = $class->admin_id === auth()->id();
        
        // Hanya wali kelas atau guru yang input nilainya yang bisa edit
        if (!$isWali && $grade->admin_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah nilai ini');
        }

        $grade->update($request->only('nilai', 'keterangan'));

        return redirect()->route('guru_mapel.grades.index', $class)
            ->with('success', 'Nilai berhasil diupdate!');
    }

    // Lihat daftar mata pelajaran
    public function listSubjects()
    {
        $subjects = auth()->user()->subjects;
        return view('guru_mapel.subjects.index', compact('subjects'));
    }

    // Form buat mata pelajaran
    public function createSubjectForm()
    {
        return view('guru_mapel.subjects.form');
    }

    // Simpan mata pelajaran
    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Subject::create([
            'name' => $validated['name'],
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('guru_mapel.subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan');
    }

// Input nilai di kelas lain (untuk guru mapel)
    public function inputGradeOtherClassForm()
    {
        $teacher = auth()->user();
        $subjects = $teacher->subjects; // Mapel guru ini
        
        if ($subjects->isEmpty()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki mata pelajaran');
        }

        // Ambil semua kelas yang punya mapel guru ini
        $classes = ClassModel::whereHas('subjects', function ($query) use ($teacher) {
            $query->where('admin_id', $teacher->id);
        })->with(['students' => function ($q) {
            $q->orderBy('name');
        }])->get();

        return view('guru_mapel.grades.input-other-class', compact('subjects', 'classes'));
    }

// Simpan nilai di kelas lain
    public function storeGradeOtherClass(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'nilai' => 'required|integer|min:0|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $student = User::find($request->student_id);
        $subject = Subject::find($request->subject_id);
        $teacher = auth()->user();

        // Validasi: subject harus milik guru
        if ($subject->admin_id !== $teacher->id) {
            return redirect()->back()->with('error', 'Mata pelajaran tidak sesuai dengan tugas Anda');
        }

        $validated['admin_id'] = $teacher->id;

        Grade::updateOrCreate(
            [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'admin_id' => $teacher->id,
            ],
            $validated
        );

        return redirect()->route('guru_mapel.grade.other-class.form')
            ->with('success', 'Nilai siswa berhasil disimpan!');

        // OLD VALIDATION - currently disabled for simpler flow
        $class = ClassModel::find(1);
        $student = User::find($request->student_id);
        $subject = Subject::find($request->subject_id);
        $teacher = auth()->user();

        // Validasi: subject harus milik guru
        if ($subject->admin_id !== $teacher->id) {
            return redirect()->back()->with('error', 'Mata pelajaran tidak sesuai dengan tugas Anda');
        }

        // Validasi: subject harus ada di class
        if (!$class->subjects()->where('subject_id', $subject->id)->exists()) {
            return redirect()->back()->with('error', 'Mata pelajaran tidak ada di kelas ini');
        }

        // Validasi: student harus di class
        if (!$class->students()->where('student_id', $student->id)->exists()) {
            return redirect()->back()->with('error', 'Siswa tidak ada di kelas ini');
        }

        $validated['student_id'] = $student->id;
        $validated['admin_id'] = $teacher->id;

        Grade::updateOrCreate(
            [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'admin_id' => $teacher->id,
            ],
            $validated
        );

        return redirect()->route('guru_mapel.grade.other-class.form')
            ->with('success', 'Nilai siswa berhasil disimpan!');
    }

    // ===== REFACTORED GRADE INPUT (Grouped by Class) =====

    /**
     * Show the refactored grade form with class selection as the first trigger
     */
    public function showGradeRefactoredForm()
    {
        $teacher = auth()->user();
        
        // Get classes where teacher teaches (either as wali kelas or guru mapel)
        $classes = ClassModel::where(function ($query) use ($teacher) {
            $query->where('admin_id', $teacher->id) // Wali kelas
                ->orWhereHas('subjects', fn($q) => $q->where('admin_id', $teacher->id)); // Guru mapel
        })->get();

        if ($classes->isEmpty()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kelas manapun');
        }

        // Get teacher's subjects
        $subjects = $teacher->subjects;

        return view('guru_mapel.grades.refactored-form', compact('classes', 'subjects'));
    }

    /**
     * AJAX: Get students for a specific class
     */
    public function getStudentsByClass(ClassModel $class)

    {
        $teacher = auth()->user();
        $isWali = $class->admin_id === $teacher->id;
        
        // Authorization: teacher must be wali kelas or teach in this class
        if (!$isWali && !$class->subjects()->where('admin_id', $teacher->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $students = $class->students()->orderBy('name')->get(['id', 'name', 'email']);
        
        return response()->json($students);
    }

    /**
     * AJAX: Get subjects for a specific class that the teacher teaches
     */
    public function getSubjectsByClass(ClassModel $class)
    {
        $teacher = auth()->user();
        $isWali = $class->admin_id === $teacher->id;
        
        // Authorization
        if (!$isWali && !$class->subjects()->where('subjects.admin_id', $teacher->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($isWali) {
            // Wali kelas dapat semua mapel di kelas ini
            $subjects = $class->subjects()->orderBy('name')->get(['subjects.id', 'subjects.name']);
        } else {
            // Guru mapel hanya mapelnya saja
            $subjects = $class->subjects()
                ->where('subjects.admin_id', $teacher->id)
                ->orderBy('name')
                ->get(['subjects.id', 'subjects.name']);
        }
        
        return response()->json($subjects);
    }

    /**
     * Store grade from refactored form
     */
    public function storeGradeRefactored(Request $request)
    {
        $teacher = auth()->user();
        $class = ClassModel::find($request->class_id);

        // Authorization
        if (!$class) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan');
        }

        $isWali = $class->admin_id === $teacher->id;
        if (!$isWali && !$class->subjects()->where('admin_id', $teacher->id)->exists()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kelas ini');
        }

        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'nilai' => 'required|integer|min:0|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Verify student is in class
        if (!$class->students()->where('student_id', $request->student_id)->exists()) {
            return redirect()->back()->with('error', 'Siswa tidak ada di kelas ini');
        }

        // Verify subject is in class
        $subject = Subject::where('id', $request->subject_id)
            ->whereHas('classes', fn($q) => $q->where('classes.id', $class->id))
            ->first();

        if (!$subject) {
            return redirect()->back()->with('error', 'Mata pelajaran tidak tersedia di kelas ini');
        }

        // If not wali, verify teacher teaches this subject
        if (!$isWali && $subject->admin_id !== $teacher->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menambah nilai untuk mata pelajaran ini');
        }

        $validated['student_id'] = $request->student_id;
        $validated['admin_id'] = $teacher->id;

        Grade::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'subject_id' => $request->subject_id,
                'admin_id' => $teacher->id,
            ],
            $validated
        );

        return redirect()->route('guru_mapel.grade.refactored.form')
            ->with('success', 'Nilai berhasil disimpan!');
    }

    /**
     * AJAX: Get students + existing grade data for a specific class & subject
     */
    public function getGradesForClassSubject(ClassModel $class, Subject $subject)
    {
        try {
            $teacher = auth()->user();
            $isWali = $class->admin_id === $teacher->id;

            // Authorization: teacher must be wali kelas or teach this subject in the class
            if (!$isWali && $subject->admin_id !== $teacher->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Subject must belong to this class
            if (!$class->subjects()->where('subjects.id', $subject->id)->exists()) {
                return response()->json(['error' => 'Subject not in class'], 403);
            }

            $students = $class->students()->orderBy('name')->get(['users.id', 'users.name']);

            $grades = Grade::where('admin_id', $teacher->id)
                ->where('subject_id', $subject->id)
                ->whereIn('student_id', $students->pluck('id'))
                ->get(['student_id', 'nilai', 'keterangan', 'created_at']);

            $gradesByStudent = $grades->keyBy('student_id');

            $rows = $students->map(function ($student) use ($gradesByStudent) {
                $g = $gradesByStudent->get($student->id);
                return [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'nilai' => $g?->nilai,
                    'keterangan' => $g?->keterangan,
                    'created_at' => $g?->created_at?->toDateTimeString(),
                ];
            })->values();

            return response()->json([
                'rows' => $rows,
            ]);
        } catch (\Exception $e) {
            \Log::error('getGradesForClassSubject error:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store grades in bulk for a selected class & subject
     */
    public function storeGradesMass(Request $request)
    {
        $teacher = auth()->user();
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:users,id',
            'grades.*.nilai' => 'nullable|integer|min:0|max:100',
            'grades.*.keterangan' => 'nullable|string|max:255',
        ]);

        $class = ClassModel::findOrFail($validated['class_id']);
        $subject = Subject::findOrFail($validated['subject_id']);

        $isWali = $class->admin_id === $teacher->id;

        if (!$isWali && $subject->admin_id !== $teacher->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke mata pelajaran ini');
        }

        // Subject must be attached to this class via class_subject table.
        // Catatan: pakai filter 'subjects.id' untuk hindari ambiguity kolom 'id'.
        if (!$class->subjects()->where('subjects.id', $subject->id)->exists()) {
            return redirect()->back()->with('error', 'Mata pelajaran tidak tersedia di kelas ini');
        }


        // Only students that belong to class.
        // Karena join tabel membuat kolom 'id' ambigu, gunakan 'users.id'.
        $studentIdsInClass = $class->students()->pluck('users.id')->toArray();


        foreach ($validated['grades'] as $row) {
            $studentId = (int) $row['student_id'];
            if (!in_array($studentId, $studentIdsInClass, true)) {
                continue;
            }

            // If nilai is empty, skip updating (so form can leave blank)
            if ($row['nilai'] === null || $row['nilai'] === '') {
                continue;
            }

            Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'subject_id' => $subject->id,
                    'admin_id' => $teacher->id,
                ],
                [
                    'nilai' => $row['nilai'],
                    'keterangan' => $row['keterangan'] ?? null,
                    'admin_id' => $teacher->id,
                ]
            );
        }

        return redirect()->route('guru_mapel.grade.refactored.form')
            ->with('success', 'Nilai berhasil disimpan!');
    }
}

