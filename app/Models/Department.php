<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'hod_id', 'description', 'status'];

    public function hod()
    {
        return $this->belongsTo(User::class, 'hod_id');
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

}
