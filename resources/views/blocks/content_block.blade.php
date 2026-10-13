{{-- Content Block Component --}}
<section class="content-block py-16 {{ $block['data']['background_class'] ?? 'bg-white' }}">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            {{-- Pre-title --}}
            @if(isset($block['data']['pretitle']) && $block['data']['pretitle'])
                <span class="inline-block text-sm font-semibold text-blue-600 uppercase tracking-wide mb-4">
                    {{ $block['data']['pretitle'] }}
                </span>
            @endif

            {{-- Main Title --}}
            @if(isset($block['data']['title']) && $block['data']['title'])
                <h2 class="text-3xl lg:text-5xl font-bold text-gray-900 mb-6">
                    {{ $block['data']['title'] }}
                </h2>
            @endif

            {{-- Subtitle --}}
            @if(isset($block['data']['subtitle']) && $block['data']['subtitle'])
                <h3 class="text-xl lg:text-2xl text-gray-600 mb-8">
                    {{ $block['data']['subtitle'] }}
                </h3>
            @endif

            {{-- Main Content --}}
            @if(isset($block['data']['content']) && $block['data']['content'])
                <div class="prose prose-xl mx-auto text-gray-700 mb-12">
                    {!! $block['data']['content'] !!}
                </div>
            @endif

            {{-- Statistics/Numbers Grid --}}
            @if(isset($block['data']['stats']) && is_array($block['data']['stats']) && count($block['data']['stats']) > 0)
                <div class="grid md:grid-cols-{{ min(count($block['data']['stats']), 4) }} gap-8 mb-12">
                    @foreach($block['data']['stats'] as $stat)
                        <div class="text-center">
                            @if(isset($stat['number']))
                                <div class="text-4xl lg:text-5xl font-bold text-blue-600 mb-2">
                                    {{ $stat['number'] }}
                                </div>
                            @endif
                            @if(isset($stat['label']))
                                <div class="text-gray-600 font-medium">
                                    {{ $stat['label'] }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Call to Action Buttons --}}
            @if(isset($block['data']['buttons']) && is_array($block['data']['buttons']))
                <div class="flex flex-wrap justify-center gap-4">
                    @foreach($block['data']['buttons'] as $button)
                        <x-button :data="$button" />
                    @endforeach
                </div>
            @elseif(isset($block['data']['button']) && is_array($block['data']['button']))
                <div class="flex justify-center">
                    <x-button :data="$block['data']['button']" />
                </div>
            @endif
        </div>
    </div>
</section>