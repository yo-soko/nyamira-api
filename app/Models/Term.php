<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $fillable = [
        'term_name', 'year', 'start_date', 'end_date', 'status'
    ];
    public function exams()
    {
        return $this->hasMany(\App\Models\Exam::class);
    }

}
