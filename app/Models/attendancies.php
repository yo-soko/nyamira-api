<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class attendancies extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'status',
        'production',
        'break',
        'overtime',
        'total_hours',
        'progress',
    ];
    
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    
}
