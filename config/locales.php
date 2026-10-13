<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    | Define all supported locales for the application with their metadata.
    | This configuration is used to generate dynamic language tabs in forms
    | and manage translations throughout the application.
    |
    */
    
    'supported' => [
        'it' => [
            'name' => 'Italian',
            'native' => 'Italiano',
            'flag' => '🇮🇹',
            'default' => true,
            'required' => true, // Almeno una lingua deve essere richiesta
            'sort' => 1,
        ],
        'en' => [
            'name' => 'English',
            'native' => 'English', 
            'flag' => '🇬🇧',
            'default' => false,
            'required' => false,
            'sort' => 2,
        ],
        // Facilmente espandibile per il futuro:
        // 'fr' => [
        //     'name' => 'French',
        //     'native' => 'Français',
        //     'flag' => '🇫🇷',
        //     'default' => false,
        //     'required' => false,
        //     'sort' => 3,
        // ],
        // 'de' => [
        //     'name' => 'German', 
        //     'native' => 'Deutsch',
        //     'flag' => '🇩🇪',
        //     'default' => false,
        //     'required' => false,
        //     'sort' => 4,
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    | The default locale to use when no specific locale is requested.
    | This should match one of the locales defined above with default => true.
    |
    */
    'default' => 'it',

    /*
    |--------------------------------------------------------------------------
    | Fallback Locale
    |--------------------------------------------------------------------------
    | The fallback locale to use when a translation is missing.
    |
    */
    'fallback' => 'it',
];