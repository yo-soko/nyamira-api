<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{  
    use HasFactory;
    protected $fillable = ['designation', 'department_id', 'status'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
