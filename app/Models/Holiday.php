<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        'holiday_name', 'from_date', 'to_date', 'days_count', 'description', 'status'
    ];
}
