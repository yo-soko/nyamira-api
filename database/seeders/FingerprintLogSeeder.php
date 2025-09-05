<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\zkteco_logs;
use Carbon\Carbon;

class FingerprintLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = range(1, 50); // assuming you have 50 users

        for ($i = 0; $i < 1000; $i++) {
            $userId = $users[array_rand($users)];
            $logDate = Carbon::now()->subDays(rand(0, 30));
            
            $pickupTime = $logDate->copy()->setTime(rand(6, 9), rand(0, 59)); // between 6:00 - 9:59
            $dropoffTime = $pickupTime->copy()->addHours(rand(6, 10)); // 6-10 hrs later

            zkteco_logs::create([
                'user_id' => $userId,
                'log_date' => $logDate->toDateString(),
                'pickup_time' => $pickupTime,
                'dropoff_time' => $dropoffTime,
                'last_synced_at' => Carbon::now()->subMinutes(rand(0, 60)),
            ]);
        }
    }
}
