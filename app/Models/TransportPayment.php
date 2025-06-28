<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportPayment extends Model
{
    protected $fillable = [
        'student_transport_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function transport()
    {
        return $this->belongsTo(StudentTransport::class, 'student_transport_id');
    }
}
