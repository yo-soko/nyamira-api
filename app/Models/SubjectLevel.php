<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectLevel extends Model
{
    protected $fillable = ['subject_id', 'level_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function level()
    {
        return $this->belongsTo(ClassLevel::class, 'level_id');
    }
}
