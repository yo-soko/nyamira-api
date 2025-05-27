<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = ['designation', 'department_id', 'status'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function department()
    {
        return $this->hasMany(Department::class);
    }
}
