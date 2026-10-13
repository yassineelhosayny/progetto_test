@extends('layouts.app')

@section('title', $page->title)

@section('meta')
    {{-- <meta name="description" content="{{ $page->meta_description }}"> --}}
    {{-- <meta property="og:title" content="{{ $page->meta_title ?: $page->title }}"> --}}
    {{-- <meta property="og:description" content="{{ $page->meta_description }}">  --}}
    <meta property="og:type" content="website">
    {{-- <meta property="og:url" content="{{ $page->canonical_url }}">
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif --}}
@endsection

@section('content')
    <div class="homepage-content">
        {{-- Home Page Blocks (senza header tradizionale) --}}
        <div class="homepage-blocks">
            @php 
                $currentLocale = app()->getLocale();
                $currentBlocks = $page->getTranslation('blocks', $currentLocale) ?? [];
                $heroBlock = null;
                foreach($currentBlocks as $block) {
                    if(isset($block['type']) && $block['type'] === 'hero') {
                        $heroBlock = $block;
                        break;
                    }
                } 
            @endphp

            @if(view()->exists("blocks.homepage.hero") && $heroBlock !== null)
                @include("blocks.homepage.hero", ['block' => $heroBlock])
            @endif

            <div class="custom-bg">
                @if($currentBlocks && is_array($currentBlocks) && count($currentBlocks) > 0)
                    @foreach($currentBlocks as $block)
                        @if(isset($block['type']) && $block['type'] !== 'hero')
                            @if(view()->exists("blocks.homepage.{$block['type']}"))

                                @include("blocks.homepage.{$block['type']}", ['block' => $block])

                            @elseif(view()->exists("blocks.{$block['type']}"))

                                @include("blocks.{$block['type']}", ['block' => $block])

                            @else
                                @include('partials.render-blocks', ['blocks' => [$block]])
                            @endif
                        @endif
                    @endforeach
                @endif
            </div> 

            <div class="split-words split-letters mt-[100vh] p-8 text-center w-full">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </div>
        </div>
    </div>
@endsection