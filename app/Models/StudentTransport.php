<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTransport extends Model
{
    protected $table = 'student_transports';
    protected $fillable = [
        'student_id',
        'term_id',
        'class_id',
        'route_id',
        'transport_type',
        'transport_fee',
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

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }
}
