<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class leaves extends Model
{
   protected $fillable = [
    'user_id', 'leave_type_id', 'from_date', 'to_date', 'leave_mode', 'reason', 'days', 'status',
    ];
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
