<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    protected $table = 'teacher_subject';

    public $incrementing = false; // Since this is a pivot table with composite keys

    public $timestamps = false; // If your pivot table does not have created_at/updated_at

    protected $fillable = [
        'teacher_id',
        'subject_id',
    ];
}
