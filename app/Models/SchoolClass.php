<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'school_classes';

    protected $fillable = [
        'level_id',
        'stream_id',
        'name',
        'capacity',
        'class_teacher',
        'class_prefect',
        'status',
    ];
    
    public function level()
    {
        return $this->belongsTo(ClassLevel::class, 'level_id');
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }

    public function classTeacher()
    {
        return $this->belongsTo(User::class, 'class_teacher');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function classPrefect()
    {
        return $this->belongsTo(Student::class, 'class_prefect');
    }
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'class_id', 'subject_id');
    }

}
