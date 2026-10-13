@php
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

$currentLocale = LaravelLocalization::getCurrentLocale();
$supportedLocales = LaravelLocalization::getSupportedLocales();
@endphp

<div class="language-switcher">
    <div class="dropdown">
        <button class="language-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="flag flag-{{ $currentLocale }}"></span>
            <span class="language-name">{{ $supportedLocales[$currentLocale]['native'] }}</span>
            <svg class="dropdown-arrow" width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        
        <ul class="dropdown-menu">
            @foreach($supportedLocales as $localeCode => $properties)
                @if($localeCode !== $currentLocale)
                    <li>
                        <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            <span class="flag flag-{{ $localeCode }}"></span>
                            <span class="language-name">{{ $properties['native'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>

<style>
.language-switcher {
    position: relative;
    display: inline-block;
}

.language-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.language-btn:hover {
    border-color: #d1d5db;
    background: #f9fafb;
}

.flag {
    width: 20px;
    height: 15px;
    border-radius: 2px;
    background-size: cover;
    background-position: center;
    flex-shrink: 0;
}

.flag-it {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjEiIGhlaWdodD0iMTUiIHZpZXdCb3g9IjAgMCAyMSAxNSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjIxIiBoZWlnaHQ9IjE1IiBmaWxsPSIjMDA5MjQ2Ii8+CjxyZWN0IHg9IjciIHdpZHRoPSI3IiBoZWlnaHQ9IjE1IiBmaWxsPSIjRkZGRkZGIi8+CjxyZWN0IHg9IjE0IiB3aWR0aD0iNyIgaGVpZ2h0PSIxNSIgZmlsbD0iI0NEMjEyQSIvPgo8L3N2Zz4K');
}

.flag-en {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjEiIGhlaWdodD0iMTUiIHZpZXdCb3g9IjAgMCAyMSAxNSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjIxIiBoZWlnaHQ9IjE1IiBmaWxsPSIjMDEyMTY5Ii8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMCAwTDIxIDBWMUgwVjBaTTAgMkwyMSAyVjNIMFYyWk0wIDRMMjEgNFY1SDBWNFpNMCA2TDIxIDZWN0gwVjZaTTAgOEwyMSA4VjlIMFY4Wk0wIDEwTDIxIDEwVjExSDBWMTBaTTAgMTJMMjEgMTJWMTNIMFYxMlpNMCAxNEwyMSAxNFYxNUgwVjE0WiIgZmlsbD0id2hpdGUiLz4KPHJlY3Qgd2lkdGg9IjkiIGhlaWdodD0iOCIgZmlsbD0iIzAxMjE2OSIvPgo8cGF0aCBkPSJNMC41IDBMOC41IDdNOC41IDBMMC41IDciIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMSIvPgo8cGF0aCBkPSJNMC41IDBMOC41IDdNOC41IDBMMC41IDciIHN0cm9rZT0iI0VGMUEyQSIgc3Ryb2tlLXdpZHRoPSIwLjYiLz4KPHBhdGggZD0iTTQuNSAwVjhNMCA0SDkiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMS42Ii8+CjxwYXRoIGQ9Ik00LjUgMFY4TTAgNEg5IiBzdHJva2U9IiNFRjFBMkEiIHN0cm9rZS13aWR0aD0iMSIvPgo8L3N2Zz4K');
}

.dropdown-arrow {
    transition: transform 0.2s ease;
}

.dropdown[open] .dropdown-arrow {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    margin-top: 4px;
    padding: 4px 0;
    list-style: none;
    z-index: 1000;
    min-width: 150px;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    color: #374151;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f3f4f6;
    color: #374151;
}

/* Bootstrap integration - se non usi Bootstrap, rimuovi questa parte */
@media (min-width: 768px) {
    .dropdown-menu {
        display: none;
    }
    
    .dropdown:hover .dropdown-menu {
        display: block;
    }
}

/* Versione senza Bootstrap */
.language-switcher .dropdown {
    position: relative;
}

.language-switcher .dropdown-menu {
    display: none;
}

.language-switcher .dropdown:hover .dropdown-menu {
    display: block;
}
</style>

<script>
// JavaScript per il toggle del dropdown (se non usi Bootstrap)
document.addEventListener('DOMContentLoaded', function() {
    const languageBtn = document.querySelector('.language-btn');
    const dropdown = document.querySelector('.language-switcher .dropdown');
    
    if (languageBtn && dropdown) {
        languageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            dropdown.classList.toggle('open');
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) {
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            }
        });
        
        // Chiudi il dropdown quando si clicca fuori
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
                const menu = dropdown.querySelector('.dropdown-menu');
                if (menu) {
                    menu.style.display = 'none';
                }
            }
        });
    }
});
</script>