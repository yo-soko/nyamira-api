<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'date_of_birth', 'email', 'phone',
        'id_no', 'address', 'education_level', 'years_of_experience',
        'gender', 'department_id', 'status', 'user_id'
    ];

    /**
     * Get all subjects taught by the teacher with class_id on the pivot
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')
            ->withPivot('class_id')
            ->withTimestamps();
    }

    /**
     * Get all classes taught by the teacher
     */
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'teacher_subject', 'teacher_id', 'class_id')
            ->distinct();
    }

    /**
     * The department the teacher belongs to
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * In case you need direct teacher_subject rows
     */
    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class);
    }
}
