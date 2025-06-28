<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverAssignment extends Model
{
    protected $fillable = [
        'vehicle_id',
        'driver_name',
        'phone',
        'assigned_from',
        'assigned_to'
    ];

    protected $casts = [
        'assigned_from' => 'date',
        'assigned_to' => 'date',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
