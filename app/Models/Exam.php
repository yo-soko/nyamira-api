<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['name','user_id', 'term_id', 'is_analysed', 'status'];

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'exam_subjects_classes', 'exam_id', 'subject_id')
                    ->withPivot('level_id')
                    ->withTimestamps();
    }

    public function levels()
    {
        return $this->belongsToMany(ClassLevel::class, 'exam_subjects_classes', 'exam_id', 'level_id')
                    ->withPivot('subject_id')
                    ->withTimestamps();
    }
    public function examSubjects()
    {
        return $this->hasMany(ExamSubjectsClasses::class);
    }
    public function results()
    {
        return $this->hasMany(Result::class);
    }
    public function examSubjectsClasses()
    {
        return $this->hasMany(\App\Models\ExamSubjectsClasses::class, 'exam_id', 'id');
    }

}
