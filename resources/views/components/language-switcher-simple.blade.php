@php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

$currentLocale = LaravelLocalization::getCurrentLocale();
$supportedLocales = LaravelLocalization::getSupportedLocales();
@endphp

<div class="language-switcher-simple">
    @foreach($supportedLocales as $localeCode => $properties)
        @if($localeCode === $currentLocale)
            <span class="language-link active">{{ strtoupper($localeCode) }}</span>
        @else
            <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" 
               class="language-link">{{ strtoupper($localeCode) }}</a>
        @endif
        
        @if(!$loop->last)
            <span class="separator">|</span>
        @endif
    @endforeach
</div>

<style>
.language-switcher-simple {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 500;
}

.language-link {
    color: #6b7280;
    text-decoration: none;
    transition: color 0.2s ease;
}

.language-link:hover {
    color: #374151;
}

.language-link.active {
    color: #1f2937;
    font-weight: 600;
}

/* Stili per footer scuro */
footer .language-link {
    color: #d1d5db;
}

footer .language-link:hover {
    color: #f9fafb;
}

footer .language-link.active {
    color: #ffffff;
    font-weight: 600;
}

footer .separator {
    color: #6b7280;
}

.separator {
    color: #d1d5db;
    user-select: none;
}
</style>