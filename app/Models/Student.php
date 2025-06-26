<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'first_name',
        'user_id',
        'second_name',
        'last_name',
        'student_reg_number',
        'student_age',
        'class_id',
        'term_id',
        'about',
        'gender',
        'status',
        'current_balance',
    ];

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
    public function guardian()
    {
        return $this->hasOne(Guardian::class);
    }
    public function meal() { return $this->hasOne(StudentMeal::class); }
    public function transport()
    {
        return $this->hasOne(StudentTransport::class, 'student_id');
    }


    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects', 'student_id', 'subject_id');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }


}
