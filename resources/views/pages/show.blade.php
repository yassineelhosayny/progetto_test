{{-- @extends('layouts.app') --}}

{{-- @section('content') --}}
@php
    /** @var \App\Models\Page $page */
    
    // Condividi la variabile $page con il layout per i meta tag SEO
    view()->share('page', $page);
    
    // titolo/descrizione (Spatie fallback)
    $title = method_exists($page, 'getTranslation') ? $page->getTranslation('title', 'it', false) : ($page->title ?? '');
    $description = method_exists($page, 'getTranslation') ? $page->getTranslation('description', 'it', false) : ($page->description ?? '');

    // raw blocks: può essere array o JSON string, spesso wrappato per lingua ['it' => [...]]
    $raw = $page->getAttributes()['blocks'] ?? $page->blocks ?? null;
    if (is_string($raw) && $raw !== '') {
        $decoded = json_decode($raw, true);
        $blocks = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
    } elseif (is_array($raw)) {
        $blocks = $raw;
    } else {
        $blocks = [];
    }
    if (isset($blocks['it']) && is_array($blocks['it'])) {
        $blocks = $blocks['it'];
    }
@endphp

@extends('layouts.app')

@section('content')

<article class="filament-page-preview">

    @foreach($blocks as $block)
        @php
            $typeRaw = $block['type'] ?? '';
            $data = $block['data'] ?? [];
            // normalizza chiavi blocco se serve
            $data['title'] = $data['title'] ?? $data['section_title'] ?? '';
            $data['description'] = $data['description'] ?? $data['section_content'] ?? '';
        @endphp

        @switch(strtolower(str_replace([' ', '_'], '-', trim($typeRaw))))
            @case('hero')
                @includeIf('sections.hero-section', ['data' => $data, 'page' => $page])
                @break

            @default
                @includeIf('sections.unknown-block', ['data' => $data, 'page' => $page])
        @endswitch
    @endforeach
</article>

@endsection