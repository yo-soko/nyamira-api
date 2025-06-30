<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    protected $table = 'teacher_subject';

    public $incrementing = true; // you have an `id` column, so this should be true
    public $timestamps = true;   // your migration includes timestamps

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'class_id', // include the class_id
    ];

    /**
     * Relationships
     */

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
       public function level()
    {
        return $this->belongsTo(ClassLevel::class, 'level_id');
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }
}
