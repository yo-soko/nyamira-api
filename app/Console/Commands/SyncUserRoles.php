<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SyncUserRoles extends Command
{
    protected $signature = 'users:sync-roles';

    protected $description = 'Sync existing users.role field to Spatie roles';

    public function handle()
    {
        $users = User::all();

        $this->info("Starting to sync roles for {$users->count()} users...");

        foreach ($users as $user) {
            if ($user->role) {
                $user->syncRoles([$user->role]);
                $this->line("User {$user->email} assigned role: {$user->role}");
            } else {
                $this->line("User {$user->email} has no role assigned.");
            }
        }

        $this->info("Sync complete!");
        return 0;
    }
}
