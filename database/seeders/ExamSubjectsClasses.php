<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\ClassLevel;
use App\Models\SchoolClass;
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

        foreach ($levels as $level) {
            // get classes for this level
            $classes = SchoolClass::where('level_id', $level->id)->get();

            foreach ($classes as $class) {
                foreach ($subjects as $subject) {
                    DB::table('exam_subjects_classes')->updateOrInsert(
                        [
                            'exam_id' => $exam->id,
                            'term_id' => 1,
                            'subject_id' => $subject->id,
                            'level_id' => $level->id,
                            'school_class_id' => $class->id,
                        ],
                        [
                            'status' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }

        echo "Exam subjects, levels, and school classes assigned successfully.\n";
    }
}
