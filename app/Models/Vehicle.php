<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'registration_number',
        'model',
        'capacity',
        'insurance_expiry'
    ];

    protected $casts = [
        'insurance_expiry' => 'date',
    ];

    public function route()
    {
        return $this->hasOne(TransportRoute::class, 'vehicle_id');
    }

    public function drivers()
    {
        return $this->hasMany(DriverAssignment::class);
    }

    public function currentDriver()
    {
        return $this->drivers()
            ->whereNull('assigned_to')
            ->orWhere('assigned_to', '>=', now())
            ->orderBy('assigned_from', 'desc')
            ->first();
    }
}
