<?php

namespace Database\Factories;

use App\Models\Designation;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;

class DesignationFactory extends Factory
{
    protected $model = Designation::class;

    public function definition(): array
    {
        return [
            'designation' => $this->faker->jobTitle,
            'department_id' => Department::inRandomOrder()->first()->id ?? Department::factory(),
            'status' => true,
        ];
    }
}