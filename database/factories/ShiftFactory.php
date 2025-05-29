<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShiftFactory extends Factory
{
    protected $model = Shift::class;

    public function definition(): array
    {
        return [
            'shift_name' => $this->faker->word . ' Shift',
            'start_time' => '08:00',
            'end_time' => '17:00',
            'days' => json_encode(['Mon', 'Tue', 'Wed', 'Thu', 'Fri']),
            'day_off' => 'Saturday',
            'recurring' => true,
            'status' => true,
            'morning_from' => '08:00',
            'morning_to' => '10:00',
            'lunch_from' => '12:00',
            'lunch_to' => '13:00',
            'evening_from' => '15:00',
            'evening_to' => '17:00',
            'description' => $this->faker->sentence,
        ];
    }
}
