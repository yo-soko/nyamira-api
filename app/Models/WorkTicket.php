<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkTicket extends Model
{
    protected $fillable = [
        'user_id', 'vehicle_id', 'authorized_by', 'authorized_designation', 'authorized_signature',
        'travel_date', 'start_point', 'end_point', 'purpose',
        'passengers', 'fuel_used', 'fuel_source',
        'start_mileage', 'end_mileage', 'estimated_distance', 'notes', 'status', 'approved_by', 'approval_remarks', 'department_id'
    ];

    protected $casts = [
        'passengers' => 'array',
        'travel_date' => 'date',
    ];

    public function driver()  { return $this->belongsTo(User::class); }
    public function user()  { return $this->belongsTo(User::class); }
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
}
