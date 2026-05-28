<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
$subjects = Subject::with('admin', 'classes')->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }


    public function create()
    {
        $gurus = User::where('role', 'guru_mapel')->get();
        return view('admin.subjects.form', compact('gurus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'admin_id' => 'required|exists:users,id',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.superusers.subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan');
    }

    public function show(Subject $subject)
    {
        $subject->load('admin', 'classes.admin', 'classes.students', 'grades');
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $gurus = User::where('role', 'guru_mapel')->get();
        return view('admin.subjects.form', compact('subject', 'gurus'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'admin_id' => 'required|exists:users,id',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.superusers.subjects.index')
            ->with('success', 'Mata pelajaran berhasil diupdate');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('admin.superusers.subjects.index')
            ->with('success', 'Mata pelajaran berhasil dihapus');
    }

    public function manageClasses(Subject $subject)
    {
        $classes = ClassModel::with('admin', 'students')->get();
        $assignedClasses = $subject->classes->pluck('id')->toArray();
        return view('admin.subjects.manage-classes', compact('subject', 'classes', 'assignedClasses'));
    }

    public function assignClasses(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'classes' => 'array',
            'classes.*' => 'exists:classes,id',
        ]);

        $subject->classes()->sync($request->classes ?? []);

        return redirect()->route('admin.superusers.subjects.show', $subject)
            ->with('success', 'Kelas untuk mata pelajaran berhasil diupdate');
    }
}