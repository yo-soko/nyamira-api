<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeeStructure;

class FeeStructureSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['description' => 'Fees & 300 Assessment', 'amount' => 6800.00, 'level_id' => 1, 'term_id' => 1, 'status' => 1],
            ['description' => 'Fees & 300 Assessment', 'amount' => 6800.00, 'level_id' => 2, 'term_id' => 1, 'status' => 1],
            ['description' => 'Fees & 300 Assessment', 'amount' => 6800.00, 'level_id' => 3, 'term_id' => 1, 'status' => 1],
            ['description' => 'Fees & 300 Assessment', 'amount' => 6900.00, 'level_id' => 4, 'term_id' => 2, 'status' => 1],
            ['description' => 'Fees & 300 Assessment', 'amount' => 6900.00, 'level_id' => 1, 'term_id' => 2, 'status' => 1],
            ['description' => 'Fees & 300 Assessment', 'amount' => 6900.00, 'level_id' => 2, 'term_id' => 2, 'status' => 1],
            ['description' => 'Fees & 300 Assessment', 'amount' => 6900.00, 'level_id' => 3, 'term_id' => 2, 'status' => 1],
        ];

        foreach ($data as $entry) {
            FeeStructure::create($entry);
        }
    }
}
