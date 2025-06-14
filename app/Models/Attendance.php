<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
    'student_id',
    'class_id',
    'user_id',
    'date',
    'session',
    'status',
    'reason',
];

}
