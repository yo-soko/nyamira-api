<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_id',
        'item_name',
        'status',
        'remark',
        'attachment',
    ];

    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }

    public function media()
    {
        return $this->hasMany(InspectionMedia::class);
    }
}

