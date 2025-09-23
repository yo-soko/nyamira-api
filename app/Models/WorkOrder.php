<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $fillable = [
        'vehicle_id', 'status', 'priority_class',
        'issue_date', 'issued_by', 'scheduled_start_date',
        'actual_start_date', 'expected_completion_date',
        'actual_completion_date', 'start_odometer', 'completion_odometer',
        'assigned_to', 'labels', 'vendor_id', 'invoice_number',
        'po_number', 'notes', 'labor_cost', 'parts_cost',
        'discount', 'tax', 'total_cost',
    ];

    protected $casts = [
        'issue_date' => 'datetime',
        'scheduled_start_date' => 'datetime',
        'actual_start_date' => 'datetime',
        'expected_completion_date' => 'datetime',
        'actual_completion_date' => 'datetime',
    ];

    // Relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // If pivot table is issue_work_order
    public function issues()
    {
        return $this->belongsToMany(Issue::class, 'issue_work_order');
    }

    // If you want service entries too
    public function serviceEntries()
    {
        return $this->hasMany(ServiceEntry::class);
    }

    public function tasks()
    {
        return $this->hasMany(WorkOrders::class);
    }
}
