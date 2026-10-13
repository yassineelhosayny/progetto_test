
<div class="page-content">
    {{-- Page Header --}}
    @if($page->title)
        <div class="page-header bg-gray-50 py-12">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold text-gray-900 text-center">{{ $page->title }}</h1>
                @if($page->description)
                    <p class="text-xl text-gray-600 mt-4 text-center max-w-3xl mx-auto">{{ $page->description }}</p>
                @endif
            </div>
        </div>
    @endif

    {{-- Page Blocks --}}
    <div class="page-blocks">
        
    </div>
</div>