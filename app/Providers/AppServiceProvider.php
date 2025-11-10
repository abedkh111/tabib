<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Illuminate\Database\Migrations\Migrator;

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
    public function boot(): void
    {
        // تعطيل هجرات Cashier التلقائية
        Cashier::ignoreMigrations();
        
        // منع تحميل هجرات vendor
        $this->app->afterResolving('migrator', function (Migrator $migrator) {
            $migrator->path(database_path('migrations'));
        });
    }
}
