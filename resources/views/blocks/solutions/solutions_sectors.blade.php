{{-- Sectors Block Component --}}
<section class="sectors-block relative overflow-hidden pt-25">

    <div class="container mx-auto px-4 relative z-10">

        {{-- Header con icona croce e titolo --}}
        <div class="indent mb-20 px-6">

           <div class="px-2 lg:px-0">
                {{-- Icona croce --}}
                @if(isset($block['data']['label']) && $block['data']['label'])
                    <div class="max-w-4xl uppercase text-white mb-6">
                        <h5 class="relative">
                            {{ $block['data']['label'] }}
                            <svg class="icon icon--plus fill-[#49AAAE] absolute -top-10 -left-10">
                                <use xlink:href="#plus"></use>
                            </svg>
                        </h5>
                    </div>
                @endif

                {{-- Titolo principale --}}
                @if(isset($block['data']['title']) && $block['data']['title'])
                    <div class="max-w-4xl">
                        <h3 class="text-blue-light">
                            {{ $block['data']['title'] }}
                        </h3>
                    </div>
                @endif
           </div>
        </div>

        {{-- Icone settori circolari --}}
        @if(isset($block['data']['sectors_images']) && is_array($block['data']['sectors_images']) && count($block['data']['sectors_images']) > 0)
            <div class="logos-container mb-16">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 mb-16">
                    @foreach($block['data']['sectors_images'] as $index => $sectorData)
                        @if(isset($sectorData['sector_image']) && $sectorData['sector_image'])
                            <div class="sector-item group text-center">
                                <div class="mx-auto mb-4 ">
                                    <img src="{{ Storage::url($sectorData['sector_image']) }}" alt="Settore {{ $index + 1 }}"
                                        class="w-full h-full">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Sezione descrizioni a due colonne --}}
        <div class="text-white indent mb-20">

            {{-- Colonna Sinistra --}}
            <div class="px-2 grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if(isset($block['data']['description_sx']) && $block['data']['description_sx'])
                    <div class="space-y-6">
                        <div class="">
                            {!! $block['data']['description_sx'] !!}
                        </div>
                    </div>
                @endif

                {{-- Colonna Destra --}}
                @if(isset($block['data']['description_dx']) && $block['data']['description_dx'])
                    <div class="space-y-6">
                        <div class="">
                            {!! $block['data']['description_dx'] !!}
                        </div>
                    </div>
                @endif
            </div>

        </div>

        {{-- Call to Action Section --}}
        @if(isset($block['data']['cta_text']) && $block['data']['cta_text'])
            <div class="text-center">

                {{-- CTA Text con simboli + --}}
                <h3 class="text-white text-2xl lg:text-3xl font-bold mb-8 tracking-wide">
                    {{ $block['data']['cta_text'] }}
                </h3>

                {{-- CTA Button --}}

                @if(is_array($block['data']['my_field']))
                    <div class="mt-8">
                        @include('components.button', ['data' => $block['data']['my_field']])
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>