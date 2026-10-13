{{-- Hero Section Block Component (existing format) --}}
<section class="hero-section-block bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        @if(isset($block['data']['title']) && $block['data']['title'])
            <div class="text-center max-w-4xl mx-auto">
                <h2 class="text-4xl font-bold text-gray-900 mb-6">
                    {{ $block['data']['title'] }}
                </h2>
                
                @if(isset($block['data']['subtitle']) && $block['data']['subtitle'])
                    <p class="text-xl text-gray-600 mb-8">
                        {{ $block['data']['subtitle'] }}
                    </p>
                @endif

                @if(isset($block['data']['content']) && $block['data']['content'])
                    <div class="prose prose-lg mx-auto">
                        {!! $block['data']['content'] !!}
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>