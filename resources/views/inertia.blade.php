<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @php
        use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
        use App\Models\Settings;

        $settings = Settings::getSettings();
        $logoUrl = $settings->getLogoUrl();
        $faviconUrl = $settings->getFaviconUrl();
        $siteName = $settings->site_name ?? config('app.name', 'Laravel');

        $pageData = $page['props']['page'] ?? [];
        $locale = LaravelLocalization::getCurrentLocale() ?: app()->getLocale();
        $getLocalizedValue = function ($value) use ($locale) {
            if (is_array($value)) {
                return $value[$locale] ?? '';
            }

            return is_string($value) ? $value : '';
        };
        $pageTitle = $getLocalizedValue($pageData['title'] ?? '');
        $metaTitle = $getLocalizedValue($pageData['meta_title'] ?? null)
            ?: $pageTitle
            ?: $siteName;
        $metaDescription = $getLocalizedValue($pageData['meta_description'] ?? '');
        $metaKeywords = $getLocalizedValue($pageData['meta_keywords'] ?? '');

        $ogUrl = url()->current();
    @endphp
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- SEO -->
        <title>{{ $metaTitle }}</title>
        @if($metaDescription)
            <meta name="description" content="{{ $metaDescription }}">
        @endif
        @if($metaKeywords)
            <meta name="keywords" content="{{ $metaKeywords }}">
        @endif

        <!-- Open Graph -->
        <meta property="og:title" content="{{ $metaTitle }}">
        @if($metaDescription)
            <meta property="og:description" content="{{ $metaDescription }}">
        @endif
        <meta property="og:url" content="{{ $ogUrl }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $siteName }}">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Fonts -->
        @include('components.fonts')

        <!-- Icons -->
        @include('components.favicons')

        @vite(['resources/js/inertia.ts', 'resources/css/master.css', 'resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @include('components.menu')
        @inertia
    </body>
</html>
