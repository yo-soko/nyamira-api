<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MealPlan;

class MealPlanSeeder extends Seeder
{
    public function run(): void
    {
        MealPlan::insert([
            ['plan_name' => 'Full Board', 'term_id' => 2, 'fee' => 3000.00, 'status' => 1],
            ['plan_name' => 'Lunch Only', 'term_id' => 2, 'fee' => 2500.00, 'status' => 0],
            ['plan_name' => 'Snacks Only', 'term_id' => 2, 'fee' => 1500.00, 'status' => 0],
        ]);
    }
}

