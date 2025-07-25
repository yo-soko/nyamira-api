<?php

return [
    'reminders' => [
        'morning' => [
            'start' => '08:00',
            'end' => '11:30',
            'interval' => 10, // minutes
        ],
        'afternoon' => [
            'start' => '14:00',
            'end' => '18:00',
            'interval' => 15,
        ],
    ],

    'lat' => env('ATTENDANCE_LAT', -0.67778),
    'lng' => env('ATTENDANCE_LNG', 34.78222),
    'radius' => env('ATTENDANCE_RADIUS_METERS', 100), // in meters
];
