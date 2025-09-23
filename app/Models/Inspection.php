<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'inspector_id',
        'inspection_date',
        'odometer_reading',
        'is_void',
        'status',
        'notes',
        'vehicle_condition_ok',
        'condition_remark',
        'reviewing_driver_signature',
        'driver_remark',
    ];

    protected $casts = [
        'inspection_date' => 'datetime',
        'is_void' => 'boolean',
        'vehicle_condition_ok' => 'boolean',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function items()
    {
        return $this->hasMany(InspectionItem::class);
    }
}
