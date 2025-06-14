<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
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

}
