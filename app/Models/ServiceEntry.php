<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceEntry extends Model
{
    protected $fillable = [
        'vehicle_id',
        'priority_class',
        'odometer',
        'completion_date',
        'start_date',
        'reference',
        'vendor_id',
        'labels',
        'notes',
        'labor_cost',
        'parts_cost',
        'discount',
        'tax',
        'total_cost',
    ];
    protected $casts = [
        'completion_date' => 'datetime',
        'start_date' => 'datetime',
    ];


    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class);
    }

    public function issues()
    {
        return $this->belongsToMany(Issues::class, 'issue_service_entry', 'service_entry_id', 'issue_id');
    }

}
