<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expenses extends Model
{
     use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'expense_type',
        'vendor_id',
        'amount',
        'frequency',
        'date',
        'notes',
        'photos',
        'documents',
    ];

    protected $casts = [
        'date' => 'date',
        'photos' => 'array',
        'documents' => 'array',
    ];

    // 🔗 Relationships
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class);
    }
}
