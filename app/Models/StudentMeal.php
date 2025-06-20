<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMeal extends Model
{
    protected $fillable = [
        'student_id',
        'term_id',
        'class_id',
        'meal_plan_id',
        'meal_fee',
        'balance',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class, 'meal_plan_id');
    }
}
