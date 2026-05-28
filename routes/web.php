<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataUserController;
use App\Http\Controllers\GuruMapelController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GuruController; // CRUD for both guru_mapel & wali_kelas
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\Auth\LoginController;

// Public route
Route::get('/', [DataUserController::class, 'index']);

// Auth routes - Custom login with role selection
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Auth routes (sudah built-in dari Laravel)
Route::middleware('auth')->group(function () {

    // Redirect berdasarkan role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'superadmin') {
            return redirect()->route('admin.superusers.dashboard');

        } elseif ($role === 'guru_mapel') {
            return redirect()->route('guru_mapel.dashboard');

        } elseif ($role === 'wali_kelas') {
            return redirect()->route('wali_kelas.dashboard');
        }

        return redirect()->route('student.dashboard');
    });



    // Routes untuk Guru (Admin)
    Route::middleware('auth.guru_mapel')->prefix('guru_mapel')->name('guru_mapel.')->group(function () {
        Route::get('/dashboard', [GuruMapelController::class, 'dashboard'])->name('dashboard');
        
        // Routes untuk Kelas
        Route::get('/classes', [GuruMapelController::class, 'listClasses'])->name('classes.index');
        Route::get('/classes/{class}', [GuruMapelController::class, 'showClass'])->name('class.show');
        
        // Routes untuk Nilai
        Route::get('/grades', [GuruMapelController::class, 'allGrades'])->name('grades.all');
        Route::get('/classes/{class}/students/{student}/grade', [GuruMapelController::class, 'inputGradeForm'])->name('grade.form');
        Route::post('/classes/{class}/students/{student}/grade', [GuruMapelController::class, 'storeGrade'])->name('grade.store');
        Route::get('/input-grade-other-class', [GuruMapelController::class, 'inputGradeOtherClassForm'])->name('grade.other-class.form');
        Route::post('/input-grade-other-class', [GuruMapelController::class, 'storeGradeOtherClass'])->name('grade.other-class.store');
        Route::get('/classes/{class}/grades', [GuruMapelController::class, 'viewGrades'])->name('grades.index');
        Route::get('/classes/{class}/grades/{grade}/edit', [GuruMapelController::class, 'editGrade'])->name('grades.edit');
        Route::put('/classes/{class}/grades/{grade}', [GuruMapelController::class, 'updateGrade'])->name('grades.update');
        
        // Refactored Grade Input Routes (Grouped by Class)
        Route::get('/grade/create', [GuruMapelController::class, 'showGradeRefactoredForm'])->name('grade.refactored.form');
        Route::post('/grade/store', [GuruMapelController::class, 'storeGradeRefactored'])->name('grade.refactored.store');
        Route::get('/api/classes/{class}/students', [GuruMapelController::class, 'getStudentsByClass'])->name('api.class.students');
        Route::get('/api/classes/{class}/subjects', [GuruMapelController::class, 'getSubjectsByClass'])->name('api.class.subjects');

        // Refactored mass input: kelas + mapel -> tabel siswa
        Route::get('/api/classes/{class}/subjects/{subject}/grades', [GuruMapelController::class, 'getGradesForClassSubject'])->name('api.class.subject.grades');
        Route::post('/grade/refactored/mass-store', [GuruMapelController::class, 'storeGradesMass'])->name('grade.refactored.mass.store');

// Routes untuk Mata Pelajaran

        Route::get('/subjects', [GuruMapelController::class, 'listSubjects'])->name('subjects.index');
        Route::get('/subjects/create', [GuruMapelController::class, 'createSubjectForm'])->name('subjects.create');
        Route::post('/subjects', [GuruMapelController::class, 'storeSubject'])->name('subjects.store');
    });


    // Routes untuk Siswa
Route::middleware('auth.student')->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/grades', [StudentController::class, 'myGrades'])->name('grades.index');
        Route::get('/grades/export-pdf', [StudentController::class, 'exportGradesPdf'])->name('grades.export-pdf');
        Route::get('/grades/teacher/{teacher}', [StudentController::class, 'gradesByTeacher'])->name('grades.by-teacher');
        Route::get('/statistics', [StudentController::class, 'statistics'])->name('statistics');
    });

    // Routes untuk Wali Kelas
    Route::middleware('auth.wali_kelas')->prefix('wali_kelas')->name('wali_kelas.')->group(function () {
        Route::get('/dashboard', [WaliKelasController::class, 'dashboard'])->name('dashboard');
        Route::get('/classes', [WaliKelasController::class, 'classes'])->name('classes.index');
        Route::get('/classes/{class}/grades', [WaliKelasController::class, 'classGrades'])->name('class.grades');
        Route::get('/classes/{class}/students/{student}/subjects/{subject}/edit', [WaliKelasController::class, 'editGrade'])->name('grade.edit');
        Route::put('/classes/{class}/students/{student}/subjects/{subject}', [WaliKelasController::class, 'updateGrade'])->name('grade.update');
    });

    // Admin Management Routes (SuperAdmin only)
    Route::middleware('auth.superadmin')->prefix('admin')->name('admin.')->group(function () {
Route::resource('classes', ClassController::class);
Route::get('classes/{class}/mapel/create', [ClassController::class, 'mapelForm'])->name('classes.mapel.form');
Route::post('classes/{class}/mapel', [ClassController::class, 'storeMapel'])->name('classes.mapel.store');

        Route::resource('gurus', GuruController::class);
        Route::resource('siswas', SiswaController::class);
    });
    
    // SuperAdmin Routes
    Route::middleware('auth.superadmin')->prefix('admin')->name('admin.superusers.')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('superusers', SuperAdminController::class)->only(['index', 'show']);
        Route::resource('subjects', SubjectController::class);
        Route::get('/subjects/{subject}/classes', [SubjectController::class, 'manageClasses'])->name('subjects.classes');
        Route::post('/subjects/{subject}/classes', [SubjectController::class, 'assignClasses'])->name('subjects.assign-classes');
    });

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
