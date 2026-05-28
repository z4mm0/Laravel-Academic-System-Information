<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    public function dashboard()
    {
        $wali = auth()->user();
        $classes = $wali->classes;
        $totalStudents = $classes->sum(fn($class) => $class->students->count());
        $totalGrades = Grade::whereIn('student_id', $classes->flatMap->students->pluck('id'))->count();
        
        return view('wali_kelas.dashboard', compact('classes', 'totalStudents', 'totalGrades'));
    }

    public function classes()
    {
        $classes = auth()->user()->classes;
        return view('wali_kelas.classes.index', compact('classes'));
    }

    public function classGrades(ClassModel $class)
    {
if (!auth()->user()->isWaliKelas() && $class->admin_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kelas ini');
        }

        $students = $class->students;
$subjects = Subject::whereHas('classes', function($q) use($class) { $q->where('classes.id', $class->id); })->get();


        // Get all grades for this class
        $grades = Grade::whereIn('student_id', $students->pluck('id'))
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->with('student', 'subject.admin')
            ->get()
            ->keyBy(function ($grade) {
                return $grade->student_id . '-' . $grade->subject_id;
            });

        return view('wali_kelas.grades.index', compact('class', 'students', 'subjects', 'grades'));
    }

    public function editGrade(ClassModel $class, User $student, $subjectId)
    {
if (!auth()->user()->isWaliKelas() && $class->admin_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke kelas ini');
        }

        if (!$class->students()->where('student_id', $student->id)->exists()) {
            return redirect()->back()->with('error', 'Siswa bukan dari kelas ini');
        }

        if (!$class->subjects()->where('subjects.id', $subjectId)->exists()) {
            return redirect()->back()->with('error', 'Mata pelajaran ini tidak ada di kelas Anda');
        }

$subject = \App\Models\Subject::findOrFail($subjectId);
        $grade = Grade::where([
            'student_id' => $student->id,
            'subject_id' => $subjectId,
        ])->first();

        return view('wali_kelas.grades.form', compact('class', 'student', 'subject', 'grade'));
    }

    public function updateGrade(Request $request, ClassModel $class, User $student, $subjectId)
    {
        if ($class->admin_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Bukan kelas Anda');
        }

        if (!$class->subjects()->where('subjects.id', $subjectId)->exists()) {
            return redirect()->back()->with('error', 'Mata pelajaran ini tidak ada di kelas Anda');
        }

        $grade = Grade::where([
            'student_id' => $student->id,
            'subject_id' => $subjectId,
        ])->first();

        if (!$grade) {
            return redirect()->back()->with('error', 'Nilai untuk siswa dan mapel ini belum tersedia.');
        }

$validated = $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $validated['admin_id'] = auth()->id();

        Grade::updateOrCreate([
            'student_id' => $student->id,
            'subject_id' => $subjectId,
        ], $validated);


        return redirect()->route('wali_kelas.class.grades', $class)
            ->with('success', 'Nilai berhasil diupdate');
    }
}

