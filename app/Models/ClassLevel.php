<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassLevel extends Model
{
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_subjects_classes', 'level_id', 'exam_id')
                    ->withPivot('subject_id')
                    ->withTimestamps();
    }

    public function getDisplayNameAttribute()
    {
        return $this->level_name;
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }


}
