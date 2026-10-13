@php
    $title = $block['data']['title'] ?? '';
    $subtitle = $block['data']['subtitle'] ?? '';
    $description = $block['data']['description'] ?? '';
    $image = $block['data']['image'] ?? null; // Logo Acerbi
    $image_2 = $block['data']['image_2'] ?? null; // Logo Certificazione
@endphp

<section class="credits-section pt-0 pb-20 lg:pt-20 relative">
    <div class="container">
        <div class="flex flex-col gap-17 px-4">

            <!-- Acerbi Logo -->
            @if($image)
                <div class="first-image px-2 lg:px-0 lg:mx-auto">
                    <img src="{{ Storage::url($image) }}" alt="Acerbi Marine Solutions" class="w-full h-auto">
                </div>
            @endif

            <!-- Left Column - Content -->
            <div class="text-editors relative flex flex-col gap-8">
                @if($title)
                    <div class="text-blue-light">
                        {!! $title !!}
                    </div>
                @endif

                @if($description)
                    <div class="text-white">
                        {!! $description !!}
                    </div>
                @endif

                <svg class="icon icon--plus fill-[#49AAAE] absolute -top-9 -left-9 lg:-top-10 lg:-left-10">
                    <use xlink:href="#plus"></use>
                </svg>
            </div>


            <!-- Certification Logo -->
            @if($image_2)
                <div class="last-image mx-auto">
                    <img src="{{ Storage::url($image_2) }}" alt="CQY Certiquality" class="w-full h-auto object-contain">
                </div>
            @endif
        </div>
    </div>
</section>