{{-- Hero Block Component --}}
<section class="hero-block relative">
    @if(isset($block['data']['slides']) && is_array($block['data']['slides']) && count($block['data']['slides']) > 0)
        {{-- Multiple slides with content and images --}}
        <div class="swiper swiper--hero slider-item" data-slider-index="0">
            <div class="swiper-wrapper">
                @foreach($block['data']['slides'] as $index => $slide)
                    <div class="swiper-slide relative">
                        {{-- Background Image --}}
                        @if(isset($slide['background_image']) && $slide['background_image'])
                            <div class="absolute inset-0 z-0">
                                <img src="{{ Storage::url($slide['background_image']) }}" 
                                     alt="{{ $slide['title'] ?? 'Hero Image ' . ($index + 1) }}" 
                                     class="w-full h-full object-cover">
                            </div>
                        @endif
                        
                        {{-- Overlay --}}
                        @if(isset($slide['overlay']) && $slide['overlay'])
                            <div class="absolute inset-0 z-5 bg-black/30"></div>
                        @endif
                        
                        {{-- Content --}}
                        <div class="relative z-10 min-h-screen flex">
                            <div class="container mx-auto animate hidden lg:block" >
                                @if(isset($slide['title']) && $slide['title'])
                                    <div class="font-bold slide-title relative animate-top">
                                        {!! $slide['title'] !!}
                                        <svg class="icon icon--plus fill-[#121C25] absolute -top-10 -left-10">
                                            <use xlink:href="#plus"></use>
                                        </svg>
                                    </div>
                                @endif

                                @if(isset($slide['subtitle']) && $slide['subtitle'])
                                    <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90">
                                        {{ $slide['subtitle'] }}
                                    </p>
                                @endif

                                @if(isset($slide['cta_text']) && $slide['cta_text'] && isset($slide['cta_link']) && $slide['cta_link'])
                                    <div class="mt-8">
                                        <a href="{{ $slide['cta_link'] }}" 
                                           class="inline-block bg-white text-gray-900 font-bold py-4 px-8 rounded-lg text-lg hover:bg-gray-100 transition-colors duration-300 shadow-lg">
                                            {{ $slide['cta_text'] }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Navigation buttons --}}
            @if(count($block['data']['slides']) > 1)
                <div class="navigation absolute top-1/2 transform -translate-y-1/2 w-full flex justify-between px-4 z-20">
                    <button class="prev cursor-pointer bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full p-3 transition-colors duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button class="next cursor-pointer bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-full p-3 transition-colors duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            @endif
            
            {{-- Dots indicator --}}
            <!-- @if(count($block['data']['slides']) > 1) -->
                <div class="swiper-pagination">
                    <!-- @foreach($block['data']['slides'] as $index => $slide) -->
                        <!-- <div class="swiper-pagination-bullet" -->
                             <!-- data-slide="{{ $index }}"></div> -->
                    <!-- @endforeach -->
                </div>
            <!-- @endif -->
        </div>
    @endif
</section>