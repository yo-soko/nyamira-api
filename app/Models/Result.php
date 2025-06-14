<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
   public function student() {
        return $this->belongsTo(Student::class);
    }

    public function exam() {
        return $this->belongsTo(Exam::class);
    }

    public function term() {
        return $this->belongsTo(Term::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function class() {
        return $this->belongsTo(SchoolClass::class); // or your actual class model
    }

    public function teacher() {
        return $this->belongsTo(User::class, 'user_id');
    }

}
