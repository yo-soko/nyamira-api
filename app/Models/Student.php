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
        return $this->belongsTo(Term::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'student_id'); 
    
    }
    public function results()
    {
        return $this->hasMany(Result::class);
    }

}
