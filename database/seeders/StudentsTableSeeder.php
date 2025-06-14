<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        foreach (range(1, 20) as $index) {
            DB::table('students')->insert([
                'first_name' => $faker->firstName,
                'second_name' => $faker->optional()->firstName,
                'last_name' => $faker->lastName,
                'student_reg_number' => strtoupper(Str::random(6)) . $index,
                'student_age' => $faker->dateTimeBetween('-18 years', '-6 years')->format('Y-m-d'),
                'class_id' => rand(1, 5), 
                'term_id' => rand(1, 3), 
                'about' => $faker->optional()->sentence,
                'gender' => $faker->randomElement(['Male', 'Female']),
                'status' => 1,
                'date_created' => Carbon::now(),
                'date_updated' => Carbon::now(),
                'current_balance' => $faker->randomFloat(2, 0, 10000),
            ]);
        }
    }
}
