<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendancies extends Model
{
    protected $fillable = [
        'employee_id',
        'shift_id',
        'date',
        'clock_in',
        'clock_out',
        'status',
        'production',
        'break',
        'overtime',
        'total_hours',
        'progress',
        'auto_clocked_out',
    ];
    
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');  // Ensure 'shift_id' is the correct foreign key
    }
}
