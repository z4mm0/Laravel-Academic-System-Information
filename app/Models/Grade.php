<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'admin_id',
        'nilai',
        'keterangan',
    ];

    // Relasi dengan Siswa
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relasi dengan Mata Pelajaran
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Relasi dengan Guru
    public function teacher()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
