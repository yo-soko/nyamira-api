<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $primaryKey = 'fee_id';

    protected $fillable = [
        'level_id', 'term_id', 'amount', 'description', 'status'
    ];


    public function classLevel() {
        return $this->belongsTo(ClassLevel::class, 'level_id');
    }


    public function term() {
        return $this->belongsTo(Term::class, 'term_id');
    }

}
