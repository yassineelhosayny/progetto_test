{{-- Custom Section Block Component --}}
<section class="custom-block py-12">
    <div class="container mx-auto px-4">
        @if(isset($block['data']['title']) && $block['data']['title'])
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ $block['data']['title'] }}
                </h2>
                
                @if(isset($block['data']['subtitle']) && $block['data']['subtitle'])
                    <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                        {{ $block['data']['subtitle'] }}
                    </p>
                @endif
            </div>
        @endif

        @if(isset($block['data']['content']) && $block['data']['content'])
            <div class="max-w-4xl mx-auto">
                <div class="prose prose-lg mx-auto">
                    {!! $block['data']['content'] !!}
                </div>
            </div>
        @endif

        @if(isset($block['data']['image']) && $block['data']['image'])
            <div class="mt-8 text-center">
                <img src="{{ Storage::url($block['data']['image']) }}" 
                     alt="{{ $block['data']['title'] ?? '' }}" 
                     class="max-w-full h-auto rounded-lg shadow-lg mx-auto">
            </div>
        @endif
    </div>
</section>