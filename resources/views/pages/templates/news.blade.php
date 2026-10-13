@extends('layouts.app')

@section('title', $page->title)

{{-- @if($page->meta_description)
@section('meta_description', $page->meta_description)
@endif --}}

@section('content')
    <div class="container page-container mx-auto px-4 py-8">
        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4" style="color: black">{{ $page->title }}</h1>
            @if($page->description)
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    {{ $page->description }}
                </p>
            @endif
        </div>

        <div class="mb-8">
        <div class="flex flex-col md:flex-row gap-4 justify-between items-center">

            {{-- Search --}}
            <div class="w-full md:w-auto">
                <form method="GET" action="{{ route('pages.show', $page->slug) }}" class="flex gap-2">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cerca nelle news..." 
                        class="flex-1 md:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Cerca
                    </button>
                </form>
            </div>

            {{-- Filtro categoria --}}
            <div class="flex gap-2 items-center">
                <span class="text-sm text-gray-600">Filtra per:</span>
                <a href="{{ route('pages.show', $page->slug) }}" 
                   class="px-3 py-1 text-sm rounded-full {{ !request('category') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors">
                    Tutte
                </a>
                {{-- @foreach($categories as $category)
                    <a href="{{ route('pages.show', array_merge([$page->slug], ['category' => $category])) }}" 
                       class="px-3 py-1 text-sm rounded-full {{ request('category') === $category ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors">
                        {{ ucfirst($category) }}
                    </a>
                @endforeach --}}
                <a href="{{ route('pages.show', array_merge([$page->slug], ['featured' => 1])) }}" 
                   class="px-3 py-1 text-sm rounded-full {{ request('featured') ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} transition-colors">
                    ⭐ In evidenza
                </a>
            </div>
        </div>
    </div>

        {{-- News Grid --}}
        @if(isset($news) && $news->count() > 0)
            <div class="news-gallery-grid" id="newsGrid">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @foreach($news as $newsItem)
                        <article
                            class="news-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
                            data-category="{{ $newsItem->category ?? '' }}">

                            {{-- Featured badge --}}
                            @if($newsItem->featured)
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                        ⭐ In evidenza
                                    </span>
                                </div>
                            @endif

                            {{-- Image --}}
                            @if($newsItem->featured_image)
                                <div class="h-48 relative">
                                    <img src="{{ Storage::url($newsItem->featured_image) }}" alt="{{ $newsItem->title }}"
                                        class="w-full h-full object-cover" loading="lazy">
                                </div>
                            @else
                                <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 relative">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            @endif

                            <div class="p-6 flex flex-col gap-4">
                                {{-- Meta info --}}
                                <div class="flex items-center justify-between text-sm text-gray-600">
                                    @if($newsItem->category)
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">
                                            {{ ucfirst($newsItem->category) }}
                                        </span>
                                    @endif
                                    <time @if($newsItem->published_at) datetime="{{ $newsItem->published_at->toISOString() }}" @endif>
                                        {{ $newsItem->published_at ? $newsItem->published_at->format('d/m/Y') : '' }}
                                    </time>
                                </div>

                                {{-- Title --}}
                                <h6 class="text-lg font-semibold mb-3 hover:text-blue-600 transition-colors">
                                    <a class="text-black" href="{{ route('news.show', $newsItem->slug) }}">
                                        {{ $newsItem->title }}
                                    </a>
                                </h5>

                                {{-- Excerpt --}}
                                @if($newsItem->excerpt_or_description)
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        {{ Str::limit($newsItem->excerpt_or_description, 120) }}
                                    </p>
                                @endif

                                {{-- Tags --}}
                                @if($newsItem->tags_array && count($newsItem->tags_array) > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($newsItem->tags_array, 0, 3) as $tag)
                                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Read more --}}
                                <a href="{{ route('news.show', $newsItem->slug) }}"
                                    class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                                    Leggi di più →
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="flex justify-center">
                    {{ $news->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nessuna news disponibile</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Non ci sono ancora news pubblicate. Torna a trovarci presto!
                </p>
            </div>
        @endif
    </div>

    {{-- Featured News Section --}}
    @if(isset($featuredNews) && $featuredNews->count() > 0)
        <section class="featured-news mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">News in Evidenza</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredNews as $newsItem)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        @if($newsItem->featured_image)
                            <div class="aspect-video">
                                <img src="{{ Storage::url($newsItem->featured_image) }}" alt="{{ $newsItem->title }}"
                                    class="w-full h-full object-cover" loading="lazy">
                            </div>
                        @endif
                        <div class="p-4">
                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                                @if($newsItem->category)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">
                                        {{ ucfirst($newsItem->category) }}
                                    </span>
                                @endif
                                <time datetime="{{ $newsItem->published_at->toISOString() }}">
                                    {{ $newsItem->formatted_published_date }}
                                </time>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                <a href="{{ route('news.show', $newsItem->slug) }}" class="hover:text-blue-600 transition-colors">
                                    {{ $newsItem->title }}
                                </a>
                            </h3>
                            @if($newsItem->excerpt_or_description)
                                <p class="text-gray-600 text-sm leading-relaxed">
                                    {{ Str::limit($newsItem->excerpt_or_description, 120) }}
                                </p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Filtri per categoria --}}
    @if(isset($categories) && $categories->count() > 0)
        <div class="mb-8">
            <div class="flex flex-wrap gap-2 justify-center">
                <button class="category-filter px-4 py-2 rounded-full bg-blue-600 text-white text-sm font-medium"
                    data-category="">
                    Tutte le News
                </button>
                @foreach($categories as $category)
                    <button
                        class="category-filter px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 text-sm font-medium transition-colors"
                        data-category="{{ $category }}">
                        {{ ucfirst($category) }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- JavaScript for category filtering --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterBtns = document.querySelectorAll('.category-filter');
            const newsCards = document.querySelectorAll('.news-card');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const category = this.dataset.category;

                    // Update active button
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-blue-600', 'text-white');
                        b.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                    });
                    this.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                    this.classList.add('bg-blue-600', 'text-white');

                    // Filter cards
                    newsCards.forEach(card => {
                        const cardCategory = card.dataset.category;
                        if (category === '' || cardCategory === category) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
@endsection