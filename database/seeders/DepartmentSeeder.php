<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            "Finance Economic Planning & Resource Mobilization",
            "Transport, Roads and Public Works",
            "Trade, Industry, Tourism and Cooperative",
            "Environment, Water, Energy and Natural Resources",
            "Agriculture, Livestock and Fisheries",
            "Education, ICT and Vocational Training",
            "Gender, Sports, Culture & Social Services",
            "Health Services",
            "Public Service Management",
            "Lands Housing and Urban Development",
            "Governor's & Executive",
        ];

        foreach ($departments as $dept) {
            DB::table('departments')->updateOrInsert(
                ['name' => $dept],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
