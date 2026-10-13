{{-- Banner Block Component --}}
<section class="banner-block relative pb-16">

    <div class="container mx-auto relative">

        <div class="bg-white">
            <div class="content py-12 lg:py-12">

                {{-- Testo formattato dall'editor --}}
                @if(isset($block['data']['banner_editor']) && $block['data']['banner_editor'])
                    <div class="text-blue-darker px-12 lg:px-0">
                        <p>{!! $block['data']['banner_editor'] !!}</p>
                    </div>
                @endif

                {{-- Loghi --}}
                <div class="flex flex-col mt-2 gap-4 px-12 lg:px-0 lg:gap-16 lg:flex-row">
                    @if(isset($block['data']['banner_image_1']) && $block['data']['banner_image_1'])
                        <div class="flex-shrink-0">
                            <img src="{{ Storage::url($block['data']['banner_image_1']) }}" alt="Brand Logo 1"
                                class="h-[75px] w-auto object-contain opacity-80 hover:opacity-100 transition-opacity duration-300">
                        </div>
                    @endif

                    @if(isset($block['data']['banner_image_2']) && $block['data']['banner_image_2'])
                        <div class="flex-shrink-0">
                            <img src="{{ Storage::url($block['data']['banner_image_2']) }}" alt="Brand Logo 2"
                                class="h-[75px] w-auto object-contain opacity-80 hover:opacity-100 transition-opacity duration-300">
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>