@extends('layouts.app')

@section('title', $page->title ?? 'Contact Us')
@section('description', $page->description ?? '')

@php
    $settings = \App\Models\Settings::getSettings();
@endphp

@section('content')
    <div class="contact-template custom-bg min-h-screen flex items-center py-48">
        {{-- Header della pagina Contatti --}}
        <div class="container">
            <section class="">
                @if(isset($page->title) || isset($page->description))
                    <div class="mb-8">
                        @if(isset($page->title))
                            <h3 class="text-white">
                                {{ $page->title }}
                            </h3>
                        @endif

                        @if(isset($page->description))
                            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                                {!! $page->description !!}
                            </p>
                        @endif
                    </div>
                @endif

                {{-- Contenuto statico di contatto di base --}}
                @if(!isset($page->blocks) || count($page->blocks) === 0)
                    <div class="grid grid-cols-1 lg:grid-cols-[auto_auto_auto] gap-12">
                        {{-- Informazioni di contatto --}}
                        <iframe class="lg:w-120 h-96 w-full"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2849.817183205016!2d8.879657132668823!3d44.416397043929656!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12d34126c6042647%3A0x45dbe8e73e39b191!2sVia%20Giuseppe%20Spataro%2C%2036%2C%2016151%20Genova%20GE!5e0!3m2!1sit!2sit!4v1659022134776!5m2!1sit!2sit"
                            frameborder="0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" data-cmp-info="8"
                            id="fitvid0" async="false" data-cmp-ab="2"></iframe>
                        <div class="column text-white">{!! $settings->contacts ?? '' !!}</div>
                        <div class="column text-white">{!! $settings->opening_hours ?? '' !!}</div>
                    </div>
                @endif
            </section>

            {{-- Blocchi configurabili (comuni a tutti i template) --}}
            @include('partials.render-blocks', ['blocks' => $page->blocks ?? []])
        </div>
    </div>
@endsection