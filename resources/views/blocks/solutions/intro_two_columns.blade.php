@php
    $title = $block['data']['title'] ?? 'Default Title';
    $intro_bold = $block['data']['intro_sx'] ?? '';
    $description_regular = $block['data']['description_sx'] ?? '';
    $description_italic = $block['data']['description_dx'] ?? '';
    $imagePath = $block['data']['image'] ?? '';
@endphp

<section class="section-intro-two-columns lg:pb-24">
    <div class="container">
        <div class="px-2 lg:px-0">
            <div class="title relative mb-12 px-4 lg:px-0">
                <h3 class="text-blue-light uppercase">{{ $title }}</h3>
                <svg class="icon icon--plus fill-blue-light absolute -top-10 -left-5 lg:-left-10">
                    <use xlink:href="#plus"></use>
                </svg>
            </div>
            <div class="two-columns-wrapper text-blue-light grid grid-cols-1 md:grid-cols-2 gap-8 ">

                <div class="first-column text-content flex flex-col justify-end">
                    <div class="mb-8">
                        {!! $intro_bold !!}
                    </div>
                    <div class="regular text-white">
                        {!! $description_regular !!}
                    </div>
                </div>
                <div class="second-column">
                    <div class="image mb-2">
                        @if($imagePath)
                            <img src="{{ Storage::url($imagePath) }}" alt="Intro Image">
                        @endif
                    </div>
                    <div class="italic text-content">
                        {!! $description_italic !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>