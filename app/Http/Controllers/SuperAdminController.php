<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index()
    {
        $users = User::with(['classesAsStudent', 'grades'])->paginate(10);
        return view('admin.superusers.index', compact('users'));
    }

    public function show(User $user)
    {
        $classes = $user->classesAsStudent;
        $classesTeacher = $user->classes;
        $grades = $user->grades;
        $gradesTeacher = $user->gradesAsTeacher;
        return view('admin.superusers.show', compact('user', 'classes', 'classesTeacher', 'grades', 'gradesTeacher'));
    }

    public function dashboard()
    {
        $totalGuru = User::whereIn('role', ['guru_mapel', 'wali_kelas'])->count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalSuperadmin = User::where('role', 'superadmin')->count();
        $totalClasses = \App\Models\ClassModel::count();
        $totalSubjects = \App\Models\Subject::count();
        $recentGuru = User::whereIn('role', ['guru_mapel', 'wali_kelas'])->latest()->take(5)->get();
        $recentSiswa = User::where('role', 'siswa')->latest()->take(5)->get();

        return view('admin.superusers.dashboard', compact(
            'totalGuru', 'totalSiswa', 'totalSuperadmin', 'totalClasses', 'totalSubjects',
            'recentGuru', 'recentSiswa'
        ));
    }
}

