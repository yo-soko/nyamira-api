<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Term;

class ExamsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Get the TERM 2 entry (make sure it's already seeded before this)
        $term = Term::where('term_name', 'TERM 2')->where('year', 2025)->first();

        if ($term) {
            $exams = [
                ['name' => 'First CAT', 'term_id' => $term->id, 'is_analysed' => 1, 'status' => 1,'user_id'=>1],
                ['name' => 'Mid Term', 'term_id' => $term->id, 'is_analysed' => 0, 'status' => 1,'user_id'=>1],
                ['name' => 'End Term', 'term_id' => $term->id, 'is_analysed' => 1, 'status' => 1,'user_id'=>1],
            ];

            foreach ($exams as $exam) {
                Exam::updateOrCreate(
                    ['name' => $exam['name'], 'term_id' => $term->id],
                    $exam
                );
            }
        } else {
            echo "TERM 2 (2025) not found. Please run TermsTableSeeder first.\n";
        }
    }
}
