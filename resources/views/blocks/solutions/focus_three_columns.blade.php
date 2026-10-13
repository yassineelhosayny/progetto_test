<section class="section-focus-three-columns lg:pb-24">
    <div class="container">
        <div class="columns-wrapper grid grid-cols-1 lg:grid-cols-3 gap-8">
            @foreach($block['data']['columns'] as $column)
                <div class="column">
                    <div class="flex justify-center items-center">
                        <svg class="icon icon--plus fill-blue-light mb-6">
                            <use xlink:href="#plus"></use>
                        </svg>
                    </div>
                    @if($column['image'])
                        <div class="p-8">
                            <div class="border border-blue-light p-2 rounded-full">
                            <img class="w-full aspect-square object-cover rounded-full" src="{{ Storage::url($column['image']) }}" alt="Column Image">
                        </div>
                        </div>
                    @endif
                    <div class="text-wrapper flex flex-col gap-6 mt-6">
                        <h6 class="text-blue-light mb-10">{{ $column['title'] }}</h6>
                        <div class="text-white description">{!! $column['description'] !!}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="overflow-hidden relative py-12 lg:pt-12 lg:pb-0">
            <div class="naval-image w-[200%] lg:w-full">
                <img src="{{ Storage::url($block['data']['naval_image']) }}" class="transition-transform duration-300 w-full" alt="Naval Image">
            </div>
            <div class="image-navigation absolute h-full w-full lg:hidden top-0 left-0 flex justify-between items-center px-4">
                <div class="prev"><x-heroicon-s-chevron-left class="text-white w-8 h-8" /></div>
                <div class="next"><x-heroicon-s-chevron-right class="text-white w-8 h-8" /></div>
            </div>
        </div>
</section>