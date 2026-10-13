<?php

use App\Http\Controllers\DispatcherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\GeneratedResourceController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProjectController;

// Route di fallback senza prefisso che reindirizza alla lingua italiana
Route::get('/', function () {
    return redirect('/en/');
})->name('root');

// Health check routes (senza localizzazione)
Route::get('/health/generator', [HealthController::class, 'generator'])->name('health.generator');
Route::get('/health/db', [HealthController::class, 'db'])->name('health.db');

// Route localizzate con prefissi lingua (/it/, /en/)
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localizationRedirect', 'localeSessionRedirect', 'localeCookieRedirect', 'localeViewPath']
], function () {
    // Homepage dinamica basata sulla pagina con is_homepage = true
    // Route::get('/', [PageController::class, 'homepage'])->name('homepage');

    // 🧪 ROUTE DI TEST: Homepage con Inertia/Vue (PoC)
    Route::get('/', [PageController::class, 'homepage'])->name('homepage.inertia');

    // Rotte News specifiche (devono essere prima delle route dinamiche)
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/categoria/{category}', [NewsController::class, 'category'])->name('news.category');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

    // Search route
    Route::get('/search', SearchController::class)->name('search.index');

    // Rotte Progetti specifiche (devono essere prima della route dinamica delle pagine)
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');

    // =================== GENERATED RESOURCES ROUTES ===================
    // Route per risorse generate dinamicamente
    // ATTENZIONE: Devono essere DOPO le route specifiche!

    // Dettaglio: /products/my-product, /events/my-event, etc.
    // Route::get('/{resource}/{slug}', [GeneratedResourceController::class, 'show'])
    //     ->name('generated.resource.show')
    //     ->where('resource', '^(?!news|admin|api|filament|dashboard|storage|livewire|up).*$')
    //     ->where('slug', '[a-zA-Z0-9\-_]+');

    // // API per ottenere tutte le risorse
    // Route::get('/api/resources', [GeneratedResourceController::class, 'apiIndex'])->name('api.resources');

    // // Route per invalidare cache (solo per admin)
    // Route::post('/admin/cache/invalidate', [GeneratedResourceController::class, 'invalidateCache'])
    //     ->middleware('auth')
    //     ->name('admin.cache.invalidate');

    // // Lista: /products, /events, etc.
    // Route::get('/{resource}', [GeneratedResourceController::class, 'index'])
    //     ->name('generated.resource.index')
    //     ->where('resource', '^(?!news|admin|api|filament|dashboard|storage|livewire|up).*$');

    // ⚠️ ATTENZIONE: Questa rotta catch-all deve essere SEMPRE L'ULTIMA!
    // Cattura tutti gli slug che non corrispondono alle rotte sopra
    // Il PageController verificherà se è una risorsa generata


    // Rotta dinamica per pagine statiche (deve essere l'ultima dichiarata, dopo tutte le rotte specifiche)
    Route::get('/qr', [DispatcherController::class, 'dispatch'])
        ->name('qr.dispatch');

    Route::get('/{slug}', [PageController::class, 'show'])
        ->name('pages.show')
        ->where('slug', '^(?!admin|api|filament|dashboard|storage|livewire|up).*$');
});
