<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassLevel extends Model
{
    use HasFactory;

    protected $fillable = ['level_name', 'status'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_levels', 'level_id', 'subject_id');
    }

}
