@extends('layouts.app')

{{-- @section('title', $news->meta_title ?: $news->title) --}}

@section('meta')
    {{--
    <meta name="description" content="{{ $news->meta_description ?: $news->excerpt }}"> --}}
    {{--
    <meta name="keywords" content="{{ $news->meta_keywords ?: '' }}"> --}}
    {{--
    <meta property="og:title" content="{{ $news->meta_title ?: $news->title }}"> --}}
    {{--
    <meta property="og:description" content="{{ $news->meta_description ?: $news->excerpt }}"> --}}
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('news.show', $news->slug) }}">
    @if($news->featured_image)
        <meta property="og:image" content="{{ asset('storage/' . $news->featured_image) }}">
    @endif
@endsection

@section('content')
    <article class="single-news custom-bg min-h-screen">
        <div class="container grid grid-cols-[70%_30%] gap-8 py-12">
            {{-- News Content --}}
            <section>
                <div class="image w-[85%]">
                    @if($news->excerpt_image)
                        <img src="{{ Storage::url($news->excerpt_image) }}" alt="{{ $news->title }}"
                            class="w-full h-auto object-cover mb-6 rounded-lg shadow-md">
                    @endif
                </div>
                @php
                    $locale = \Illuminate\Support\Facades\App::getLocale();
                    $date = $news->publication_date;
                    $formattedDate = optional($date)->translatedFormat('d F Y', $locale);
                @endphp
                <ul class="list-disc">
                    <li class="flex items-center gap-2 text-gray-500 mb-4">
                        <x-heroicon-o-calendar-days class="w-6 h-6"></x-heroicon-o-calendar-days>
                        <time datetime="{{ optional($date)->toISOString() }}">
                            {{ $formattedDate ?? '' }}
                        </time>
                    </li>
                </ul>

                {{-- Excerpt --}}
                @if($news->excerpt)
                    <div class="border-b border-white pb-8 mb-8 mt-8">
                        <p class="text-white">{{ $news->excerpt }}</p>
                    </div>
                @endif

                {{-- Content --}}
                <div class="description flex flex-col gap-8 text-white mt-6">
                    {!! $news->description !!}
                </div>
            </section>

            {{-- Back to news --}}
            <aside>
                <a href="{{ route('pages.show', 'news') }}"
                    class="bg-[#00466A] px-4 py-1 uppercase text-white inline-flex items-center gap-4 rounded-sm">
                    <div class="xsmall"><b>Back to list</b></div>
                    <x-heroicon-s-backspace class="h-4"></x-heroicon-s-backspace>
                </a>
                <div class="photo-gallery">
                    @if($news->blocks)
                        @foreach ($news->blocks as $block)
                            @if($block['type'] === 'photo_gallery' && count($block['data']) > 0)
                                <div class="flex flex-col gap-4 mt-8">
                                    <div class="swiper swiper--photo-gallery w-full">
                                        <div class="swiper-wrapper">
                                            @foreach ($block['data']['gallery'] as $key => $photo)
                                                <div class="swiper-slide">
                                                    @include('components.modal-opener', ['photo' => $photo, 'key' => $key])
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="swiper-pagination--photo-gallery"></div>
                                </div>
                                @foreach ($block['data']['gallery'] as $key => $photo)
                                    <div data-modal="modal-{{ $key }}" class="modal fixed top-0 left-0 flex items-center justify-center opacity-0 bg-[rgba(0,0,0,0.5)] -z-1 w-screen h-screen">
                                        <div class="max-w-[75%] h-[75%]">
                                            <img src="{{ Storage::url($photo['photo']) }}" alt="" class="w-auto h-full object-cover rounded-lg shadow-md">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                </div>
            </aside>
        </div>
    </article>

@endsection