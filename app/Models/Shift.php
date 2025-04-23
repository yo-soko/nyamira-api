<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    protected $primaryKey = 'shift_name';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
        'day_off',
        'days',
        'recurring',
        'status',
        'morning_break_from',
        'morning_break_to',
        'lunch_break_from',
        'lunch_break_to',
        'evening_break_from',
        'evening_break_to',
        'description',
    ];

}
 