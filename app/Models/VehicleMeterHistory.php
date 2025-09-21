<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleMeterHistory extends Model
{
      protected $fillable = [
        'vehicle_id',
        'operator_id',
        'meter_reading',
        'source',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
