@extends('layouts.app')

@section('title', $page->title)

@section('meta')
    {{-- <meta name="description" content="{{ $page->meta_description }}">
    <meta property="og:title" content="{{ $page->meta_title ?: $page->title }}">
    <meta property="og:description" content="{{ $page->meta_description }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $page->canonical_url }}">
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif --}}
@endsection

@section('content')
<div class="page-content">
    {{-- Page Header --}}
    @if($page->title)
        <div class="page-header bg-gray-50 py-12">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold text-gray-900 text-center">{{ $page->title }}</h1>
                @if($page->description)
                    <h6 class="text-center">{!! $page->description !!}</h6>
                @endif
            </div>
        </div>
    @endif

    {{-- Page Blocks --}}
    <div class="page-blocks">
        @php 
            $currentBlocks = $page->getTranslation('blocks', app()->getLocale()) ?? [];
        @endphp
        
        @if($currentBlocks && is_array($currentBlocks))
            @foreach($currentBlocks as $block)
                @if(isset($block['type']))
                    @include("blocks.{$block['type']}", ['block' => $block])
                @endif
            @endforeach
        @endif
    </div>
</div>
@endsection