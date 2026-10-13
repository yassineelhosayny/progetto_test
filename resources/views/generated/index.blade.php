{{-- resources/views/generated/index.blade.php --}}
@extends('layouts.app')

@section('title', ucfirst($resourceName))

@section('content')
    <div class="container mx-auto generated-resource-index">
        {{-- Header della pagina --}}
        <div class="page-header mb-8">
            <div class="mx-auto px-4">
                <h1>{{ ucfirst($resourceName) }}</h1>

                @if($resource->description ?? false)
                    <p class="text-lg text-gray-600 mb-4">
                        {{ $resource->description }}
                    </p>
                @endif

                <div class="text-sm text-gray-500">
                    {{ $records->total() }} {{ Str::plural('elemento', $records->total()) }} trovati
                </div>
            </div>
        </div>

        {{-- Contenuto principale --}}
        @if($records->count() > 0)
            {{-- Griglia degli elementi --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($records as $record)
                    <article class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        {{-- Immagine placeholder o reale --}}
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            @if(isset($record->image) && $record->image)
                                <img src="{{ asset('storage/' . $record->image) }}"
                                    alt="{{ $record->title ?? $record->name ?? 'Immagine' }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </div>

                        {{-- Contenuto card --}}
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                {{ $record->title ?? $record->name ?? 'Elemento senza titolo' }}
                            </h3>

                            @if(isset($record->description) && $record->description)
                                <p class="text-gray-600 text-sm mb-3 line-clamp-3">
                                    {{ Str::limit(strip_tags($record->description), 100) }}
                                </p>
                            @endif

                            {{-- Metadati --}}
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                @if(isset($record->created_at))
                                    <span>{{ $record->created_at->format('d/m/Y') }}</span>
                                @endif

                                @if(isset($record->category))
                                    <span class="bg-gray-100 px-2 py-1 rounded">
                                        {{ $record->category }}
                                    </span>
                                @endif
                            </div>

                            {{-- Link dettaglio --}}
                            <a href="{{ route('generated.resource.show', [$resourceName, $record->slug]) }}"
                                class="inline-block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                                Leggi di più
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Paginazione --}}
            <div class="flex justify-center">
                {{ $records->links() }}
            </div>
        @else
            {{-- Stato vuoto --}}
            <div class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    Nessun elemento trovato
                </h3>
                <p class="text-gray-600">
                    Non ci sono ancora elementi in questa sezione.
                </p>
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .line-clamp-3 {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
        </style>
    @endpush
@endsection