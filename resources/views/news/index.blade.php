@extends('layouts.app')

@section('title', 'News')

@section('meta')
    <meta name="description" content="Tutte le ultime news e articoli del nostro blog">
    <meta property="og:title" content="News - {{ config('app.name') }}">
    <meta property="og:description" content="Tutte le ultime news e articoli del nostro blog">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ LaravelLocalization::localizeURL(route('news.index')) }}">
@endsection

@section('content')
    <div class="news-archive custom-bg min-h-screen">
        <div class="container">
            <h3 class="text-white">News</h3>

            {{-- News Grid --}}
            <div class="flex flex-col-reverse gap-8 mt-12 pb-24 lg:grid lg:grid-cols-[70%_30%] lg:gap-24">
                @if($news->count() > 0)
                    <div class="flex flex-col gap-8">
                        @foreach($news as $newsItem)
                            @include('components.news-card', ['newsItem' => $newsItem])
                        @endforeach
                    </div>
                @else
                    {{-- Empty state --}}
                    <div class="text-center py-16">
                        <div class="max-w-md mx-auto">
                            <div class="text-6xl mb-4">📰</div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Nessuna news trovata</h3>
                            <p class="text-gray-600 mb-6">
                                @if(request('search') || request('category'))
                                    Non ci sono news che corrispondono ai tuoi criteri di ricerca.
                                @else
                                    Non ci sono ancora news pubblicate.
                                @endif
                            </p>
                            @if(request('search') || request('category'))
                                <a href="{{ route('news.index') }}"
                                    class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Visualizza tutte le news
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                @include('components.news-categories-filters', ['years' => $years])
            </div>
            {{-- Filtro per anno --}}
        </div>
    </div>
@endsection