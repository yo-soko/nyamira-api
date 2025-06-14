<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubjectsClasses extends Model
{
    // App\Models\ExamSubject.php

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function level()
    {
        return $this->belongsTo(ClassLevel::class);
    }

}
