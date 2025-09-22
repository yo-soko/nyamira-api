<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issues extends Model
{
   
    protected $fillable = [
        'vehicle_id',
        'priority',
        'reported_at',
        'summary',
        'description',
        'labels',
        'reported_by',
        'assigned_to',
        'due_date',
        'primary_meter_due',
        'status',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
