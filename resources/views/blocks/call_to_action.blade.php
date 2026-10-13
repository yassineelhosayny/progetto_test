{{-- Call to Action Block Component --}}
<section class="cta-block py-20 {{ $block['data']['background_class'] ?? 'bg-gradient-to-r from-blue-600 to-purple-600' }}">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            {{-- Main Title --}}
            @if(isset($block['data']['title']) && $block['data']['title'])
                <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6">
                    {{ $block['data']['title'] }}
                </h2>
            @endif

            {{-- Subtitle/Description --}}
            @if(isset($block['data']['subtitle']) && $block['data']['subtitle'])
                <p class="text-xl lg:text-2xl text-white/90 mb-12 max-w-3xl mx-auto">
                    {{ $block['data']['subtitle'] }}
                </p>
            @endif

            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                {{-- Primary Button --}}
                @if(isset($block['data']['primary_button']) && is_array($block['data']['primary_button']))
                    <a href="{{ $block['data']['primary_button']['actual_url'] ?? '#' }}" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 font-bold text-lg rounded-lg hover:bg-gray-100 transition-all duration-300 shadow-lg">
                        {{ $block['data']['primary_button']['title'] ?? 'Get Started' }}
                        <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif

                {{-- Secondary Button --}}
                @if(isset($block['data']['secondary_button']) && is_array($block['data']['secondary_button']))
                    <a href="{{ $block['data']['secondary_button']['actual_url'] ?? '#' }}" 
                       class="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-white font-bold text-lg rounded-lg hover:bg-white hover:text-blue-600 transition-all duration-300">
                        {{ $block['data']['secondary_button']['title'] ?? 'Learn More' }}
                    </a>
                @endif
            </div>

            {{-- Additional Info --}}
            @if(isset($block['data']['note']) && $block['data']['note'])
                <p class="text-white/70 text-sm mt-8">
                    {{ $block['data']['note'] }}
                </p>
            @endif
        </div>
    </div>
</section>