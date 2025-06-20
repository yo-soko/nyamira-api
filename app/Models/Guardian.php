<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    
    protected $fillable = [
        'student_id',
        'guardian_first_name',
        'guardian_last_name',
        'address',
        'guardian_relationship',
        'first_phone',
        'second_phone',
        'id_number',
        'email',
        'guardian_about',
    ];

    // Relationship to student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
