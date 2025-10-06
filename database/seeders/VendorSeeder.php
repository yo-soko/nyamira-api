<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;

/**
 * Run the database seeds.
 */
class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            'Total Energies',
            'Rubis',
            'Easton',
            'Nyagi',
            'Shell',
            'Penol',
            'National Oil',
            'Aftah',
        ];

        foreach ($vendors as $name) {
            Vendor::updateOrCreate(['name' => $name]);
        }
    }
}

