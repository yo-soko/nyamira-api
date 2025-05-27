<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = ['designation', 'department', 'status'];
    
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

}
