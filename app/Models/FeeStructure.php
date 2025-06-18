<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id', 'term_id', 'amount', 'description', 'status'
    ];

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class, 'class_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
