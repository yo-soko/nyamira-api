<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportAttendance extends Model
{
    protected $fillable = [
        'student_id',
        'route_id',
        'date',
        'pickup_location',
        'dropoff_location',
        'pickup_time',
        'dropoff_time',
        'pickup_status',
        'dropoff_status',
    ];

    protected $casts = [
        'date' => 'date',
        'pickup_time' => 'string',       // stored as TIME in MySQL
        'dropoff_time' => 'string',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    public function stop()
    {
        // Reserved for future use if stop_id is ever added to this model
        return $this->belongsTo(TransportStop::class, 'stop_id');
    }

    // Optional: Computed general status if needed
    public function getStatusAttribute()
    {
        if ($this->pickup_status === 'present' || $this->dropoff_status === 'present') {
            return 'present';
        }

        if ($this->pickup_status === 'absent' && $this->dropoff_status === 'absent') {
            return 'absent';
        }

        return 'partial'; // covers one session marked, one not marked
    }
}
