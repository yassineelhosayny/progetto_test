{{-- Two Column Block Component --}}
<section class="two-column-block py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Left Column --}}
            <div class="order-2 lg:order-1 animate">
                @if(isset($block['data']['title']) && $block['data']['title'])
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6 animate-top">
                        {{ $block['data']['title'] }}
                    </h2>
                @endif

                @if(isset($block['data']['subtitle']) && $block['data']['subtitle'])
                    <h3 class="text-xl text-gray-600 mb-6 animate-bottom">
                        {{ $block['data']['subtitle'] }}
                    </h3>
                @endif

                @if(isset($block['data']['content']) && $block['data']['content'])
                    <div class="prose prose-lg text-gray-700 mb-8 animate-fade">
                        {!! $block['data']['content'] !!}
                    </div>
                @endif

                {{-- CTA Button --}}
                @if(isset($block['data']['button']) && is_array($block['data']['button']))
                    <div class="mt-8 animate-left">
                        <x-button :data="$block['data']['button']" />
                    </div>
                @endif

                {{-- Multiple Buttons --}}
                @if(isset($block['data']['buttons']) && is_array($block['data']['buttons']))
                    <div class="flex flex-wrap gap-4 mt-8">
                        @foreach($block['data']['buttons'] as $button)
                            <x-button :data="$button" />
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Right Column - Image --}}
            <div class="order-1 lg:order-2 animate">
                @if(isset($block['data']['image']) && $block['data']['image'])
                    <div class="relative animate-bottom">
                        <x-image :src="$block['data']['image']" 
                                 :alt="$block['data']['image_alt'] ?? $block['data']['title'] ?? 'Image'"
                                 class="w-full h-auto rounded-lg shadow-lg" />
                        
                        {{-- Optional Image Overlay Text --}}
                        @if(isset($block['data']['image_overlay']) && $block['data']['image_overlay'])
                            <div class="absolute inset-0 bg-black bg-opacity-40 rounded-lg flex items-center justify-center">
                                <p class="text-white text-lg font-medium text-center px-4">
                                    {{ $block['data']['image_overlay'] }}
                                </p>
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Placeholder when no image --}}
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-gray-500">Image placeholder</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>