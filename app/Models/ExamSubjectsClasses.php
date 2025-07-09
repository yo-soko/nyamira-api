<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubjectsClasses extends Model
{
    protected $table = 'exam_subjects_classes';

    protected $fillable = [
        'exam_id',
        'subject_id',
        'term_id',
        'level_id',
        'school_class_id',
        'status',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function level()
    {
        return $this->belongsTo(ClassLevel::class, 'level_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }
}
