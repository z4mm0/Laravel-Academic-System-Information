<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
        'nohp',
        'alamat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    // Superadmin helper
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    // Relasi untuk Admin (Guru)
    public function classes()
    {
        return $this->hasMany(ClassModel::class, 'admin_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'admin_id');
    }

    public function gradesAsTeacher()
    {
        return $this->hasMany(Grade::class, 'admin_id');
    }

    // Relasi untuk Siswa
    public function classesAsStudent()
    {
        return $this->belongsToMany(ClassModel::class, 'class_student', 'student_id', 'class_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    // Helper method
public function isGuruMapel()
    {
        return $this->role === 'guru_mapel';
    }

    public function isWaliKelas()
    {
        return $this->role === 'wali_kelas';
    }

    public function isStudent()
    {
        return $this->role === 'siswa';
    }}