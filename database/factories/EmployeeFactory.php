<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Shift;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'profile_photo' => null,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'contact_number' => $this->faker->phoneNumber,
            'emp_code' => 'EMP' . $this->faker->unique()->numberBetween(1000, 9999),
            'dob' => $this->faker->date('Y-m-d', '-20 years'),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'nationality' => $this->faker->country,
            'joining_date' => $this->faker->date('Y-m-d', '-1 years'),
            'shift_id' => Shift::inRandomOrder()->first()->id ?? Shift::factory(),
            'department_id' => Department::inRandomOrder()->first()->id ?? Department::factory(),
            'designation_id' => Designation::inRandomOrder()->first()->id ?? Designation::factory(),
            'blood_group' => $this->faker->randomElement(['A+', 'B+', 'O+', 'AB+']),
            'status' => true,
            'about' => null,

            'address' => $this->faker->address,
            'country' => $this->faker->country,
            'zipcode' => $this->faker->postcode,

            'emergency_contact1' => $this->faker->phoneNumber,
            'emergency_relation1' => 'Brother',
            'emergency_name1' => $this->faker->name,

            'emergency_contact2' => $this->faker->phoneNumber,
            'emergency_relation2' => 'Mother',
            'emergency_name2' => $this->faker->name,

            'bank_name' => 'ABC Bank',
            'account_number' => $this->faker->bankAccountNumber,
            'branch' => $this->faker->city,

            'password' => Hash::make('password'),
        ];
    }
}
