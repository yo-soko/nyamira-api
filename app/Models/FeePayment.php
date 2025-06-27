<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use HasFactory;

    protected $table = 'fee_payments'; // optional, but safe to include
    protected $primaryKey = 'payment_id';
    public $incrementing = true; // REQUIRED
    protected $keyType = 'int';  // REQUIRED if not UUID

    protected $fillable = [
        'student_id',
        'class_id',
        'term_id',
        'amount_paid',
        'payment_mode',
        'description',
        'receipt_number',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classLevel()
    {
        return $this->belongsTo(SchoolClass::class, 'level_id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }


    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }


    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->second_name} {$this->last_name}";
    }

}
