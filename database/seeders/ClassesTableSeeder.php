<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolClass;
use Carbon\Carbon;

class ClassesTableSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['stream_id' => 12, 'class_teacher' => 2, 'class_prefect' => null, 'capacity' => 22, 'status' => 1, 'level_id' => 5],
            ['stream_id' => 11, 'class_teacher' => 3, 'class_prefect' => 'Dahlia', 'capacity' => 23, 'status' => 1, 'level_id' => 5],
            ['stream_id' => 16, 'class_teacher' => 4, 'class_prefect' => 'Edna Nyaboke', 'capacity' => 19, 'status' => 1, 'level_id' => 11],
            ['stream_id' => 14, 'class_teacher' => 5, 'class_prefect' => 'Jilliann Hadassah', 'capacity' => 39, 'status' => 1, 'level_id' => 7],
            ['stream_id' => 15, 'class_teacher' => 6, 'class_prefect' => 'Whitney Machuka', 'capacity' => 22, 'status' => 1, 'level_id' => 10],
            ['stream_id' => 5, 'class_teacher' => 7, 'class_prefect' => 'Camilla Alexsa', 'capacity' => 24, 'status' => 1, 'level_id' => 4],
            ['stream_id' => 10, 'class_teacher' => 8, 'class_prefect' => 'Nancy Moraa', 'capacity' => 22, 'status' => 1, 'level_id' => 4],
            ['stream_id' => 9, 'class_teacher' => 9, 'class_prefect' => 'Evan Moindi', 'capacity' => 23, 'status' => 1, 'level_id' => 4],
            ['stream_id' => 13, 'class_teacher' => 10, 'class_prefect' => 'Precious Hope', 'capacity' => 36, 'status' => 1, 'level_id' => 8],
            ['stream_id' => 6, 'class_teacher' => 11, 'class_prefect' => 'Milan Camillus', 'capacity' => 37, 'status' => 1, 'level_id' => 6],
            ['stream_id' => 7, 'class_teacher' => 12, 'class_prefect' => 'Acie Warren', 'capacity' => 37, 'status' => 1, 'level_id' => null],
        ];

        foreach ($data as $item) {
            SchoolClass::create($item);
        }
    }
}

