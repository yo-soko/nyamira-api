<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; 

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')-> insert([
		'name' => 'Solomon Batasi',
		'code' => 'Dev001',
		'email' => 'info@javapa.com',
    	'password' => Hash::make('12345678'),
		'role' => 'developer',
		'status' => true,
		'created_at' => now(),
		'updated_at' => now(),
]);

}
}
