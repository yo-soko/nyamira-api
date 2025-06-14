<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guardian;

class GuardiansTableSeeder extends Seeder
{
    public function run(): void
    {
        Guardian::create([
            'student_id' => 1, // make sure this student exists
            'guardian_first_name' => 'Mary',
            'guardian_last_name' => 'Otieno',
            'guardian_relationship' => 'Mother',
            'first_phone' => '0712345678',
            'second_phone' => '0798765432',
            'address' => 'Kisii Town',
            'id_number' => '12345678',
            'email' => 'mary.otieno@example.com',
            'guardian_about' => 'Responsible parent actively involved in school matters.',
        ]);
    }
}

