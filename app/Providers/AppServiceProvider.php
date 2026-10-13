<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use App\Models\ResourceGenerator;
use App\Observers\ResourceGeneratorObserver;
use App\Services\GeneratedFilesAutoloader;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Defer potentially expensive/FS/DB-related registrations until the framework is fully booted.
        $this->app->booted(function () {
            // Register observer only after boot and only if the table exists.
            try {
                if (Schema::hasTable((new ResourceGenerator)->getTable())) {
                    ResourceGenerator::observe(ResourceGeneratorObserver::class);
                }
            } catch (Throwable $e) {
                // swallow: during some artisan commands DB may not be available
            }

            try {
                if (app()->environment('production') || !is_writable(app_path())) {
                    GeneratedFilesAutoloader::register();
                }
            } catch (Throwable $e) {
                // Swallow errors during early bootstrap (e.g. package discovery)
            }
        });

        // Repository binding (safe to bind now)
        $this->app->bind(
            \App\Repositories\GeneratedResourceRepositoryInterface::class,
            \App\Repositories\GeneratedResourceRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Livewire component alias for the published MenuBuilder so
        // views that mount 'menu-builder' use our App Livewire class.
        try {
            Livewire::component('menu-builder', \App\Http\Livewire\MenuBuilder::class);
        } catch (\Throwable $e) {
            // Ignore when Livewire isn't available (artisan commands during early bootstrap)
        }
        try {
            Livewire::component('menu-item-form', \App\Http\Livewire\MenuItemForm::class);
        } catch (\Throwable $e) {
            // Ignore when Livewire isn't available
        }
    }
}
