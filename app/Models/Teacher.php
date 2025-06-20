<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'date_of_birth', 'email', 'phone',
        'id_no', 'address', 'education_level', 'years_of_experience',
        'gender', 'department', 'status'
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher_class')
                    ->withPivot('schoolclass_id')
                    ->withTimestamps();
    }

    public function schoolclasses()
    {
        return $this->belongsToMany(Schoolclass::class, 'subject_teacher_class')
                    ->withPivot('subject_id')
                    ->withTimestamps();
    }
}
