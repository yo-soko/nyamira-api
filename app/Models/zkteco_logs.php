<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class zkteco_logs extends Model
{
    protected $fillable = [
        'user_id', 'log_date', 'dropoff_time', 'pickup_time', 'last_synced_at'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'code'); 
        // 'user_id' in logs matches 'code' in users
    }
}
