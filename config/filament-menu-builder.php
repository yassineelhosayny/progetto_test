<?php

return [
    'models' => [
        // 'Menu' => 'App\\Models\\Menu',
        'MenuItem' => App\Models\MenuItem::class,
    ],
    'api_enabled' => true,
    'cache' => [
        'enabled' => true,
        'key' => 'filament-menu-builder',
        'ttl' => 60 * 60 * 24,
    ],
    'usable_parameters' => [
        // For example:
        // 'mega_menu',
        // 'mega_menu_columns',
    ],
    'exclude_route_names' => [
        '/^debugbar\./', // Exclude debugbar routes
        '/^filament\./',   // Exclude filament routes
        '/^livewire\./',   // Exclude livewire routes
    ],
    'exclude_routes' => [
        //
    ],
    'dto' => [
        'menu' => \Biostate\FilamentMenuBuilder\DTO\Menu::class,
        // Use app DTO to extend vendor DTO with app-specific fields
        'menu_item' => \App\DTO\MenuItem::class,
    ],
];
