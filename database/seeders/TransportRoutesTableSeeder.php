<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportRoutesTableSeeder extends Seeder
{
    public function run()
    {
        $routes = [
            ['route_name' => 'Jogoo, Getare, Nyankongo', 'fee' => 5000.00, 'status' => 1],
            ['route_name' => 'Motemomwamu, Mogonchoro', 'fee' => 8000.00, 'status' => 1],
            ['route_name' => 'Moitunya, Kemera', 'fee' => 10000.00, 'status' => 1],
            ['route_name' => 'Bosigisa, Cocacola, Kari, Kisii School', 'fee' => 5000.00, 'status' => 1],
            ['route_name' => 'Mailimbili, Omoremi', 'fee' => 6000.00, 'status' => 1],
            ['route_name' => 'Bobaracho, Menyikwa, Christamarianne', 'fee' => 7000.00, 'status' => 1],
            ['route_name' => 'Gusii Highlights, Gekomu, Mwembe, Chitangi', 'fee' => 6000.00, 'status' => 1],
            ['route_name' => 'Daraja Mbili, Nyanchwa, Suneka Junction', 'fee' => 7000.00, 'status' => 1],
            ['route_name' => 'St Jude, Egesa, Inner Getare, Ongwae Bridge', 'fee' => 6000.00, 'status' => 1],
            ['route_name' => 'Nyamataro, Kanunda, Imperial', 'fee' => 8000.00, 'status' => 1],
            ['route_name' => 'Embassy, Nyakoe, Mosocho', 'fee' => 9000.00, 'status' => 1],
        ];

        DB::table('transport_routes')->insert($routes);
    }
}
