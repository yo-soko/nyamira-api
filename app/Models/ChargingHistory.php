<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargingHistory extends Model
{
    protected $fillable = [
        'vehicle_id',
        'vendor_id',
        'odometer',
        'charging_started',
        'charging_ended',
        'charging_duration',
        'total_energy',
        'energy_price',
        'energy_cost',
        'reference',
        'is_personal',
        'photos',
        'documents',
        'comments',
    ];

    protected $casts = [
        'charging_started' => 'datetime',
        'charging_ended' => 'datetime',
        'photos' => 'array',
        'documents' => 'array',
        'is_personal' => 'boolean',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class);
    }
}
