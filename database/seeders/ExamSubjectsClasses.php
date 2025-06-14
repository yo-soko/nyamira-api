<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ClassLevel;
use Illuminate\Support\Facades\DB;

class ExamSubjectsClasses extends Seeder
{
    public function run(): void
    {
        // Fetch the exam
        $exam = Exam::where('name', 'First CAT')->first();

        if (!$exam) {
            echo "Exam not found. Run ExamsTableSeeder first.\n";
            return;
        }

        // Fetch all subjects and levels
        $subjects = Subject::all();
        $levels = ClassLevel::all();

        // For each level and subject, assign to the exam
        foreach ($levels as $level) {
            foreach ($subjects as $subject) {
                DB::table('exam_subjects_classes')->updateOrInsert(
                    [
                        'exam_id' => $exam->id,
                        'subject_id' => $subject->id,
                        'level_id' => $level->id,
                    ]
                );
            }
        }

        echo "Exam subjects and levels assigned successfully.\n";
    }
}
