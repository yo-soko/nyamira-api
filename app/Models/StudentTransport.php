<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTransport extends Model
{
    protected $fillable = [
        'student_id',
        'term_id',
        'class_id',
        'route_id',
        'stop_id',
        'transport_type',
        'transport_fee',
        'balance'
    ];

    protected $casts = [
        'transport_fee' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

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
        return $this->belongsTo(TransportRoute::class);
    }

    public function stop()
    {
        return $this->belongsTo(TransportStop::class);
    }

    public function attendance()
    {
        return $this->hasMany(TransportAttendance::class, 'student_id', 'student_id');
    }

    public function payments()
    {
        return $this->hasMany(TransportPayment::class);
    }

    public function transportAttendances()
    {
        return $this->hasMany(TransportAttendance::class);
    }
}
