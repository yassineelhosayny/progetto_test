@php
    use App\Models\Settings;
    $settings = Settings::getSettings();
@endphp

@if($settings->instagram_url)
    <a href="{{ $settings->instagram_url }}" target="_blank" rel="noopener noreferrer" class="px-2 border-r border-white">
        <svg class="icon icon--social fill-white">
            <use xlink:href="#instagram"></use>
        </svg>
    </a>
@endif
@if($settings->facebook_url)
    <a href="{{ $settings->facebook_url }}" target="_blank" rel="noopener noreferrer" class="px-2 border-r border-white">
        <svg class="icon icon--social fill-white">
            <use xlink:href="#facebook"></use>
        </svg>
    </a>
@endif
@if($settings->twitter_url)
    <a href="{{ $settings->twitter_url }}" target="_blank" rel="noopener noreferrer" class="px-2 border-r border-white">
        <svg class="icon icon--social fill-white">
            <use xlink:href="#twitter"></use>
        </svg>
    </a>
@endif
@if($settings->youtube_url)
    <a href="{{ $settings->youtube_url }}" target="_blank" rel="noopener noreferrer" class="px-2 border-r border-white">
        <svg class="icon icon--social fill-white">
            <use xlink:href="#youtube"></use>
        </svg>
    </a>
@endif
@if($settings->linkedin_url)
    <a href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="px-2 border-r border-white">
        <svg class="icon icon--social fill-white">
            <use xlink:href="#linkedin"></use>
        </svg>
    </a>
@endif
