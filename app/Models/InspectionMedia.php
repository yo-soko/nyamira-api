<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'inspection_item_id',
        'file_path',
        'type',
    ];

    public function item()
    {
        return $this->belongsTo(InspectionItem::class, 'inspection_item_id');
    }
}
