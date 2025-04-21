<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
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
        'shift',
        'department',
        'designation',
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
    ];

    protected $hidden = ['password'];
}
