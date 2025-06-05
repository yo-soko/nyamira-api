<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'profile_photo',
        'first_name',
        'last_name',
        'email',
        'contact_number',
        'emp_code',
        'dob',
        'gender',
        'nationality',
        'joining_date',
        'shift_id',
        'department_id',
        'designation_id',
        'blood_group',
        'status',
        'about',
        'address',
        'country',
        'zipcode',
        'emergency_contact1',
        'emergency_relation1',
        'emergency_name1',
        'emergency_contact2',
        'emergency_relation2',
        'emergency_name2',
        'bank_name',
        'account_number',
        'branch',
        'password',
        'user_id',
    ];

    protected $hidden = ['password'];

    public function attendancies() {
        return $this->hasMany(Attendancies::class);
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function department()
    {
        return $this->belongsTo(Department::class);
    }
     public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

}
