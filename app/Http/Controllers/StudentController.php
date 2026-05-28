<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Dashboard Siswa
    public function dashboard()
    {
        $student = auth()->user();
        $classes = $student->classesAsStudent;
        $grades = $student->grades()
            ->with('subject', 'teacher')
            ->paginate(20);

        $teacherClasses = $classes->map(fn($class) => $class->admin);

        return view('student.dashboard', compact('classes', 'grades', 'teacherClasses'));
    }

    // Lihat semua nilai
    public function myGrades()
    {
        $student = auth()->user();
        $grades = $student->grades()
            ->with('subject', 'teacher')
            ->get()
            ->groupBy('subject.admin.name'); // Group by guru

        return view('student.grades.index', compact('grades'));
    }

    // Lihat nilai per guru
    public function gradesByTeacher($teacherId)
    {
        $student = auth()->user();
        
        // Validasi siswa hanya bisa lihat nilai dari guru yang mengajar mereka
        $teacherClasses = $student->classesAsStudent->pluck('admin_id');
        
        if (!$teacherClasses->contains($teacherId)) {
            return redirect()->back()->with('error', 'Guru ini tidak mengajar Anda');
        }

        $grades = $student->grades()
            ->where('admin_id', $teacherId)
            ->with('subject', 'teacher')
            ->get();

        $teacher = $grades->first()?->teacher ?? null;

        return view('student.grades.by-teacher', compact('grades', 'teacher'));
    }

// Statistik nilai siswa
    public function statistics()
    {
        $student = auth()->user();
        $grades = $student->grades;

        $totalGrades = $grades->count();
        $averageGrade = $totalGrades > 0 ? $grades->avg('nilai') : 0;
        $highestGrade = $totalGrades > 0 ? $grades->max('nilai') : 0;
        $lowestGrade = $totalGrades > 0 ? $grades->min('nilai') : 0;

        return view('student.statistics', compact('totalGrades', 'averageGrade', 'highestGrade', 'lowestGrade', 'grades'));
    }

// Ekspor nilai ke PDF
    public function exportGradesPdf()
    {
        $student = auth()->user();
        $grades = $student->grades()
            ->with('subject', 'teacher')
            ->get()
            ->groupBy('subject.admin.name');

        $avgAll = $student->grades()->avg('nilai') ?? 0;
        
        $html = '<html><head><meta charset="UTF-8"><title>Laporan Nilai - '.$student->name.'</title>';
        $html .= '<style>
            @media print { body { -webkit-print-color-adjust: exact; } }
            body { font-family: Arial, sans-serif; padding: 20px; margin: 0; }
            h1 { color: #333; margin-top: 0; }
            h3 { color: #555; margin-top: 20px; border-bottom: 1px solid #4CAF50; padding-bottom: 5px; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
            th { background-color: #4CAF50; color: white; }
            tr:nth-child(even) { background-color: #f9f9f9; }
            .avg { font-weight: bold; color: #4CAF50; font-size: 18px; margin-top: 20px; }
            .teacher-avg { font-weight: bold; color: #333; margin-top: 5px; }
            .footer { margin-top: 30px; font-size: 11px; color: #666; text-align: center; }
            .no-print { display: none; }
        </style></head><body>';
        $html .= '<div class="no-print" style="margin-bottom: 20px;">';
        $html .= '<button onclick="window.print()" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer;">🖨️ Print</button>';
        $html .= '</div>';
        $html .= '<h1>Rapor Nilai Siswa</h1>';
        $html .= '<p><strong>Nama Siswa:</strong> '.$student->name.'</p>';
        $html .= '<p><strong>Tanggal Cetak:</strong> '.date('d F Y').'</p>';
        $html .= '<p class="avg">Rata-rata Keseluruhan: '.number_format($avgAll, 2).'</p>';
        
        foreach ($grades as $teacherName => $teacherGrades) {
            $html .= '<h3>Guru: '.$teacherName.'</h3>';
            $html .= '<table><thead><tr><th style="width:30px">No</th><th>Mata Pelajaran</th><th style="width:60px">Nilai</th><th>Keterangan</th></tr></thead><tbody>';
            $no = 1;
            $teacherAvg = $teacherGrades->avg('nilai');
            foreach ($teacherGrades as $grade) {
                $html .= '<tr><td>'.$no.'</td><td>'.$grade->subject->name.'</td><td><strong>'.$grade->nilai.'</strong></td><td>'.($grade->keterangan ?? '-').'</td></tr>';
                $no++;
            }
            $html .= '</tbody></table>';
            $html .= '<p class="teacher-avg">Rata-rata Guru ini: '.number_format($teacherAvg, 2).'</p>';
        }
        
        $html .= '<div class="footer">--- Rapor ini Generated oleh Sistem Informasi Siswa ---</div>';
        $html .= '<script>window.onload = function() { window.print(); }</script>';
        $html .= '</body></html>';
        
        return response($html)->withHeaders([
            'Content-Type' => 'text/html',
        ]);
    }
}
