@php
    use App\Models\Page;

    $linkedPage = Page::find($block['data']['link']);
    $linkedUrl = $linkedPage ? route('pages.show', $linkedPage->slug) : '#';
@endphp

<section class="section-discover-more">
    <div class="container pb-24">
        <div class="title relative mb-12">
            <h3 class="uppercase text-white">Discover more about {{ $block['data']['title'] }}</h3>
            <svg class="icon icon--plus fill-blue-light absolute -top-10 -left-10">
                <use xlink:href="#plus"></use>
            </svg>
        </div>
        <div class="discover-wrapper flex flex-col gap-12 items-start lg:flex-row lg:items-center">
            <img src="{{ Storage::url($block['data']['icon']) }}" alt="Icon {{ $block['data']['title'] }}" class="discover-icon w-50 h-50">
            <div class="button-container flex flex-col gap-4">
                <h3 class="text-white">{{ $block['data']['title'] }}</h3>
                <a href="{{ $linkedUrl }}" class="btn">Discover More</a>
            </div>
        </div>
    </div>
</section>