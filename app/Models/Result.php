<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'term_id',
        'user_id',
        'subject_id',
        'class_id',
        'marks',
        'absent',
        'comments',
        'grade',
    ];
   public function student() {
        return $this->belongsTo(Student::class);
    }

    public function exam() {
        return $this->belongsTo(Exam::class);
    }

    public function term() {
        return $this->belongsTo(Term::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function class() {
        return $this->belongsTo(SchoolClass::class); 
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
