<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'date_of_birth', 'email', 'phone',
        'id_no', 'address', 'education_level', 'years_of_experience',
        'gender', 'department_id', 'status', 'user_id'
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject');
    }        
                    
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function schoolclasses()
    {
        return $this->belongsToMany(Schoolclass::class);
    }
}
