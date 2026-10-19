<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Support\HtmlString;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Enums\Width;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use Biostate\FilamentMenuBuilder\FilamentMenuBuilderPlugin;
use App\Models\Menu;
use App\Models\MenuItem;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        // Use config defaults only to avoid database access during early bootstrap
        $siteName = config('filament.site_name', config('app.name'));
        $logoUrl = config('filament.site_logo') ?? null;

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(fn () => $logoUrl ? new HtmlString('<img src="' . $logoUrl . '" alt="Logo" class="h-10 w-auto">') : null)
            ->brandName(fn () => $siteName)
            ->colors([
                'primary' => Color::Rose,
            ])
            ->maxContentWidth(Width::Full)
            ->sidebarWidth('16rem')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'Video Demo',
                'Contenuti',
                'Amministrazione',
                'Filament Shield',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\Filament\Clusters')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                EasyFooterPlugin::make()
                    ->withSentence($siteName)
                    ->withLogo(
                        asset('images/adverteaser-logo-magenta.svg'),
                        'https://www.adverteaser.com',
                        'AdminCP Filament è realizzato da',
                        18
                    ),
                // Use the package models so the built-in nestedset and drag-&-drop
                // editor work out of the box. If you prefer to use App models,
                // switch these to your classes or remove the chaining.
                FilamentMenuBuilderPlugin::make()
                    ->usingMenuModel(Menu::class)
                    ->usingMenuItemModel(MenuItem::class)
                    ->usingMenuResource(\App\Filament\Resources\MenusBuilder\MenuResource::class)
                    ->usingMenuItemResource(\App\Filament\Resources\MenusBuilder\MenuItemResource::class),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
