@props(['item' => [] ,'lgClasses' => ''])

@php
    // Each $item is the package MenuItem model (has ->children relation)
    $hasChildren = isset($item->children) && $item->children->isNotEmpty();
    $link = $item->link ?? '#';
@endphp

    @if($hasChildren)

        <li class="sub-menu px-2 border-t border-[rgba(255,255,255,0.15)] lg:border-0 group lg:relative">
            @if($item->type_choice === 'placeholder')
                <div class="w-full sub-menu-opener">
                    <div class="label w-full flex justify-between items-center py-4 lg:gap-2 uppercase hover:underline hover:translate-x-1 transition-all duration-300 ease-in-out">
                    <h3>{{ $item->name }}</h3>

                    @svg('heroicon-s-chevron-down', 'w-3 h-3 text-white transition-transform duration-300 ease-in-out')</div>
                </div>
            @else
                <a href="{{ $item->url }}" class="w-full sub-menu-opener">
                    <div class="label w-full flex justify-between items-center py-4 lg:gap-2 uppercase hover:underline hover:translate-x-1 transition-all duration-300 ease-in-out">
                    <h3>{{ $item->name }}</h3>
                    @svg('heroicon-s-chevron-down', 'w-3 h-3 text-white transition-transform duration-300 ease-in-out')</div>
                </a>
            @endif
            <ul class="sub-menu-list h-0 opacity-0 transition-height duration-300 lg:absolute lg:w-[200px] {{ $lgClasses }}">
                <div class="sub-menu-body">
                    @foreach ($item->children as $child)
                        
                            @php
                                $lgClasses = 'lg:absolute lg:bg-primary lg:top-[-50%] lg:left-full lg:w-48 lg:h-auto menu-item-children';
                                // $subChildren = array_filter($menu['tertiary_items'], fn($subChild) => $subChild['parent_id'] == $child['id']);
                            @endphp
                            @include('filament-menu-builder::components.tailwind.menu-item', ['item' => $child, 'lgClasses' => $lgClasses])
                    @endforeach
                </div>
            </ul>
        </li>
    @else
        <li class="border-t border-[rgba(255,255,255,0.15)] lg:border-0">
            <a
                href="{{ $link }}"
                class="label w-full flex justify-between items-center py-4 lg:gap-2 uppercase hover:underline hover:translate-x-1 transition-all duration-300 ease-in-out"
            >
                <span><h3>{{ $item->name }}</h3></span>
            </a>
        </li>
    @endif
</li>
