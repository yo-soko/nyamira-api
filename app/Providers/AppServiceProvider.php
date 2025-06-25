<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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
            View::share('leaveTypess', \App\Models\LeaveType::where('status', 1)->get());
            View::share('classes', \App\Models\SchoolClass::where('status', 1)->get());
            View::share('exams', \App\Models\Exam::where('status', 1)->get());
            View::share('terms', \App\Models\Term::where('status', 1)->get());
            View::share('subjects', \App\Models\Subject::where('status', 1)->get());
            View::share('levels', \App\Models\ClassLevel::where('status', 1)->get());
            View::share('roless', \App\Models\Role::all());

        }
}
