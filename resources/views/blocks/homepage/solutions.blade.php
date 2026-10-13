@php
$component = $block['data'] ?? [];
    $title = $component['title'] ?? 'SOLUTIONS';
    $subtitle = $component['subtitle'] ?? '';
    $description = $component['description'] ?? '';
    $colorScheme = $component['color_scheme'] ?? '#49aaae';
    $secondaryColor = $colorScheme == '#49aaae' ? '#fff' : '#49aaae';
    $bgColor = $secondaryColor == '#49aaae' ? 'transparent' : '#49aaae';
    $image = $component['image'] ?? null;

    // var_dump($component);
@endphp

<section class="solutions-section pb-17 lg:pt-17 relative" style="--primary-color: {{ $colorScheme }}; --secondary-color: {{ $secondaryColor }}; 
    --bg-color: {{ $bgColor }};">
    {{-- Decorative Background Elements --}}
    <div class="container">
        <!-- Header Section -->
        <div class="relative mb-12 px-6 lg:px-4">
            @if($title)
                <div class="mb-4">
                    <h4 class="uppercase" style="color: var(--secondary-color);">
                        {{-- First word highlighted --}}
                        {{ $title }}
                    </h4>
                </div>
            @endif
            
            @if($subtitle)
                <div class="mb-4">
                    <h6>{{ $subtitle }}</h6>
                </div>
            @endif
            
            @if($description)
                <div class="">
                    {!! $description !!}
                </div>
            @endif

            <svg class="icon icon--plus absolute -top-10 -left-3 lg:-left-10" style="fill: var(--secondary-color);">
                <use xlink:href="#plus"></use>
            </svg>
        </div>

        <!-- Solutions Diagram Image -->
        @if($image)
            <div class="flex justify-center">
                <div class="max-w-4xl w-full">
                    <img src="{{ Storage::url($image) }}" 
                         alt="Solutions Diagram" 
                         class="w-full h-auto object-contain">
                </div>
            </div>
        @endif
    </div>
</section>
