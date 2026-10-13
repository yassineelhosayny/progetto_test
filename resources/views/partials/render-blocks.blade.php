{{-- resources/views/partials/render-blocks.blade.php --}}
{{-- Partial per il rendering dei blocchi comuni in qualsiasi template --}}

@if(isset($blocks) && is_array($blocks))
    @foreach($blocks as $block)
        @if(isset($block['type']) && isset($block['data']))
            @switch($block['type'])
                {{-- Blocchi Comuni disponibili in tutti i template --}}
                @case('two_column')
                    @include('blocks.two-column', ['block' => $block])
                    @break
                
                @case('content_block')
                    @include('blocks.content-block', ['block' => $block])
                    @break
                
                @case('call_to_action')
                    @include('blocks.call-to-action', ['block' => $block])
                    @break
                
                @case('cards_grid')
                    <section class="cards-grid-section py-16">
                        <div class="container mx-auto px-4">
                            @if(isset($block['data']['section_title']) && $block['data']['section_title'])
                                <div class="text-center mb-12">
                                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                                        {{ $block['data']['section_title'] }}
                                    </h2>
                                    @if(isset($block['data']['section_description']) && $block['data']['section_description'])
                                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                                            {{ $block['data']['section_description'] }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                            
                            @if(isset($block['data']['cards']) && is_array($block['data']['cards']))
                                <div class="grid gap-8 {{ 'grid-cols-1 md:grid-cols-' . ($block['data']['grid_columns'] ?? '3') }}">
                                    @foreach($block['data']['cards'] as $card)
                                        @include('components.card', ['card' => $card])
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </section>
                    @break
                
                @case('gallery')
                    <section class="gallery-section">
                            @if(isset($block['data']['title']) && $block['data']['title'])
                                <div class="text-center mb-12">
                                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                                        {{ $block['data']['title'] }}
                                    </h2>
                                    @if(isset($block['data']['description']) && $block['data']['description'])
                                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                                            {{ $block['data']['description'] }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                            
                            @if(isset($block['data']['images']) && is_array($block['data']['images']))
                                @include('components.gallery', [
                                    'gallery' => [
                                        'images' => $block['data']['images'],
                                        'grid_classes' => (count($block['data']['images']) > 1) ? 'grid-cols-2 md:grid-cols-' . ($block['data']['grid_columns'] ?? '4') : 'grid-cols-1',
                                        'classes' => 'grid gap-4'
                                    ]
                                ])
                            @endif
                    </section>
                    @break
                
                @case('contact_form')
                    <section class="contact-form-section py-16">
                        <div class="container mx-auto px-4">
                            <div class="max-w-2xl mx-auto">
                                @include('components.contact-form', ['form' => $block['data']])
                            </div>
                        </div>
                    </section>
                    @break

                @case('editor')
                    <section class="editor-section py-16">
                        <div class="container mx-auto">
                            <div class="">
                                @include('components.editor', ['editor' => $block['data']])
                            </div>
                        </div>
                    </section>
                    @break
                
                {{-- Blocchi Specifici del Template Homepage --}}
                @case('hero')
                    @if(isset($block['data']['slides']) && is_array($block['data']['slides']))
                        <section class="hero-section relative h-screen overflow-hidden">
                            @foreach($block['data']['slides'] as $index => $slide)
                                @php
                                    $backgroundImage = isset($slide['background_image']) ? asset('storage/' . $slide['background_image']) : '';
                                @endphp
                                <div class="hero-slide absolute inset-0 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                                     @if($backgroundImage) style="background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center;" @endif>
                                    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                                    <div class="relative z-10 flex items-center justify-center h-full">
                                        <div class="text-center text-white">
                                            <h1 class="text-5xl md:text-7xl font-bold mb-8">
                                                {{ $slide['title'] }}
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </section>
                    @endif
                    @break
                
                @case('sectors')
                    {{-- Implementazione per sectors --}}
                    <section class="sectors-section py-16">
                        <div class="container mx-auto px-4">
                            <h2 class="text-3xl font-bold text-center mb-12">Our Sectors</h2>
                            {{-- Contenuto sectors specifico --}}
                        </div>
                    </section>
                    @break
                
                {{-- Blocchi per template News --}}
                @case('news_gallery')
                    <section class="news-gallery-section py-16">
                        <div class="container mx-auto px-4">
                            <h2 class="text-3xl font-bold text-center mb-12">Latest News</h2>
                            {{-- Implementazione news gallery --}}
                        </div>
                    </section>
                    @break
                
                {{-- Fallback per blocchi non riconosciuti --}}
                @default
                    <div class="unknown-block py-8">
                        <div class="container mx-auto px-4 text-center text-gray-500">
                            <p>Block type "{{ $block['type'] }}" not supported in this template</p>
                        </div>
                    </div>
            @endswitch
        @endif
    @endforeach
@endif