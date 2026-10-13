{{-- Tailored Solutions Block Component --}}
<section
    class="tailored-solutions-block relative min-h-screen flex flex-col items-center justify-center overflow-hidden">

    {{-- Background Image --}}
    @if(isset($block['data']['image']) && $block['data']['image'])
        <div class="">
            <img src="{{ Storage::url($block['data']['image']) }}"
                alt="{{ $block['data']['title'] ?? 'Tailored Solutions' }}"
                class="w-full h-[350px] lg:h-[1080px] object-cover">
        </div>
    @endif

    {{-- Content --}}
    <div class="relative z-10 container py-16 bg-blue-light">
        <div class="content">

            <div class="relative flex flex-col gap-4 px-8 lg:px-0">
                @if(isset($block['data']['title']) && $block['data']['title'])
                    <h4>
                        {{ strtoupper($block['data']['title']) }}
                    </h4>
                @endif

                {{-- Sottotitolo --}}
                @if(isset($block['data']['subtitle']) && $block['data']['subtitle'])
                    <h6>
                        {{ strtoupper($block['data']['subtitle']) }}
                    </h6>
                @endif

                {{-- Descrizione --}}
                @if(isset($block['data']['description']) && $block['data']['description'])
                    <div class="text-white">{!! $block['data']['description'] !!}</div>
                @endif

                <svg class="icon icon--plus fill-blue-lighter absolute -top-10 -left-2 lg:-left-10">
                    <use xlink:href="#plus"></use>
                </svg>
            </div>

        </div>
    </div>
</section>