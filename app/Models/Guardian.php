<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'guardian_first_name',
        'guardian_last_name',
        'guardian_relationship',
        'first_phone',
        'second_phone',
        'address',
        'id_number',
        'email',
        'guardian_about',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
