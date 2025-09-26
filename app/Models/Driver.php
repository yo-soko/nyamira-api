<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'identity_card_number',
        'driving_licence_number',
        'personal_number',
        'licence_date_issue',
        'department_id',
        'licence_date_expiry',
        'identity_card_file',
        'driving_licence_file',
        'passport_photo_file',
        'verified',
        'user_id',
    ];

    protected $casts = [
        'licence_date_issue' => 'date',
        'licence_date_expiry' => 'date',
        'verified' => 'boolean',
    ];
}
