<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportAttendance extends Model
{
    protected $fillable = [
        'student_id',
        'date',
        'status',
        'marked_at',
        'pickup_location',
        'dropoff_location',
        'pickup_time',
        'dropoff_time',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'date' => 'date',
        'marked_at' => 'datetime',
        'pickup_time' => 'datetime:H:i:s',
        'dropoff_time' => 'datetime:H:i:s',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function route()
    {
        // Not directly stored in transport_attendances table anymore — handled via student_transport
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    public function stop()
    {
        // Optional: if you ever add stop_id column
        return $this->belongsTo(TransportStop::class, 'stop_id');
    }
}
