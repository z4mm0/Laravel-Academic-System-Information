<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'admin_id',
    ];

    // Relasi dengan Admin (Guru)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relasi dengan Grade
public function grades()
    {
        return $this->hasMany(Grade::class, 'subject_id');
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_subject', 'subject_id', 'class_id');
    }
}

