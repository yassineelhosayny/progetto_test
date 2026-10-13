@extends('layouts.app')

@section('title', 'News - ' . ucfirst($category))

@section('meta')
    <meta name="description" content="Tutte le news della categoria {{ ucfirst($category) }}">
    <meta property="og:title" content="News {{ ucfirst($category) }} - {{ config('app.name') }}">
    <meta property="og:description" content="Tutte le news della categoria {{ ucfirst($category) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('news.category', $category) }}">
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('homepage') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="mx-2 text-gray-400">/</span>
                    <a href="{{ route('news.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
                        News
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="mx-2 text-gray-400">/</span>
                    <span class="text-gray-500">{{ ucfirst($category->name) }}</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
            News: {{ ucfirst($category->name) }}
        </h1>
        <p class="text-center">
            Tutte le news della categoria {{ ucfirst($category->name) }}
        </p>
        
        {{-- Back to all news --}}
        <div class="mt-6">
            <a href="{{ route('news.index') }}" 
               class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors">
                ← Visualizza tutte le news
            </a>
        </div>
    </div>

    {{-- News Grid --}}
    @if($news->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            @foreach($news as $newsItem)
                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    {{-- Featured badge --}}
                    @if($newsItem->featured)
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                ⭐ In evidenza
                            </span>
                        </div>
                    @endif

                    {{-- Image placeholder --}}
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 relative">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-white text-sm opacity-75">{{ $newsItem->category->name }}</span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6">
                        <div class="flex items-center text-sm text-gray-500 mb-2">
                            <span>{{ $newsItem->published_at ? $newsItem->published_at->format('d M Y') : $newsItem->created_at->format('d M Y') }}</span>
                            <span class="mx-2">•</span>
                            <span class="text-blue-600">{{ ucfirst($newsItem->category->name) }}</span>
                        </div>
                        
                        <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                            <a href="{{ route('news.show', $newsItem->slug) }}" class="hover:text-blue-600 transition-colors">
                                {{ $newsItem->title }}
                            </a>
                        </h2>
                        
                        @if($newsItem->excerpt)
                            <p class="text-gray-600 mb-4 line-clamp-3">
                                {{ $newsItem->excerpt }}
                            </p>
                        @endif

                        <div class="flex justify-between items-center">
                            <a href="{{ route('news.show', $newsItem->slug) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                Leggi tutto →
                            </a>
                            
                            @if($newsItem->tags)
                                <div class="flex gap-1">
                                    @foreach(array_slice($newsItem->tags, 0, 2) as $tag)
                                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">
                                            #{{ $tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center">
            {{ $news->appends(['category' => $category])->links() }}
        </div>
    @else
        {{-- Empty state --}}
        <div class="text-center py-16">
            <div class="max-w-md mx-auto">
                <div class="text-6xl mb-4">📂</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">
                    Nessuna news in questa categoria
                </h3>
                <p class="text-gray-600 mb-6">
                    Non ci sono ancora news pubblicate nella categoria "{{ ucfirst($category) }}".
                </p>
                <a href="{{ route('news.index') }}" 
                   class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Visualizza tutte le news
                </a>
            </div>
        </div>
    @endif
</div>
@endsection