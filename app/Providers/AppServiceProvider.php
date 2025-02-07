<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Ajusta el tiempo de ejecución y el límite de memoria
        set_time_limit(0); // Permite que el script se ejecute indefinidamente
        ini_set('memory_limit', '512M'); // Aumenta la memoria disponible a 512M
    }
}
