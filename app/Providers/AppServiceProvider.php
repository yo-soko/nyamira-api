<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        Blade::directive('useronly', function () {
            return "<?php if (session('user_type') == 'user'): ?>";
        });
    
        Blade::directive('enduseronly', function () {
            return "<?php endif; ?>";
        });
    
        Blade::directive('employeeonly', function () {
            return "<?php if (session('user_type') == 'employee'): ?>";
        });
    
        Blade::directive('endemployeeonly', function () {
            return "<?php endif; ?>";
        });

        
    }
}
