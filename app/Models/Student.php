<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id'); // assuming your class model is SchoolClass
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'student_id');

    }
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->second_name} {$this->last_name}";
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }



}
