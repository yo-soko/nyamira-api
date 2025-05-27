<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Blade::if('role', function ($role) {
            return session('user_type') === $role;
        });

        Blade::if('roles', function ($roles) {
            return in_array(session('user_type'), $roles);
        });

        View::share('departments', \App\Models\Department::where('status', 1)->get());
        
    }
}
