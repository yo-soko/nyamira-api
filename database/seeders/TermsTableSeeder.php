<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Term;

class TermsTableSeeder extends Seeder
{
    public function run(): void
    {
        Term::create([
            'term_name'   => 'TERM 2',
            'year'        => 2025,
            'start_date'  => '2025-04-28',
            'end_date'    => '2025-10-24',
            'status'      => 1,
        ]);
    }
}
