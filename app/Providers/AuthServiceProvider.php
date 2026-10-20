<?php

namespace App\Providers;

use App\Models\Client;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\News;
use App\Policies\ClientPolicy;
use App\Policies\NewsPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Client::class => ClientPolicy::class,
        News::class => NewsPolicy::class,
        \App\Models\Menu::class => \App\Policies\MenuPolicy::class,
        \App\Models\Settings::class => \App\Policies\SettingsPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
