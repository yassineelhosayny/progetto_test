@php
    $menuId = $component['id'] ?? null;
    $menuName = $component['name'] ?? null;
    $menuLocation = $component['menu_location'] ?? null;
    $cssClasses = $component['css_classes'] ?? '';
    $orientation = $component['orientation'] ?? 'horizontal'; // horizontal | vertical
    $showIcons = $component['show_icons'] ?? true;
    $menu = null;
    // Prefer the Filament Menu Builder blade component when available.
    // It renders menus by slug (menu slug == name slug). Fall back to
    // the legacy Menu model logic if the component or menu is not present.
    $useMenuBuilder = class_exists(\Biostate\FilamentMenuBuilder\FilamentMenuBuilderPlugin::class) && $menuName;
    if (! $useMenuBuilder && $menuName && $menuLocation) {
        $menuModel = new \App\Models\Menu();
        $menu = $menuModel->getMenuItems($menuName, $menuLocation);
    }

@endphp
<header id="header-menu"
    class="fixed w-full flex items-center z-50 top-8">
    <div class="header-container w-full bg-white flex justify-between items-center border border-black rounded-4xl p-4">

        <div class="header-logo flex items-center">
            <a href="{{ LaravelLocalization::localizeUrl('/') }}" class="flex items-center space-x-3">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" class="h-auto">
                @else
                    <span class="text-xl font-bold text-gray-900">{{ $siteName }}</span>
                @endif
            </a>
        </div>
        <div class="nav-container">

            <nav class="fixed h-screen w-screen left-0 top-0 bg-white lg:w-[33.3333vw] lg:left-[unset] lg:right-0">
                <x-filament-menu-builder::menu 
                    slug="menu-principale" 
                    view="filament-menu-builder::components.tailwind.menu"
                />
            </nav>
            <!-- <form action="{{ route('search.index') }}" method="GET">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cerca..."
                    class="focus-visible:outline-none"
                >
                <button type="submit">Cerca</button>
            </form> -->
        </div>
        
        <!-- <div class="socials hidden lg:flex">
            <x-socials></x-socials>
        </div> -->
        
        <div class="toggle-buttons w-8 h-8 flex items-center relative">
            @svg('heroicon-o-bars-3', 'w-8 h-8 text-black absolute opacity-100 top-0 left-0 transition-opacity duration-300 hamburger')
        </div>
    </div>
</header>