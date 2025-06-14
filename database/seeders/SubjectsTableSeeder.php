<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectsTableSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['subject_code' => '121', 'subject_name' => 'Mathematics', 'status' => 1],
            ['subject_code' => '1', 'subject_name' => 'Language', 'status' => 1],
            ['subject_code' => '201', 'subject_name' => 'Environmental Activities', 'status' => 1],
            ['subject_code' => '401', 'subject_name' => 'Creative Activities', 'status' => 1],
            ['subject_code' => '301', 'subject_name' => 'Religious Education (CRE)', 'status' => 1],
            ['subject_code' => '101', 'subject_name' => 'English', 'status' => 1],
            ['subject_code' => '101B', 'subject_name' => 'Literacy', 'status' => 1],
            ['subject_code' => '102', 'subject_name' => 'Kiswahili', 'status' => 1],
            ['subject_code' => '222', 'subject_name' => 'Social Studies', 'status' => 1],
            ['subject_code' => '202', 'subject_name' => 'Science and Technology', 'status' => 1],
            ['subject_code' => '402', 'subject_name' => 'Agriculture', 'status' => 1],
            ['subject_code' => '501', 'subject_name' => 'Indigenous Languages', 'status' => 1],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}

