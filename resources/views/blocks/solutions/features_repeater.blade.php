<section class="section-features-repeater">
    <div class="container">
        <div class="features-wrapper flex flex-col gap-12">
            @foreach($block['data']['features'] as $key =>$feature)
            @php
                $isLastChild = $key == count($block['data']['features']) - 1
            @endphp
            <div class="feature-item mb-12 relative">
                @if ($isLastChild)
                    <p class="text-blue-light border-t-6 border-blue-light pt-2 mt-2"><b>{{ $feature['title'] }}</b></p>
                    <div class="description">
                        <h6 class="text-blue-light h8">{{ $feature['subtitle'] }}</h6>
                        <div class="text-white">{!! $feature['description'] !!}</div>
                        <svg class="icon icon--plus fill-blue-light mt-6 absolute -top-15 -left-10">
                            <use xlink:href="#plus"></use>
                        </svg>
                    </div>
                @else
                    <h6 class="text-white">{{ $feature['title'] }}</h6>
                    <div class="description border-t-2 pt-2 border-blue-light mt-2">
                        <p class="text-blue-light h8">{{ $feature['subtitle'] }}</p>
                        <div class="text-white h8">{!! $feature['description'] !!}</div>
                        <svg class="icon icon--plus fill-blue-light mt-6 absolute -top-15 -left-10">
                            <use xlink:href="#plus"></use>
                        </svg>
                    </div>
                @endif
                    <div class="images flex flex-col lg:flex-row gap-4">
                        @if ($feature['featured_images'] && count($feature['featured_images']) > 0)
                            @foreach ($feature['featured_images'] as $image)
                                <div class="featured-image mt-6 {{ $isLastChild ? 'w-53 h-53' : 'w-40 h-40' }} border border-blue-light p-2 rounded-full overflow-hidden">
                                    <img class="object-cover w-full h-full rounded-full" src="{{ Storage::url($image['featured_image']) }}" alt="Feature Image">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>