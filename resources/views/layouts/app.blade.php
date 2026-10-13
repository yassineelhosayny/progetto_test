<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
    use App\Models\Settings;
    
    $settings = Settings::getSettings();
    $logoUrl = $settings->getLogoUrl();
    $faviconUrl = $settings->getFaviconUrl();
    $siteName = $settings->site_name ?? config('app.name', 'Laravel');
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $settings->site_name ?? config('app.name', 'Laravel'))</title>
    {{-- <title>Filament</title> --}}
    @include('components.favicons')

    {{-- Meta Tags --}}

    @yield('meta')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/js/app.js', 'resources/css/master.css', 'resources/scss/app.scss'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

@php
    // Ottieni lo slug della pagina corrente dalla route, fallback a 'home' se non disponibile
    $currentSlug = request()->route('slug') ?? (request()->route()->getName() ?? 'home');
    // Rendi lo slug adatto come classe CSS (solo caratteri validi)
    $bodyClass = 'font-sans antialiased page-' . Str::slug($currentSlug);
@endphp
<body class="{{ $bodyClass }}">

    {{-- Dynamic Menu Component --}}
    @include('components.menu')

    {{-- Main Content --}}
    <main class="">
        <div class="main-content min-h-screen">
            @yield('content')
        </div>
    </main>
    <!-- <div class="fixed bottom-[50px] right-[50px] z-50">
        {{-- Language Switcher --}}
        <x-language-switcher />
    </div> -->
    @include('components.svg-symbols')
</body>
</html>