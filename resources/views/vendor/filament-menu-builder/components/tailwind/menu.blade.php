@php 
use App\Models\Settings;
$items = is_iterable($menuItems) ? $menuItems : []; 
        
$settings = Settings::getSettings();
$logoUrl = $settings->getLogoUrl();
$faviconUrl = $settings->getFaviconUrl();
$siteName = $settings->site_name ?? config('app.name', 'Laravel');
@endphp
<div class="nav-wrapper h-full p-8">
    <div class="nav-header flex justify-between pb-8">
        <a href="{{ LaravelLocalization::localizeUrl('/') }}" class="flex items-center space-x-3">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-auto">
            @else
                <span class="text-xl font-bold text-gray-900">{{ $siteName }}</span>
            @endif
        </a>
        
        @svg('heroicon-o-x-mark', 'w-8 h-8 text-black cross')
    </div>
    <ul class="flex-col">
        @foreach($items as $menuItem)
            @include('filament-menu-builder::components.tailwind.menu-item', ['item' => $menuItem, 'lgClasses' => $lgClasses = 'lg:w-0 lg:p-5 lg:invisible lg:h-auto lg:border-[#494e51] lg:border lg:group-hover:z-10 lg:group-hover:opacity-100 lg:group-hover:visible'])
        @endforeach
    </ul>
</div>
