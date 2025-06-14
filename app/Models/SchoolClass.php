<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{

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

}
