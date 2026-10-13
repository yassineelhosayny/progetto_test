<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Modelli inclusi nella ricerca frontend
    |--------------------------------------------------------------------------
    |
    | Aggiungi qui le classi dei modelli che vuoi rendere cercabili
    | nella barra di ricerca pubblica del sito.
    |
    */

    'models' => [
        App\Models\Page::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Risultati per pagina
    |--------------------------------------------------------------------------
    */

    'per_page' => 12,
];