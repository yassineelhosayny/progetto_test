<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // La configurazione è ora gestita dal file config/laravellocalization.php
        // e viene caricata automaticamente dal pacchetto
    }
}