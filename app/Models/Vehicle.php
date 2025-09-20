<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'name','vin','license_plate','type','fuel_type','year',
        'make','model','trim','registration_state','status','group',
        'operator_id','ownership','color','body_type','body_subtype',
        'msrp','photo','labels','purchase_date','purchase_price',
        'retirement_date','insurance_policy_number','insurance_expiry',
        'loan_details',
    ];

    protected $casts = [
        'labels' => 'array',
        'purchase_date' => 'date',
        'retirement_date' => 'date',
        'insurance_expiry' => 'date',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}
