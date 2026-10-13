{{-- resources/views/generated/show.blade.php --}}
@extends('layouts.app')

@section('title', $record->title ?? $record->name ?? 'Dettaglio')

@section('content')
<div class="generated-resource-show">
    {{-- Breadcrumb --}}
    <nav class="bg-gray-100 py-3 mb-8">
        <div class="container mx-auto px-4">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li>
                    <a href="{{ url('/') }}" class="hover:text-blue-600">Home</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="{{ route('generated.resource.index', $resourceName) }}" class="hover:text-blue-600">
                        {{ ucfirst($resourceName) }}
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-900">{{ $record->title ?? $record->name ?? 'Dettaglio' }}</span>
                </li>
            </ol>
        </div>
    </nav>

    <main class="container mx-auto px-4">
        <article class="max-w-4xl mx-auto">
            {{-- Header dell'articolo --}}
            <header class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    {{ $record->title ?? $record->name ?? 'Elemento senza titolo' }}
                </h1>
                
                {{-- Metadati --}}
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-6">
                    @if(isset($record->created_at))
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $record->created_at->format('d/m/Y') }}
                        </span>
                    @endif
                    
                    @if(isset($record->category))
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-medium">
                            {{ $record->category }}
                        </span>
                    @endif
                    
                    @if(isset($record->status) && $record->status)
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                            {{ ucfirst($record->status) }}
                        </span>
                    @endif
                </div>
                
                {{-- Descrizione breve --}}
                @if(isset($record->description) && $record->description)
                    <div class="text-lg text-gray-700 leading-relaxed mb-6">
                        {{ $record->description }}
                    </div>
                @endif
            </header>

            {{-- Immagine principale --}}
            @if(isset($record->image) && $record->image)
                <div class="mb-8">
                    <img src="{{ asset('storage/' . $record->image) }}" 
                         alt="{{ $record->title ?? $record->name ?? 'Immagine' }}"
                         class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
                </div>
            @endif

            {{-- Contenuto principale --}}
            <div class="prose prose-lg max-w-none mb-8">
                @if(isset($record->content) && $record->content)
                    {!! $record->content !!}
                @elseif(isset($record->body) && $record->body)
                    {!! $record->body !!}
                @else
                    <p class="text-gray-600 italic">Nessun contenuto disponibile.</p>
                @endif
            </div>

            {{-- Campi aggiuntivi dinamici --}}
            @php
                $excludedFields = ['id', 'slug', 'title', 'name', 'description', 'content', 'body', 'image', 'category', 'status', 'created_at', 'updated_at'];
                $additionalFields = collect($record->getAttributes())
                    ->except($excludedFields)
                    ->filter(function($value) {
                        return !is_null($value) && $value !== '';
                    });
            @endphp

            @if($additionalFields->count() > 0)
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informazioni aggiuntive</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($additionalFields as $field => $value)
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">
                                    {{ ucfirst(str_replace('_', ' ', $field)) }}
                                </dt>
                                <dd class="text-sm text-gray-900">
                                    @if(is_array($value))
                                        {{ implode(', ', $value) }}
                                    @elseif(is_bool($value))
                                        {{ $value ? 'Sì' : 'No' }}
                                    @elseif(is_string($value) && filter_var($value, FILTER_VALIDATE_URL))
                                        <a href="{{ $value }}" target="_blank" class="text-blue-600 hover:underline">
                                            {{ $value }}
                                        </a>
                                    @else
                                        {{ $value }}
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </dl>
                </div>
            @endif

            {{-- Azioni --}}
            <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                <a href="{{ route('generated.resource.index', $resourceName) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Torna alla lista
                </a>
                
                {{-- Condivisione (opzionale) --}}
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Condividi:</span>
                    <button onclick="navigator.share ? navigator.share({title: document.title, url: window.location.href}) : copyToClipboard(window.location.href)" 
                            class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm rounded-md transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        Condividi
                    </button>
                </div>
            </div>
        </article>
    </main>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Link copiato negli appunti!');
    });
}
</script>
@endpush
@endsection