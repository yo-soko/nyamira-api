<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelHistory extends Model
{
    protected $fillable = [
        'vehicle_id',
        'fuel_entry_date',
        'vendor_id',
        'reference',
        'is_personal',
        'is_partial',
        'reset_usage',
        'photos',
        'documents',
        'comments',
    ];

    protected $casts = [
        'fuel_entry_date' => 'datetime',
        'photos' => 'array',
        'documents' => 'array',
        'is_personal' => 'boolean',
        'is_partial' => 'boolean',
        'reset_usage' => 'boolean',
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
