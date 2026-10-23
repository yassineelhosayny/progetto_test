<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Utenti amministrativi per lo sviluppo locale
    |--------------------------------------------------------------------------
    |
    | Le password rimangono nel file .env, che non viene salvato su Git.
    | Questi valori vengono utilizzati esclusivamente dal seeder locale.
    |
    */
    'super_admin' => [
        'name' => env('LOCAL_SUPER_ADMIN_NAME', 'Super'),
        'lastname' => env('LOCAL_SUPER_ADMIN_LASTNAME', 'Admin'),
        'email' => env('LOCAL_SUPER_ADMIN_EMAIL', 'superadmin@videodemo.test'),
        'password' => env('LOCAL_SUPER_ADMIN_PASSWORD'),
    ],

    'admin' => [
        'name' => env('LOCAL_ADMIN_NAME', 'Admin'),
        'lastname' => env('LOCAL_ADMIN_LASTNAME', 'VideoDemo'),
        'email' => env('LOCAL_ADMIN_EMAIL', 'admin@videodemo.test'),
        'password' => env('LOCAL_ADMIN_PASSWORD'),
    ],
];
