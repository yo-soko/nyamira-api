<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportStop extends Model
{
    protected $fillable = [
        'route_id',
        'stop_name',
        'stop_order',
        'pickup_time',
        'dropoff_time'
    ];

    public function route()
    {
        return $this->belongsTo(TransportRoute::class);
    }

    public function studentTransports()
    {
        return $this->hasMany(StudentTransport::class);
    }
}
