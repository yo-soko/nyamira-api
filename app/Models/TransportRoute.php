<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model
{
    protected $fillable = [
        'route_name',
        'fee',
        'status',
        'distance_km',
        'estimated_duration',
        'vehicle_id'
    ];

    protected $casts = [
        'status' => 'boolean',
        'fee' => 'decimal:2',
        'distance_km' => 'decimal:2',
    ];
    public $incrementing = true;
    protected $keyType = 'int';
    protected $table = 'transport_routes';

    public function stops()
    {
        return $this->hasMany(TransportStop::class, 'route_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function studentTransports()
    {
        return $this->hasMany(StudentTransport::class);
    }

    public function attendance()
    {
        return $this->hasMany(TransportAttendance::class);
    }
}
