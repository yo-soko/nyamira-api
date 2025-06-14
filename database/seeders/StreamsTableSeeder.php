<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Stream;
use Carbon\Carbon;

class StreamsTableSeeder extends Seeder
{
    public function run(): void
    {
        $streams = [
            ['initials' => 'Shinners', 'name' => 'Shinners', 'status' => 1 ],
            ['initials' => 'A', 'name' => 'play group A', 'status' => 1],
            ['initials' => 'B', 'name' => 'play group B', 'status' => 1],
            ['initials' => 'C', 'name' => 'play group C', 'status' => 1],
            ['initials' => 'Peak', 'name' => 'Peak', 'status' => 1],
            ['initials' => 'Pillars', 'name' => 'Pillars', 'status' => 1],
            ['initials' => 'Elites', 'name' => 'Elites', 'status' => 1],
            ['initials' => 'Winners', 'name' => 'Winners', 'status' => 1 ],
            ['initials' => 'Achivers', 'name' => 'Achivers', 'status' => 1 ],
            ['initials' => 'Lions', 'name' => 'Lions', 'status' => 1 ],
            ['initials' => 'Eagles', 'name' => 'Eagles', 'status' => 1],
        ];

        foreach ($streams as $stream) {
            Stream::create($stream);
        }
    }
}

