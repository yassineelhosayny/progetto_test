@extends('layouts.app')

@section('title', $page->title ?? 'Solutions')
@section('description', $page->description ?? '')

@section('content')
<div class="solutions-template custom-bg">

    {{-- Blocchi configurabili (comuni a tutti i template) --}}
        @php 
            $currentLocale = app()->getLocale();
            $currentBlocks = $page->getTranslation('blocks', $currentLocale) ?? [];
        @endphp
        
        @if($currentBlocks && is_array($currentBlocks) && count($currentBlocks) > 0)
            @foreach($currentBlocks as $block)
                @if(isset($block['type']))
                    {{-- Prima prova blocchi specifici della solutions --}}
                    @if(view()->exists("blocks.solutions.{$block['type']}"))
                        @include("blocks.solutions.{$block['type']}", ['block' => $block])
                    {{-- Altrimenti prova blocchi generali --}}
                    @elseif(view()->exists("blocks.{$block['type']}"))
                        @include("blocks.{$block['type']}", ['block' => $block])
                    {{-- Fallback al sistema di rendering generale --}}
                    @else
                        @include('partials.render-blocks', ['blocks' => [$block]])
                    @endif
                @endif
            @endforeach
        @endif 
</div>
@endsection