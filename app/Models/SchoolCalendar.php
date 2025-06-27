<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'event_date',
        'start_time',
        'end_time',
        'event_location',
        'description',
        'is_holiday',
        'event_color'
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_holiday' => 'boolean'
    ];

    public function getFullCalendarEventAttribute()
    {
        return [
            'title' => $this->event_name,
            'start' => $this->event_date->format('Y-m-d') . 'T' . $this->start_time->format('H:i:s'),
            'end' => $this->end_time ? $this->event_date->format('Y-m-d') . 'T' . $this->end_time->format('H:i:s') : null,
            'location' => $this->event_location,
            'description' => $this->description,
            'color' => $this->event_color,
            'extendedProps' => [
                'location' => $this->event_location,
                'description' => $this->description,
                'isHoliday' => $this->is_holiday
            ]
        ];
    }
}
