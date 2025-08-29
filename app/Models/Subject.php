<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['subject_code', 'subject_name', 'status'];

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_subjects_classes', 'subject_id', 'exam_id')
            ->withPivot('level_id')
            ->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject')
            ->withPivot('class_id')
            ->withTimestamps();
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subjects', 'subject_id', 'class_id');
    }
          public function level()
    {
        return $this->belongsTo(ClassLevel::class, 'level_id');
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class, 'stream_id');

    }

    public function subjectLevels()
    {
        return $this->hasMany(SubjectLevel::class, 'subject_id');
    }
    public function levels()
    {
        return $this->belongsToMany(ClassLevel::class, 'subject_levels', 'subject_id', 'level_id');
    }

     public function timetables()
    {
        return $this->hasMany(Timetable::class, 'subject_id');
    }

}

