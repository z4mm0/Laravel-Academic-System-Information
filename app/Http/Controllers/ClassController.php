<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    // List all classes (superadmin)
    public function index()
    {
        $classes = ClassModel::with(['admin', 'students'])->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    // Create form
    public function create()
    {
        $gurus = User::whereIn('role', ['guru_mapel', 'wali_kelas'])->get();
        return view('admin.classes.form', compact('gurus'));
    }

    // Store new class
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'admin_id' => 'required|exists:users,id',
        ], [
            'name.required' => 'Nama kelas harus diisi',
            'admin_id.required' => 'Wali kelas harus dipilih',
        ]);

        ClassModel::create($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil ditambahkan');
    }

    // Edit form
    public function edit(ClassModel $class)
    {
        $gurus = User::whereIn('role', ['guru_mapel', 'wali_kelas'])->get();
        return view('admin.classes.form', compact('class', 'gurus'));
    }

    // Update class
    public function update(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'admin_id' => 'required|exists:users,id',
        ], [
            'name.required' => 'Nama kelas harus diisi',
            'admin_id.required' => 'Wali kelas harus dipilih',
        ]);

        $class->update($validated);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil diupdate');
    }

    // Delete class
    public function destroy(ClassModel $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus');
    }

    // Show detail class
    public function show(ClassModel $class)
    {
        $class->load(['admin', 'students.user']);
        $class->setRelation('subjects', $class->classSubjects->pluck('subject'));
        $subjects = Subject::all();
        return view('admin.classes.show', compact('class', 'subjects'));
    }

    public function mapelForm(ClassModel $class)
    {
        $subjects = Subject::all();
        return view('admin.classes.mapel_form', compact('class', 'subjects'));
    }

    public function storeMapel(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id|unique:class_subject,subject_id,NULL,id,class_id,' . $class->id,
        ]);

        $class->subjects()->attach($validated['subject_id']);

        return redirect()->route('admin.classes.show', $class)
            ->with('success', 'Mata pelajaran berhasil ditambahkan');
    }
}

