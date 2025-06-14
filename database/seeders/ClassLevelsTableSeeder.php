<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassLevel;

class ClassLevelsTableSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['level_name' => 'Grade 1', 'status' => 1],
            ['level_name' => 'Grade 2', 'status' => 1],
            ['level_name' => 'Grade 3', 'status' => 1],
            ['level_name' => 'Grade 4', 'status' => 1],
        ];

        foreach ($levels as $level) {
            ClassLevel::create($level);
        }
    }
}

